<?php

namespace Selfphp\DataConverter\Tests\Format;

use PHPUnit\Framework\TestCase;
use Selfphp\DataConverter\Format\ArrayToJsonConverter;

class ArrayToJsonConverterTest extends TestCase
{
    public function testSimpleArrayToJson()
    {
        $data = [
            'name' => 'Alice',
            'active' => true
        ];

        $converter = new ArrayToJsonConverter();
        $json = $converter->convert($data);

        $this->assertJsonStringEqualsJsonString(
            json_encode($data, JSON_PRETTY_PRINT),
            $json
        );
    }

    public function testThrowsExceptionOnInvalidData()
    {
        $this->expectException(\JsonException::class);

        $data = ['invalid' => fopen('php://temp', 'r')];

        $converter = new ArrayToJsonConverter();
        $converter->convert($data);
    }

    public function testCustomJsonFlags()
    {
        $data = ['url' => 'https://example.com'];

        $converter = (new ArrayToJsonConverter())
            ->withFlags(JSON_UNESCAPED_SLASHES);

        $json = $converter->convert($data);

        $this->assertStringContainsString('https://example.com', $json);
        $this->assertStringNotContainsString('\\/', $json); // kein escaped slash
    }
}
