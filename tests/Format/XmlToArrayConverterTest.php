<?php

namespace Selfphp\DataConverter\Tests\Format;

use PHPUnit\Framework\TestCase;
use Selfphp\DataConverter\Format\XmlToArrayConverter;

class XmlToArrayConverterTest extends TestCase
{
    public function testSimpleXmlToArray()
    {
        $xml = <<<XML
<root>
    <name>John</name>
    <age>30</age>
</root>
XML;

        $converter = new XmlToArrayConverter();
        $array = $converter->convert($xml);

        $this->assertSame([
            'name' => 'John',
            'age' => '30',
        ], $array);
    }

    public function testDeeplyNestedXmlToArray()
    {
        $xml = <<<XML
<root>
    <user>
        <name>Anna</name>
        <email>anna@example.com</email>
    </user>
</root>
XML;

        $converter = new XmlToArrayConverter();
        $array = $converter->convert($xml);

        $this->assertSame([
            'user' => [
                'name' => 'Anna',
                'email' => 'anna@example.com'
            ]
        ], $array);
    }

    public function testMultipleSameTagsToArray()
    {
        $xml = <<<XML
<root>
    <tag>one</tag>
    <tag>two</tag>
    <tag>three</tag>
</root>
XML;

        $converter = new XmlToArrayConverter();
        $array = $converter->convert($xml);

        $this->assertSame([
            'tag' => ['one', 'two', 'three']
        ], $array);
    }

    public function testXmlWithAttributes()
    {
        $xml = <<<XML
<user id="42" active="true">Anna</user>
XML;

        $converter = new XmlToArrayConverter();
        $array = $converter->convert($xml);

        $this->assertSame([
            '@id' => '42',
            '@active' => 'true',
            '#text' => 'Anna'
        ], $array);
    }

    public function testThrowsExceptionOnInvalidXml()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid XML input.');

        $invalidXml = '<root><unclosed></root>';

        $converter = new XmlToArrayConverter();
        $converter->convert($invalidXml);
    }


}
