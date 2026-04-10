<?php

namespace App\Exceptions;

use Exception;

class EventFullException extends Exception
{
    protected $message = 'Az esemény betelt, nincs több szabad hely.';
}
