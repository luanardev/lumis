<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DisabledModuleException extends HttpException
{
    public function __construct()
    {
        $message = 'Application is Disabled';
        parent::__construct(403, $message, null, []);
    }
}
