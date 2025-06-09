<?php

namespace Selfphp\DataConverter\Format;

class XmlToArrayConverter
{
    public function convert(string $xml): array
    {
        $element = @simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($element === false) {
            throw new \InvalidArgumentException("Invalid XML input.");
        }

        return $this->convertElement($element);
    }

    private function convertElement(\SimpleXMLElement $element): array|string
    {
        $result = [];

        // 1. Attribute verarbeiten
        foreach ($element->attributes() as $attrName => $attrValue) {
            $result["@{$attrName}"] = (string) $attrValue;
        }

        // 2. Kind-Elemente verarbeiten
        foreach ($element->children() as $key => $child) {
            $childValue = $this->convertElement($child);
            if (isset($result[$key])) {
                if (!is_array($result[$key]) || !isset($result[$key][0])) {
                    $result[$key] = [$result[$key]];
                }
                $result[$key][] = $childValue;
            } else {
                $result[$key] = $childValue;
            }
        }

        // 3. Textinhalt (wenn keine Kind-Elemente vorhanden)
        if (count($element->children()) === 0 && trim((string)$element) !== '') {
            if (!empty($result)) {
                $result['#text'] = (string) $element;
            } else {
                return (string) $element;
            }
        }

        return $result;
    }



}
