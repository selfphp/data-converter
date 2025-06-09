<?php

namespace Selfphp\DataConverter\Format;

class ArrayToJsonConverter
{
    private int $flags;

    public function __construct(int $flags = JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
    {
        $this->flags = $flags;
    }

    public function withFlags(int $flags): self
    {
        $clone = clone $this;
        $clone->flags = JSON_THROW_ON_ERROR | $flags;
        return $clone;
    }

    public function convert(array $data): string
    {
        return json_encode($data, $this->flags);
    }
}


