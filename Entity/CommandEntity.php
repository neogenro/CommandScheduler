<?php

namespace Neogen\CommandSchedulerBundle\Entity;

/**
 * CommandEntity
 */
class CommandEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $command;

    /**
     * @var string
     */
    private $arguments = null;

    /**
     * @var string
     */
    private $cronExpression;

    /**
     * @var bool
     */
    private $priority;

    /**
     * @var \DateTime
     */
    private $lastExecution = null;

    /**
     * @var int
     */
    private $lastReturnCode = null;

    /**
     * @var int
     */
    private $lastRunDuration = null;

    /**
     * @var string
     */
    private $lastLog = null;

    /**
     * @var bool
     */
    private $executeNow = null;

    /**
     * @var bool
     */
    private $disabled = null;

    /**
     * @var bool
     */
    private $locked = null;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CommandEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set command
     *
     * @param string $command
     *
     * @return CommandEntity
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set arguments
     *
     * @param string $arguments
     *
     * @return CommandEntity
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get arguments
     *
     * @return string
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set cronExpression
     *
     * @param string $cronExpression
     *
     * @return CommandEntity
     */
    public function setCronExpression($cronExpression)
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }

    /**
     * Get cronExpression
     *
     * @return string
     */
    public function getCronExpression()
    {
        return $this->cronExpression;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     *
     * @return CommandEntity
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return bool
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set lastExecution
     *
     * @param \DateTime $lastExecution
     *
     * @return CommandEntity
     */
    public function setLastExecution($lastExecution)
    {
        $this->lastExecution = $lastExecution;

        return $this;
    }

    /**
     * Get lastExecution
     *
     * @return \DateTime
     */
    public function getLastExecution()
    {
        return $this->lastExecution;
    }

    /**
     * Set lastReturnCode
     *
     * @param integer $lastReturnCode
     *
     * @return CommandEntity
     */
    public function setLastReturnCode($lastReturnCode)
    {
        $this->lastReturnCode = $lastReturnCode;

        return $this;
    }

    /**
     * Get lastReturnCode
     *
     * @return int
     */
    public function getLastReturnCode()
    {
        return $this->lastReturnCode;
    }

    /**
     * Set lastRunDuration
     *
     * @param integer $lastRunDuration
     *
     * @return CommandEntity
     */
    public function setLastRunDuration($lastRunDuration)
    {
        $this->lastRunDuration = $lastRunDuration;

        return $this;
    }

    /**
     * Get lastRunDuration
     *
     * @return int
     */
    public function getLastRunDuration()
    {
        return $this->lastRunDuration;
    }

    /**
     * Set lastLog
     *
     * @param string $lastLog
     *
     * @return CommandEntity
     */
    public function setLastLog($lastLog)
    {
        $this->lastLog = $lastLog;

        return $this;
    }

    /**
     * Get lastLog
     *
     * @return string
     */
    public function getLastLog()
    {
        return $this->lastLog;
    }

    /**
     * @return bool
     */
    public function isExecuteNow()
    {
        return $this->executeNow;
    }

    /**
     * @param bool $executeNow
     * @return CommandEntity
     */
    public function setExecuteNow($executeNow)
    {
        $this->executeNow = $executeNow;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return CommandEntity
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     * @return CommandEntity
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }
}

