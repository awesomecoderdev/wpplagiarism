<?php

namespace AwesomeCoder\Filesystem;

interface PathNormalizer
{
    public function normalizePath(string $path): string;
}
