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

        if (method_exists($this, 'get'.studly_case($key).'Attribute')) {
            return $this->{'get'.studly_case($key).'Attribute'}($value);
        }

        return $value;
    }

    public function __isset(string $key)
    {
        return ! is_null($this->$key);
    }
}
