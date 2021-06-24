<?php
declare(strict_types=1);

namespace App\Exceptions;


class UnauthorizedAccessException extends \Exception
{
    protected $message = "Unauthorized Access!";

}