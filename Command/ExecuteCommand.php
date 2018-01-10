<?php

/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 09.01.2018
 * Time: 09:29
 */
namespace Neogen\CommandSchedulerBundle\Command;

use Cron\CronExpression;
use Neogen\CommandSchedulerBundle\Entity\CommandEntity;
use Neogen\CommandSchedulerBundle\Model\ProcessModel;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ExecuteCommand extends Command
{
    /** @var \Doctrine\Common\Persistence\ObjectManager|object */
    protected $em;
    /** @var  OutputInterface */
    protected $output;
    /** @var  ProcessModel[] */
    private $processes;
    /** @var  string */
    private $environment;

    /**
     * ExecuteCommand constructor.
     * @param RegistryInterface $registry
     * @param $environment
     * @param null $name
     */
    public function __construct(RegistryInterface $registry, $environment, $name = null)
    {
        $this->em = $registry->getManager();
        $this->processes = [];
        $this->environment = $environment;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('command_scheduler:execute')
            ->setDescription('Execute scheduled commands')
            ->setHelp('This class is the entry point to execute all scheduled command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $commands = $this->em->getRepository(CommandEntity::class)->findBy([
            'disabled' => false,
            'locked' => false
        ]);

        if(count($commands)){
            /** @var CommandEntity $command */
            foreach ($commands as $command){
                $this->executeCommand($command);
            }
        }

        while(count($this->processes) > 0){
            $this->wait();
        }

        $this->em->clear();

        return 1;
    }

    /**
     * Wait for the children processes.
     */
    private function wait()
    {
        foreach($this->processes as $key=>$processModel){
            $process = $processModel->getProcess();
            if(!$process->isRunning()){
                $log = '';
                if(!empty($process->getOutput())){
                    $log .= "OUTPUT: ".$process->getOutput();
                }
                if(!empty($process->getErrorOutput())){
                    $log .= "ERROR OUTPUT: ".$process->getErrorOutput();
                }

                $commandModel = $processModel->getCommand();
                $commandModel->setLocked(false);
                $commandModel->setLastExecution(new \DateTime());
                $commandModel->setLastReturnCode(null == $process->getExitCode() ? 0 : $process->getExitCode());
                $commandModel->setLastLog($log);
                $commandModel->setLastRunDuration($processModel->getStartTime()->diff(new \DateTime())->s);
                $this->em->merge($commandModel);
                $this->em->flush();

                unset($this->processes[$key]);
            }
        }
    }

    /**
     * @param CommandEntity $commandEntity
     * @return int|null
     */
    private function executeCommand(CommandEntity $commandEntity)
    {

        if($commandEntity->isExecuteNow()){
            return $this->runCommand($commandEntity);
        }

        $cron        = CronExpression::factory($commandEntity->getCronExpression());
        $nextRunDate = $cron->getNextRunDate($commandEntity->getLastExecution());
        $now         = new \DateTime();

        if($nextRunDate < $now) {
            return $this->runCommand($commandEntity);
        }

        return null;
    }

    /**
     * @param CommandEntity $scheduledCommand
     * @return int
     */
    private function runCommand(CommandEntity $scheduledCommand)
    {
        $this->output->writeln([
            '<info>Start command:</info>'.$scheduledCommand->getCommand()
        ]);
        $this->em->getConnection()->beginTransaction();
        try {
            $notLockedCommand = $this->em
                ->getRepository(CommandEntity::class)
                ->findOneBy(['id' => $scheduledCommand, 'locked' => false]);
            if ($notLockedCommand === null) {
                $this->output->writeln(['<info>The command: '.$scheduledCommand->getCommand().' is not found!</info>']);
                return -1;
            }

            /** @var CommandEntity $scheduledCommand */
            $scheduledCommand = $notLockedCommand;
            $scheduledCommand->setLastExecution(new \DateTime());
            $scheduledCommand->setLocked(true);
            $scheduledCommand->setExecuteNow(false);
            $scheduledCommand = $this->em->merge($scheduledCommand);
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $this->output->writeln([sprintf('<cerror>Command with id: %s is locked %s</cerror>',
                $scheduledCommand->getId(),
                (!empty($e->getMessage()) ? sprintf('(%s)', $e->getMessage()) : ''))]);
            return -1;
        }
        try {
            $command = $this->getApplication()->find($scheduledCommand->getCommand());
        } catch (\InvalidArgumentException $e) {
            $scheduledCommand->setLastReturnCode(-1);
            $this->output->writeln(['<cerror>Cannot find ' . $scheduledCommand->getCommand() . '</cerror>']);

            $this->em->merge($scheduledCommand);
            $this->em->flush();
            return -1;
        }

        $input = $scheduledCommand->getCommand().' '. $scheduledCommand->getArguments().' --env='.$this->environment;
        $input = new StringInput($input);
        $command->mergeApplicationDefinition();
        $input->bind($command->getDefinition());

        // Disable interactive mode if the current command has no-interaction flag
        if (true === $input->hasParameterOption(array('--no-interaction', '-n'))) {
            $input->setInteractive(false);
        }

        if (false === $this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }

        $input = $scheduledCommand->getCommand().' '. $scheduledCommand->getArguments().' --env='.$this->environment;
        $input = new StringInput($input);
        $command->mergeApplicationDefinition();
        $input->bind($command->getDefinition());

        if (true === $input->hasParameterOption(array('--no-interaction', '-n'))) {
            $input->setInteractive(false);
        }

        $cmd = sprintf('bin/console %s', $input);
        $process = new Process($cmd);
        $process->start();
        $this->processes[] = new ProcessModel($scheduledCommand, $process);
        return 1;
    }
}