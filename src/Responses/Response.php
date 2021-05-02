<?php

namespace Olekjs\Allegro\Responses;

use JsonSerializable;

class Response implements JsonSerializable
{
    /**
     * Response attributes.
     *
     * @var  array
     */
    protected $attributes = [];

    /**
     * Response constructor.
     *
     * @param  array|null  $attributes
     */
    public function __construct(array $attributes = null)
    {
        $this->fill($attributes);
    }

    /**
     * Pass the attributes from the response to the object.
     *
     * @param  array  $attributes
     *
     * @return array
     */
    protected function mutateAttributesToArray(array $attributes): array
    {
        foreach ($attributes as $key => $value) {
            $attributes[$key] = $value;
        }

        return $attributes;
    }

    /**
     * Fill the response values ​​to the object.
     *
     * @param  array|null  $attributes
     *
     * @return Olekjs\Allegro\Responses\Response
     */
    public function fill(array $attributes = null): Response
    {
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    /**
     * Set response attribute to object.
     *
     * @param  miexd  $key
     * @param  miexd  $value
     *
     * @return Olekjs\Allegro\Responses\Response
     */
    public function setAttribute($key, $value): Response
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get response attribute from object.
     *
     * @param  miexd  $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes) || $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }
    }

    /**
     * Get response attribute value from object.
     *
     * @param  miexd  $key
     *
     * @return mixed
     */
    protected function getAttributeValue($key)
    {
        return $this->getAttributeFromArray($key);
    }

    /**
     * Get response attribute value from array.
     *
     * @param  miexd  $key
     *
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Create a static object from response.
     *
     * @param  array|null  $attributes
     *
     * @return Olekjs\Allegro\Responses\Response
     */
    public static function create(array $attributes = null): Response
    {
        return new static($attributes);
    }

    /**
     * Parse response object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $attributes = $this->mutateAttributesToArray($this->attributes);

        return $attributes;
    }

    /**
     * Serialize object to json.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Return response object as json.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Get attribute from response object.
     *
     * @param  mixed  $key
     *
     * @return miexd
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Set attribute to response object.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Check if attribute exists.
     *
     * @param  mixed  $offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Unset response object attribute.
     *
     * @param  mixed  $offset
     *
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Check if attribute exists.
     *
     * @param  mixed  $key
     *
     * @return boolean
     */
    public function __isset($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset response object attribute.
     *
     * @param  mixed  $key
     *
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }
}
