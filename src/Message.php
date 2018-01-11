<?php

namespace Ronanchilvers\Silex\Queue;

use Serializable;

/**
 * Message class for queuing background tasks
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Message implements Serializable
{
    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $args;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct($service = null, $method = null, array $args = null)
    {
        $this->service = $service;
        $this->method = $method;
        $this->args = $args;
    }

    /**
     * Get the service handler for this message
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set the service handler for this message
     *
     * @param string $value
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setService($value)
    {
        $this->service = $value;
    }

    /**
     * Get the method for this message
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the method for this message
     *
     * @param string $value
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setMethod($value)
    {
        $this->method = $value;
    }

    /**
     * Get the arguments for this message
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Set the arguments for this message
     *
     * @param array $value
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setArgs($value)
    {
        $this->args = $value;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function serialize()
    {
        return serialize([
            'service' => $this->service,
            'method' => $this->method,
            'args' => $this->args,
        ]);
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->service = $data['service'];
        $this->method = $data['method'];
        $this->args = $data['args'];
    }
}
