<?php

namespace Illuminate\Filesystem;

interface PathNormalizer
{
    public function normalizePath(string $path): string;
}
