<?php

namespace Spatie\Sheets;

class Sheet
{
    /** @var array */
    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get(string $key)
    {
        $value = $this->attributes[$key] ?? null;

        $accessorFunctionName = 'get'.studly_case($key).'Attribute';

        if (method_exists($this, $accessorFunctionName)) {
            return $this->$accessorFunctionName($value);
        }

        return $value;
    }

    public function __isset(string $key)
    {
        return ! is_null($this->$key);
    }
}
