<?php

namespace Ilyaotinov\CLI\Exception;

class WrongType extends \RuntimeException
{
    public function __construct(
        string $message = "Wrong type",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
