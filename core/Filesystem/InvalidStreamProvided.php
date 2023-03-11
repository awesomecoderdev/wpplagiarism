<?php

namespace AwesomeCoder\Filesystem;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidStreamProvided extends BaseInvalidArgumentException implements FilesystemException
{
}
