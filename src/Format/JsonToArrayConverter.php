<?php

namespace Selfphp\DataConverter\Format;

class JsonToArrayConverter
{
    public function convert(string $json): array
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Expected JSON to decode to an array.');
        }

        return $data;
    }

}
