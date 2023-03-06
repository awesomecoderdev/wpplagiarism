<?php

namespace AwesomeCoder\Support\Traits;

use AwesomeCoder\Container\Container;

trait Localizable
{
    /**
     * Run the callback with the given locale.
     *
     * @param  string  $locale
     * @param  \Closure  $callback
     * @return mixed
     */
    public function withLocale($locale, $callback)
    {
        if (!$locale) {
            return $callback();
        }

        $plugin = Container::getInstance();

        $original = $plugin->getLocale();

        try {
            $plugin->setLocale($locale);

            return $callback();
        } finally {
            $plugin->setLocale($original);
        }
    }
}
