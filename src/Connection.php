<?php

namespace Ronanchilvers\Silex\Queue;

use Pheanstalk\Pheanstalk;

/**
 * Base class for beanstalk queue handlers
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
abstract class Connection
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var Pheanstalk\Pheanstalk
     */
    private $connection;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Get the connection object
     *
     * @return Pheanstalk\Pheanstalk
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function connection()
    {
        if (!$this->connection instanceof Pheanstalk) {
            $this->connection = new Pheanstalk(
                $this->getOption('host', 'localhost'),
                $this->getOption('port', '11300'),
                $this->getOption('timeout', null),
                $this->getOption('persistent', false)
            );
        }

        return $this->connection;
    }

    /**
     * Get an option
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function getOption($key, $default = null)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $default;
    }
}
