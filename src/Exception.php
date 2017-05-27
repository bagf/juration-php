<?php

namespace Juration;

use ErrorException;

class Exception extends ErrorException
{
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}
