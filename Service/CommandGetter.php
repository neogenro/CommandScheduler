<?php

/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 05.01.2018
 * Time: 12:21
 */

namespace Neogen\CommandSchedulerBundle\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandGetter
{
    /** @var KernelInterface */
    private $kernel;
    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            array(
                'command' => 'list',
                '--format' => 'xml'
            )
        );

        $output = new StreamOutput(fopen('php://memory', 'w+'));
        $application->run($input, $output);
        rewind($output->getStream());

        return $this->extractCommandsFromXML(stream_get_contents($output->getStream()));
    }

    /**
     * Extract an array of available Symfony command from the XML output
     *
     * @param $xml
     * @return array
     */
    private function extractCommandsFromXML($xml)
    {
        if ($xml == '') {
            return array();
        }

        $node = new \SimpleXMLElement($xml);
        $commandsList = array();

        foreach ($node->namespaces->namespace as $namespace) {
            $namespaceId = (string)$namespace->attributes()->id;

            //if (!in_array($namespaceId, $this->excludedNamespaces)) {
                foreach ($namespace->command as $command) {
                    $commandsList[$namespaceId][(string)$command] = (string)$command;
                }
            //}
        }

        return $commandsList;
    }
}