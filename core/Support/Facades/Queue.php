<?php

namespace AwesomeCoder\Support\Facades;

use AwesomeCoder\Queue\Worker;
use AwesomeCoder\Support\Testing\Fakes\QueueFake;

/**
 * @method static void before(mixed $callback)
 * @method static void after(mixed $callback)
 * @method static void exceptionOccurred(mixed $callback)
 * @method static void looping(mixed $callback)
 * @method static void failing(mixed $callback)
 * @method static void stopping(mixed $callback)
 * @method static bool connected(string|null $name = null)
 * @method static \AwesomeCoder\Contracts\Queue\Queue connection(string|null $name = null)
 * @method static void extend(string $driver, \Closure $resolver)
 * @method static void addConnector(string $driver, \Closure $resolver)
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static string getName(string|null $connection = null)
 * @method static \AwesomeCoder\Contracts\Foundation\Application getApplication()
 * @method static \AwesomeCoder\Queue\QueueManager setApplication(\AwesomeCoder\Contracts\Foundation\Application $plugin)
 * @method static int size(string|null $queue = null)
 * @method static mixed push(string|object $job, mixed $data = '', string|null $queue = null)
 * @method static mixed pushOn(string $queue, string|object $job, mixed $data = '')
 * @method static mixed pushRaw(string $payload, string|null $queue = null, array $options = [])
 * @method static mixed later(\DateTimeInterface|\DateInterval|int $delay, string|object $job, mixed $data = '', string|null $queue = null)
 * @method static mixed laterOn(string $queue, \DateTimeInterface|\DateInterval|int $delay, string|object $job, mixed $data = '')
 * @method static mixed bulk(array $jobs, mixed $data = '', string|null $queue = null)
 * @method static \AwesomeCoder\Contracts\Queue\Job|null pop(string|null $queue = null)
 * @method static string getConnectionName()
 * @method static \AwesomeCoder\Contracts\Queue\Queue setConnectionName(string $name)
 * @method static mixed getJobBackoff(mixed $job)
 * @method static mixed getJobExpiration(mixed $job)
 * @method static void createPayloadUsing(callable|null $callback)
 * @method static \AwesomeCoder\Container\Container getContainer()
 * @method static void setContainer(\AwesomeCoder\Container\Container $container)
 * @method static \AwesomeCoder\Support\Testing\Fakes\QueueFake except(array|string $jobsToBeQueued)
 * @method static void assertPushed(string|\Closure $job, callable|int|null $callback = null)
 * @method static void assertPushedOn(string $queue, string|\Closure $job, callable|null $callback = null)
 * @method static void assertPushedWithChain(string $job, array $expectedChain = [], callable|null $callback = null)
 * @method static void assertPushedWithoutChain(string $job, callable|null $callback = null)
 * @method static void assertNotPushed(string|\Closure $job, callable|null $callback = null)
 * @method static void assertNothingPushed()
 * @method static \AwesomeCoder\Support\Collection pushed(string $job, callable|null $callback = null)
 * @method static bool hasPushed(string $job)
 * @method static bool shouldFakeJob(object $job)
 * @method static array pushedJobs()
 *
 * @see \AwesomeCoder\Queue\QueueManager
 * @see \AwesomeCoder\Queue\Queue
 * @see \AwesomeCoder\Support\Testing\Fakes\QueueFake
 */
class Queue extends Facade
{
    /**
     * Register a callback to be executed to pick jobs.
     *
     * @param  string  $workerName
     * @param  callable  $callback
     * @return void
     */
    public static function popUsing($workerName, $callback)
    {
        return Worker::popUsing($workerName, $callback);
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @param  array|string  $jobsToFake
     * @return \AwesomeCoder\Support\Testing\Fakes\QueueFake
     */
    public static function fake($jobsToFake = [])
    {
        static::swap($fake = new QueueFake(static::getFacadeApplication(), $jobsToFake, static::getFacadeRoot()));

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'queue';
    }
}
