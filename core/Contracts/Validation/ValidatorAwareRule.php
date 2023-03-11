<?php

namespace AwesomeCoder\Contracts\Validation;

interface ValidatorAwareRule
{
    /**
     * Set the current validator.
     *
     * @param  \AwesomeCoder\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator);
}
