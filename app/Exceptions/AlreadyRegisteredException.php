<?php

namespace App\Exceptions;

use Exception;

class AlreadyRegisteredException extends Exception
{
    protected $message = 'Már jelentkeztél erre az eseményre.';
}
