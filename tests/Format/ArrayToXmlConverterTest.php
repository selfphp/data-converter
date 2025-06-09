<?php

declare(strict_types=1);

namespace Selfphp\DataConverter\Tests\Format;

use PHPUnit\Framework\TestCase;
use Selfphp\DataConverter\Format\ArrayToXmlConverter;

class ArrayToXmlConverterTest extends TestCase
{
    public function testSimpleArrayConversion(): void
    {
        $converter = new ArrayToXmlConverter();
        $input = [
            'name' => 'Alice',
            'age' => 30,
            'skills' => ['PHP', 'XML']
        ];

        $xml = $converter->convert($input);

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
        $this->assertStringContainsString('<name>Alice</name>', $xml);
        $this->assertStringContainsString('<age>30</age>', $xml);
        $this->assertStringContainsString('<skills>', $xml);
        $this->assertStringContainsString('<item0>PHP</item0>', $xml);
        $this->assertStringContainsString('<item1>XML</item1>', $xml);
    }

    public function testDeeplyNestedArray(): void
    {
        $converter = new ArrayToXmlConverter('root');
        $input = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'value' => 'deep'
                    ]
                ]
            ]
        ];

        $xml = $converter->convert($input);

        $this->assertStringContainsString('<level3>', $xml);
        $this->assertStringContainsString('<value>deep</value>', $xml);
    }


    public function testThrowsExceptionOnInvalidInput(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new ArrayToXmlConverter();
        $converter->convert("not-an-array");
    }

    public function testCustomRootElement(): void
    {
        $converter = new ArrayToXmlConverter('response');
        $input = ['status' => 'ok'];

        $xml = $converter->convert($input);

        $this->assertStringContainsString('<response>', $xml);
        $this->assertStringContainsString('</response>', $xml);
    }

    public function testNoXmlDeclaration(): void
    {
        $converter = new ArrayToXmlConverter('data', false);
        $input = ['value' => 42];

        $xml = $converter->convert($input);

        $this->assertStringNotContainsString('<?xml', $xml);
        $this->assertStringContainsString('<data>', $xml);
    }

    public function testNullConversionWithXsiNil(): void
    {
        $converter = new ArrayToXmlConverter('data', true, true); // convertNullToXsiNil = true
        $input = ['name' => null];

        $xml = $converter->convert($input);

        $this->assertStringContainsString('xsi:nil="true"', $xml);
        $this->assertStringContainsString('xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"', $xml);
    }

    public function testBoolConversionToString(): void
    {
        $converter = new ArrayToXmlConverter('flags', true, false, true); // convertBoolToString = true
        $input = ['active' => true, 'deleted' => false];

        $xml = $converter->convert($input);

        $this->assertStringContainsString('<active>true</active>', $xml);
        $this->assertStringContainsString('<deleted>false</deleted>', $xml);
    }

    public function testRemovesInvalidXmlCharacters(): void
    {
        $converter = new ArrayToXmlConverter();

        $input = ['data' => "Hello\x01\x02 World"];
        $xml = $converter->convert($input);

        // Ergebnis sollte das saubere XML enthalten – OHNE Steuerzeichen
        $this->assertStringContainsString('<data>Hello World</data>', $xml);
    }

    public function testRejectsInvalidRootTag(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ArrayToXmlConverter('123Invalid');
    }

    public function testRejectsInvalidKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new ArrayToXmlConverter();
        $data = ['valid' => 'yes', '🤖invalid' => 'no'];
        $converter->convert($data);
    }

    public function testToDomReturnsDomDocument(): void
    {
        $converter = new ArrayToXmlConverter('data');
        $data = ['name' => 'XML'];

        $dom = $converter->toDom($data);

        $this->assertInstanceOf(\DOMDocument::class, $dom);
        $this->assertEquals('data', $dom->documentElement->tagName);
        $this->assertEquals('XML', $dom->documentElement->getElementsByTagName('name')[0]->textContent);
    }

    public function testStaticConvertMethod(): void
    {
        $xml = ArrayToXmlConverter::convertArray(['greeting' => 'Hi'], 'message');

        $this->assertStringContainsString('<message>', $xml);
        $this->assertStringContainsString('<greeting>Hi</greeting>', $xml);
    }


}
