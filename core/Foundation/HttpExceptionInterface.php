<?php

namespace AwesomeCoder\Foundation\Exception;

/**
 * Interface for HTTP error exceptions.
 *
 * @author Mohammad Ibrahim <awesomecoder.dev@gmail.com>
 */
interface HttpExceptionInterface extends \Throwable
{
    /**
     * Returns the status code.
     */
    public function getStatusCode(): int;

    /**
     * Returns response headers.
     */
    public function getHeaders(): array;
}
