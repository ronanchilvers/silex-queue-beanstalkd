<?php

namespace Ronanchilvers\Silex\Queue\Console\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to run a queue consumer
 *
 * This command expects to be run via a CLI process of some sort
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class ConsumeCommand extends Command
{
    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function configure()
    {
        $this
            ->setName('queue:consume')
            ->addOption(
                'queue',
                null,
                InputOption::VALUE_REQUIRED,
                'A queue to consume from'
            )
            ;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queue = $input->getOption('queue');
        if (!is_null($queue)) {
            $output->writeln('Consuming queue ' . $queue);
        } else {
            $output->writeln('Consuming default queue');
        }
        $app = $this->getSilexApplication();
        $consumer = $app['queue.consumer'];
        $consumer->listen($output, $queue);
    }

}
