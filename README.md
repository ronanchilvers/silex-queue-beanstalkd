# Simple queue for silex

This is a simple queue mechanism for [Silex](https://silex.symfony.com/) using [Beanstalkd](http://kr.github.io/beanstalkd/) as the backend.

## Installation

Installation using composer:

```bash
composer require ronanchilvers\silex-queue-beanstalkd
```

We're assuming that you have an accessible Beanstalkd instance running somewhere and that you know its network address.

## Configuration

To use the queue service you need to register the provider like so:

```php
$app->register(new Ronanchilvers\Silex\Queue\QueueProvider());
```

By default this assumes that Beanstalkd is running on the default port (11300) on localhost.

There are a few configuration directives that you can use to get the queue service working. You can pass these in using the normal Silex way like this:

```php
$app->register(new Ronanchilvers\Silex\Queue\QueueProvider(), [
    'queue.options' => [
        'host' => '1.2.3.4'
    ]
]);
```

The available configuration keys are:

 - host : Beanstalkd host (default localhost)
 - port : Beanstalkd port (default 11300)
 - timeout : Timeout for Beanstalkd connection (default 2 seconds)
 - persistent : Use a persistent connection to Beanstalkd or not (default false)
 - default.queue : default queue to push / pull to / from
 - max.iterations : maximum iterations a worker does before exiting
