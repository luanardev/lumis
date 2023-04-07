<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ModuleAccessException extends HttpException
{
    public function __construct()
    {
        $message = 'User does not have access.';
        parent::__construct(403, $message, null, []);
    }
}
