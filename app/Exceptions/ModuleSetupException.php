<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ModuleSetupException extends HttpException
{
    public function __construct($moduleName)
    {
        $message = $moduleName . ' Setup Not Found.';
        parent::__construct(403, $message, null, []);
    }
}
