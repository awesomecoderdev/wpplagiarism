<?php

namespace AwesomeCoder\Http\Resources;

interface PotentiallyMissing
{
    /**
     * Determine if the object should be considered "missing".
     *
     * @return bool
     */
    public function isMissing();
}
