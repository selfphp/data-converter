<?php

namespace Selfphp\DataConverter\Format;

use DOMDocument;
use DOMElement;
use Selfphp\DataConverter\FormatInterface;

/**
 * Converts associative arrays into XML.
 *
 * This converter supports nested arrays, optional XML declaration,
 * null-to-xsi:nil conversion, boolean normalization, and tag name validation.
 */
class ArrayToXmlConverter implements FormatInterface
{
    /** @var string Root element name of the resulting XML */
    private string $rootElement;

    /** @var bool Whether to add the XML declaration */
    private bool $addXmlDeclaration;

    /** @var bool Whether to convert null values to xsi:nil */
    private bool $convertNullToXsiNil;

    /** @var bool Whether to convert boolean values to 'true'/'false' */
    private bool $convertBoolToString;

    /**
     * Constructor.
     *
     * @param string $rootElement Root tag name
     * @param bool $addXmlDeclaration Whether to include XML declaration
     * @param bool $convertNullToXsiNil Whether to convert null to xsi:nil
     * @param bool $convertBoolToString Whether to normalize booleans as strings
     * @throws \InvalidArgumentException If root element name is invalid
     */
    public function __construct(
        string $rootElement = 'root',
        bool $addXmlDeclaration = true,
        bool $convertNullToXsiNil = false,
        bool $convertBoolToString = false
    ) {
        if (!$this->isValidTagName($rootElement)) {
            throw new \InvalidArgumentException("Invalid XML root element name: '$rootElement'");
        }

        $this->rootElement = $rootElement;
        $this->addXmlDeclaration = $addXmlDeclaration;
        $this->convertNullToXsiNil = $convertNullToXsiNil;
        $this->convertBoolToString = $convertBoolToString;
    }

    /**
     * Converts the given input array to XML.
     *
     * @param mixed $input Must be an array
     * @return string XML string
     * @throws \InvalidArgumentException
     */
    public function convert(mixed $input): string
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException("Input must be an array.");
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElement($this->rootElement);
        $doc->appendChild($root);

        $this->appendArrayToXml($input, $root, $doc);

        return $this->addXmlDeclaration
            ? $doc->saveXML()
            : $doc->saveXML($doc->documentElement);
    }

    /**
     * Recursively appends array data to the XML DOM.
     *
     * @param array $data
     * @param DOMElement $element
     * @param DOMDocument $doc
     * @return void
     */
    protected function appendArrayToXml(array $data, DOMElement $element, DOMDocument $doc): void
    {
        foreach ($data as $key => $value) {
            if (!is_numeric($key) && !$this->isValidTagName($key)) {
                throw new \InvalidArgumentException("Invalid XML element name: '$key'");
            }

            $key = is_numeric($key) ? "item{$key}" : preg_replace('/[^a-z0-9_\-]/i', '_', $key);

            if (is_array($value)) {
                $child = $doc->createElement($key);
                $element->appendChild($child);
                $this->appendArrayToXml($value, $child, $doc);
            } elseif ($value === null && $this->convertNullToXsiNil) {
                $child = $doc->createElement($key);
                $child->setAttributeNS(
                    'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:nil',
                    'true'
                );
                $doc->documentElement->setAttributeNS(
                    'http://www.w3.org/2000/xmlns/',
                    'xmlns:xsi',
                    'http://www.w3.org/2001/XMLSchema-instance'
                );
                $element->appendChild($child);
            } else {
                $text = $value;
                if (is_bool($value) && $this->convertBoolToString) {
                    $text = $value ? 'true' : 'false';
                }

                $cleaned = $this->stripInvalidXmlChars((string)$text);
                $child = $doc->createElement($key);
                $child->appendChild($doc->createTextNode((string)$cleaned));
                $element->appendChild($child);
            }
        }
    }

    /**
     * Removes invalid characters that are not allowed in XML.
     *
     * @param string $value
     * @return string
     */
    protected function stripInvalidXmlChars(string $value): string
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    }

    /**
     * Checks if a given tag name is valid.
     *
     * @param string $name
     * @return bool
     */
    protected function isValidTagName(string $name): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_\-\.]*$/', $name) === 1;
    }

    /**
     * Returns a DOMDocument for the given array input.
     *
     * @param array $input Input data
     * @return DOMDocument
     */
    public function toDom(mixed $input): \DOMDocument
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException("Input must be an array.");
        }

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElement($this->rootElement);
        $doc->appendChild($root);

        $this->appendArrayToXml($input, $root, $doc);

        return $doc;
    }

    /**
     * Static entry point to convert an array to XML in one call.
     *
     * @param array $input
     * @param string $rootElement
     * @param bool $addXmlDeclaration
     * @param bool $convertNullToXsiNil
     * @param bool $convertBoolToString
     * @return string XML output
     */
    public static function convertArray(
        array $input,
        string $rootElement = 'root',
        bool $addXmlDeclaration = true,
        bool $convertNullToXsiNil = false,
        bool $convertBoolToString = false
    ): string {
        $converter = new self(
            $rootElement,
            $addXmlDeclaration,
            $convertNullToXsiNil,
            $convertBoolToString
        );

        return $converter->convert($input);
    }

}
