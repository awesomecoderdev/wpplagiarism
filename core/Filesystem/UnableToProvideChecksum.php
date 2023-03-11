<?php

namespace AwesomeCoder\Filesystem;

use RuntimeException;
use Throwable;

final class UnableToProvideChecksum extends RuntimeException implements FilesystemException
{
    public function __construct(string $reason, string $path, ?Throwable $previous = null)
    {
        parent::__construct("Unable to get checksum for $path: $reason", 0, $previous);
    }
}
