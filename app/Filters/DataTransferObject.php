<?php

namespace App\Filters;

use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class DataTransferObject
 *
 * @package App\Filters
 */
abstract class DataTransferObject
{
    /**
     * @param Request $request
     * @return DataTransferObject
     */
    public static function fromRequest(Request $request): DataTransferObject
    {
        $object = new static();
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $object->{$property} = $request->input($property);
        }

        return $object;
    }
}
