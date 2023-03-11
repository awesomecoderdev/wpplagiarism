<?php

namespace AwesomeCoder\Database\Eloquent\Concerns;

use AwesomeCoder\Database\Eloquent\ModelNotFoundException;
use AwesomeCoder\Support\Str;

trait HasUuids
{
    /**
     * Generate a primary UUID for the model.
     *
     * @return void
     */
    public static function bootHasUuids()
    {
        static::creating(function (self $model) {
            foreach ($model->uniqueIds() as $column) {
                if (empty($model->{$column})) {
                    $model->{$column} = $model->newUniqueId();
                }
            }
        });
    }

    /**
     * Generate a new UUID for the model.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return (string) Str::orderedUuid();
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return [$this->getKeyName()];
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  \AwesomeCoder\Database\Eloquent\Model|\AwesomeCoder\Database\Eloquent\Relations\Relation  $query
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \AwesomeCoder\Database\Eloquent\Relations\Relation
     *
     * @throws \AwesomeCoder\Database\Eloquent\ModelNotFoundException
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field && in_array($field, $this->uniqueIds()) && !Str::isUuid($value)) {
            throw (new ModelNotFoundException)->setModel(get_class($this), $value);
        }

        if (!$field && in_array($this->getRouteKeyName(), $this->uniqueIds()) && !Str::isUuid($value)) {
            throw (new ModelNotFoundException)->setModel(get_class($this), $value);
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return 'string';
        }

        return $this->keyType;
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return false;
        }

        return $this->incrementing;
    }
}
