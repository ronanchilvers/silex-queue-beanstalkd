<?php

namespace Ronanchilvers\Silex\Queue;

use Ronanchilvers\Silex\Queue\Consumer;
use Ronanchilvers\Silex\Queue\Publisher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Queue service provider
 *
 * queue.options keys are:
 *   host : Beanstalkd host (default localhost)
 *   port : Beanstalkd port (default 11300)
 *   timeout : Timeout for Beanstalkd connection (default 2 seconds)
 *   persistent : Use a persistent connection to Beanstalkd or not (default false)
 *   default.queue : default queue to push / pull to / from
 *   max.iterations : maximum iterations a worker does before exiting
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class QueueProvider implements ServiceProviderInterface
{
    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function register(Container $pimple)
    {
        $pimple['queue.options'] = [];
        $pimple['queue.publisher'] = function ($c) {
            return new Publisher(
                $c['queue.options']
            );
        };
        $pimple['queue.consumer'] = function ($c) {
            return new Consumer(
                $c,
                $c['queue.options']
            );
        };
    }
}
