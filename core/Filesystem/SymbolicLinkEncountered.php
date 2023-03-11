<?php

namespace AwesomeCoder\Filesystem;

use RuntimeException;

final class SymbolicLinkEncountered extends RuntimeException implements FilesystemException
{
    private string $location;

    public function location(): string
    {
        return $this->location;
    }

    public static function atLocation(string $pathName): SymbolicLinkEncountered
    {
        $e = new static("Unsupported symbolic link encountered at location $pathName");
        $e->location = $pathName;

        return $e;
    }
}
