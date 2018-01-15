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
 - timeout : Timeout for Beanstalkd connection (default 2 seconds). This option is passed directly through to Pheanstalk.
 - persistent : Use a persistent connection to Beanstalkd or not (default false). This option is passed directly through to Pheanstalk.
 - default.queue : default queue to push / pull to / from. The queue can be overridden at publish / consume time.
 - max.iterations : maximum iterations a worker does when using the queue:consume CLI command before exiting

 ## Consume Command

 A simple consume command for use with ```symfony/console``` is provided. The command requires the [```knplabs/console-service-provider```](https://github.com/KnpLabs/ConsoleServiceProvider) package as it needs access to the Application object. To use it you can do something like this:

 ```php
 $console->add(new Ronanchilvers\Silex\Queue\Console\Command\ConsumeCommand());
 ```

which will add a queue:consume command to the console application.
