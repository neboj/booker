<?php
declare(strict_types=1);

namespace App\Exceptions;


class EntityUniqueConstraintException extends \Exception
{

    /**
     * EntityUniqueConstraintException constructor.
     * @param array $array
     */
    public function __construct(array $array)
    {
        $classWithNamespace = $array['entity_name'];
        $classOnly = substr($classWithNamespace, strrpos($classWithNamespace, '\\') + 1);
        parent::__construct('' . $classOnly . ' already exists in db.');
    }
}