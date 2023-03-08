<?php

namespace Illuminate\Filesystem;

class UnableToCheckFileExistence extends UnableToCheckExistence
{
    public function operation(): string
    {
        return FilesystemOperationFailed::OPERATION_FILE_EXISTS;
    }
}
