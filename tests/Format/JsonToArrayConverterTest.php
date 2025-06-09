<?php

namespace Selfphp\DataConverter\Tests\Format;

use PHPUnit\Framework\TestCase;
use Selfphp\DataConverter\Format\JsonToArrayConverter;

class JsonToArrayConverterTest extends TestCase
{
    public function testSimpleJsonToArray()
    {
        $json = '{"name":"Alice","active":true}';

        $converter = new JsonToArrayConverter();
        $array = $converter->convert($json);

        $this->assertSame([
            'name' => 'Alice',
            'active' => true,
        ], $array);
    }

    public function testThrowsExceptionOnInvalidJson()
    {
        $this->expectException(\JsonException::class);

        $invalidJson = '{"name": "Alice", '; // fehlt schließende Klammer

        $converter = new JsonToArrayConverter();
        $converter->convert($invalidJson);
    }

    public function testThrowsExceptionIfNotDecodedToArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected JSON to decode to an array.');

        $json = '"just a string"'; // gültiges JSON, aber kein Array

        $converter = new JsonToArrayConverter();
        $converter->convert($json);
    }


}
