<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BlockedAccountException extends HttpException
{
    public function __construct()
    {
        $message = 'User Account is Blocked.';
        parent::__construct(403, $message, null, []);
    }
}
