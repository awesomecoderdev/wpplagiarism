<?php

namespace AwesomeCoder\Database\Eloquent;

interface Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \AwesomeCoder\Database\Eloquent\Builder  $builder
     * @param  \AwesomeCoder\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model);
}
