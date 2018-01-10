<?php

/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 10.01.2018
 * Time: 12:09
 */

namespace Neogen\CommandSchedulerBundle\Model;

use Neogen\CommandSchedulerBundle\Entity\CommandEntity;
use Symfony\Component\Process\Process;

class ProcessModel
{
    /** @var  CommandEntity */
    private $command;
    /** @var  Process */
    private $process;
    /** @var  \DateTime */
    private $startTime;

    /**
     * ProcessModel constructor.
     * @param CommandEntity $command
     * @param Process $process
     */
    public function __construct(CommandEntity $command, Process $process)
    {
        $this->command = $command;
        $this->process = $process;
        $this->startTime = new \DateTime();
    }


    /**
     * @return CommandEntity
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param CommandEntity $command
     * @return ProcessModel
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param Process $process
     * @return ProcessModel
     */
    public function setProcess($process)
    {
        $this->process = $process;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     * @return ProcessModel
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }
}