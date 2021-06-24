<?php
declare(strict_types=1);

namespace App\Exceptions;


class NotExistingResource extends \Exception
{
    protected $message = 'Resource does not exist!';
}