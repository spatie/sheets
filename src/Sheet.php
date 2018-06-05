<?php

namespace Spatie\Sheets;

use ArrayAccess;

class Sheet implements ArrayAccess
{
    /** @var array */
    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __isset(string $key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset(string $key)
    {
        unset($this->attributes[$key]);
    }

    public function offsetExists($key)
    {
        return !is_null($this->getAttribute($key));
    }

    public function offsetGet($key)
    {
        return $this->getAttribute($key);
    }

    public function offsetSet($key, $value)
    {
        $this->$key = $value;
    }

    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    protected function getAttribute(string $key)
    {
        $value = $this->attributes[$key] ?? null;

        $accessorFunctionName = 'get'.studly_case($key).'Attribute';

        if (method_exists($this, $accessorFunctionName)) {
            return $this->$accessorFunctionName($value);
        }

        return $value;
    }
}
