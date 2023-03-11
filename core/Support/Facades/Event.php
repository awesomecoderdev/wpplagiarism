<?php

namespace AwesomeCoder\Support\Facades;

use AwesomeCoder\Database\Eloquent\Model;
use AwesomeCoder\Support\Testing\Fakes\EventFake;

/**
 * @method static void listen(\Closure|string|array $events, \Closure|string|array|null $listener = null)
 * @method static bool hasListeners(string $eventName)
 * @method static bool hasWildcardListeners(string $eventName)
 * @method static void push(string $event, object|array $payload = [])
 * @method static void flush(string $event)
 * @method static void subscribe(object|string $subscriber)
 * @method static array|null until(string|object $event, mixed $payload = [])
 * @method static array|null dispatch(string|object $event, mixed $payload = [], bool $halt = false)
 * @method static array getListeners(string $eventName)
 * @method static \Closure makeListener(\Closure|string|array $listener, bool $wildcard = false)
 * @method static \Closure createClassListener(string $listener, bool $wildcard = false)
 * @method static void forget(string $event)
 * @method static void forgetPushed()
 * @method static \AwesomeCoder\Events\Dispatcher setQueueResolver(callable $resolver)
 * @method static array getRawListeners()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static \AwesomeCoder\Support\Testing\Fakes\EventFake except(array|string $eventsToDispatch)
 * @method static void assertListening(string $expectedEvent, string|array $expectedListener)
 * @method static void assertDispatched(string|\Closure $event, callable|int|null $callback = null)
 * @method static void assertDispatchedTimes(string $event, int $times = 1)
 * @method static void assertNotDispatched(string|\Closure $event, callable|null $callback = null)
 * @method static void assertNothingDispatched()
 * @method static \AwesomeCoder\Support\Collection dispatched(string $event, callable|null $callback = null)
 * @method static bool hasDispatched(string $event)
 *
 * @see \AwesomeCoder\Events\Dispatcher
 * @see \AwesomeCoder\Support\Testing\Fakes\EventFake
 */
class Event extends Facade
{



    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'events';
    }
}
