<?php

namespace AwesomeCoder\Foundation\Validation;

use AwesomeCoder\Contracts\Validation\Factory;
use AwesomeCoder\Foundation\Precognition;
use AwesomeCoder\Http\Request;
use AwesomeCoder\Validation\ValidationException;

trait ValidatesRequests
{
    /**
     * Run the validation routine against the given validator.
     *
     * @param  \AwesomeCoder\Contracts\Validation\Validator|array  $validator
     * @param  \AwesomeCoder\Http\Request|null  $request
     * @return array
     *
     * @throws \AwesomeCoder\Validation\ValidationException
     */
    public function validateWith($validator, Request $request = null)
    {
        $request = $request ?: request();

        if (is_array($validator)) {
            $validator = $this->getValidationFactory()->make($request->all(), $validator);
        }

        if ($request->isPrecognitive()) {
            $validator->after(Precognition::afterValidationHook($request))
                ->setRules(
                    $request->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
                );
        }

        return $validator->validate();
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws \AwesomeCoder\Validation\ValidationException
     */
    public function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        $validator = $this->getValidationFactory()->make(
            $request->all(),
            $rules,
            $messages,
            $customAttributes
        );

        if ($request->isPrecognitive()) {
            $validator->after(Precognition::afterValidationHook($request))
                ->setRules(
                    $request->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
                );
        }

        return $validator->validate();
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  string  $errorBag
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws \AwesomeCoder\Validation\ValidationException
     */
    public function validateWithBag(
        $errorBag,
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        try {
            return $this->validate($request, $rules, $messages, $customAttributes);
        } catch (ValidationException $e) {
            $e->errorBag = $errorBag;

            throw $e;
        }
    }

    /**
     * Get a validation factory instance.
     *
     * @return \AwesomeCoder\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return plugin(Factory::class);
    }
}
