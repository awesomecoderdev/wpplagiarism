<?php

namespace AwesomeCoder\Database\Eloquent\Casts;

use AwesomeCoder\Contracts\Database\Eloquent\Castable;
use AwesomeCoder\Contracts\Database\Eloquent\CastsAttributes;
use AwesomeCoder\Support\Collection;

class AsCollection implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return CastsAttributes<\AwesomeCoder\Support\Collection<array-key, mixed>, iterable>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (!isset($attributes[$key])) {
                    return;
                }

                $data = json_decode($attributes[$key], true);

                return is_array($data) ? new Collection($data) : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                return [$key => json_encode($value)];
            }
        };
    }
}
