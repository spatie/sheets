<?php

namespace Spatie\Sheets;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Str;
use JsonSerializable;

class Sheet implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    /** @var array */
    protected $attributes;

    /** @var string|null */
    protected $path;

    public function __construct(array $attributes = [], ?string $path = null)
    {
        $this->attributes = $attributes;
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
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

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function offsetExists($key): bool
    {
        return ! is_null($this->getAttribute($key));
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->getAttribute($key);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        $this->$key = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    public function toArray(): array
    {
        $keys = array_keys($this->attributes);

        return array_map(function (string $key) {
            return $this->getAttribute($key);
        }, array_combine($keys, $keys));
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodingException(
                'Error encoding sheet ['.get_class($this).'] to JSON: '.$message
            );
        }

        return $json;
    }

    protected function getAttribute(string $key)
    {
        $value = $this->attributes[$key] ?? null;

        $accessorFunctionName = 'get'.Str::studly($key).'Attribute';

        if (method_exists($this, $accessorFunctionName)) {
            return $this->$accessorFunctionName($value);
        }

        return $value;
    }
}
