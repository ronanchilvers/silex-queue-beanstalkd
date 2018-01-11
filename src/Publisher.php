<?php

namespace Ronanchilvers\Silex\Queue;

use Ronanchilvers\Silex\Queue\Connection;
use Ronanchilvers\Silex\Queue\Message;
use Pheanstalk\Pheanstalk;

/**
 * Publisher service allowing pushing messages onto the queue
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Publisher extends Connection
{
    /**
     * Send a message to the queue
     *
     * This is a convenience method that wraps up the arguments into a
     * App\Queue\Message object and sends it.
     *
     * @param string $service
     * @param string $method
     * @param array $args
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function sendMessage($service, $method, $args = [], $queue = null)
    {
        $message = new Message(
            $service,
            $method,
            $args
        );

        return $this->send($message, $queue);
    }

    /**
     * Send a message to the queue
     *
     * @param App\Queue\Message $message
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function send(Message $message, $queue = null)
    {
        if (is_null($queue)) {
            $queue = $this->getOption('default.queue', 'messages');
        }
        $message = serialize($message);
        return $this->connection()
            ->useTube($queue)
            ->put($message);
    }
}
