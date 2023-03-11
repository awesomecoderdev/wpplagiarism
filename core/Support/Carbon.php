<?php

namespace AwesomeCoder\Support;

use Carbon\Carbon as BaseCarbon;
use Carbon\CarbonImmutable as BaseCarbonImmutable;
use AwesomeCoder\Support\Traits\Conditionable;

class Carbon extends BaseCarbon
{
    use Conditionable;

    /**
     * {@inheritdoc}
     */
    public static function setTestNow($testNow = null)
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
}
