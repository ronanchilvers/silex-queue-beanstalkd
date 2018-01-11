<?php

namespace Ronanchilvers\Silex\Queue;

use Ronanchilvers\Silex\Queue\Connection;
use Ronanchilvers\Silex\Queue\Message;
use Pheanstalk\Pheanstalk;
use Pimple\Container;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Consumer service allowing pulling messages from the queue
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Consumer extends Connection
{
    /**
     * @var Pimple\Container
     */
    protected $container;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(Container $container, array $options)
    {
        parent::__construct($options);
        $this->container = $container;
    }

    /**
     * Listen for messages
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @param string $queue
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function listen(OutputInterface $output = null, $queue = null)
    {
        if (is_null($queue)) {
            $queue = $this->getOption('default.queue', 'messages');
        }
        if (is_null($output)) {
            $output = new NullOutput();
        }
        $maxIterations = $this->getOption('max.iterations', 1000);
        $output->writeln(
            sprintf(
                'Configuration : queue %s, max iterations %d',
                $queue,
                $maxIterations
            )
        );
        $output->writeln('Starting queue watch');
        $this->connection()->watch($queue);
        $iterations = 0;
        $output->writeln('Waiting for jobs');
        while ($job = $this->connection()->reserve()) {
            $output->writeln('Dispatching job id ' . $job->getId());
            $message = unserialize($job->getData());
            if (!$message instanceof Message) {
                $output->writeln('Invalid message for job ' . $job->getId());
                $this->connection()->delete($job);
                $iterations++;
                continue;
            }
            $output->writeln(
                sprintf(
                    'Job : %d, service \'%s\', method \'%s\', args \'%s\'',
                    $job->getId(),
                    $message->getService(),
                    $message->getMethod(),
                    json_encode($message->getArgs())
                )
            );
            $status = $this->dispatch(
                $message,
                $output
            );
            if (false !== $status) {
                $msg = sprintf('Dispatched job %d ok', $job->getId());
                $this->connection()->delete($job);
            } else {
                $msg = sprintf('Dispatched job %d failed', $job->getId());
                $this->connection()->delete($job);
            }
            $output->writeln($msg);
            $iterations++;
            if ($iterations >= $maxIterations) {
                $output->writeln(sprintf('Breaking after %d iterations', $iterations));
                break;
            }
        }
        $output->writeln('Queue watch finished for ' . $queue);

        return true;
    }

    /**
     * Dispatch a message from the queue
     *
     * @param App\Queue\Message $message
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return boolean
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function dispatch(Message $message, OutputInterface $output)
    {
        if (!isset($this->container[$message->getService()])) {
            $output->writeln(
                sprintf('Service \'%s\' does not exist', $message->getService())
            );
            return false;
        }
        $service = $this->container[$message->getService()];
        $method = $message->getMethod();
        $result = call_user_func_array(
            [
                $service,
                $method
            ],
            $message->getArgs()
        );

        return $result;
    }
}
