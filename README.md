# selfphp/data-converter

> A lightweight and extensible PHP library to convert structured data between formats.  
> **Currently supports:** array → XML, XML → array and array ↔ JSON conversion.

---

## 🚀 Features

- ✅ Convert associative arrays to XML
- ✅ Convert XML to associative arrays
- ✅ Convert arrays to JSON (with optional flags)
- ✅ Convert JSON strings to associative arrays
- ✅ Custom root element name
- ✅ Optional XML declaration
- ✅ Null → `xsi:nil` conversion
- ✅ Boolean → string normalization (`true` / `false`)
- ✅ Recursive array handling
- ✅ Clean and readable output (pretty print)
- ✅ Invalid characters are automatically removed
- ✅ Minimal and modern code (no dependencies)

---

## 📦 Installation

```bash
composer require selfphp/data-converter
```

> ✅ PHP 8.1 or higher required

---

## ✨ Usage Example

### Array to XML

```php
use Selfphp\DataConverter\Format\ArrayToXmlConverter;

$array = [
    'user' => [
        'name' => 'Alice',
        'active' => true,
        'note' => null
    ]
];

$xml = ArrayToXmlConverter::convertArray(
    $array,
    rootElement: 'response',
    addXmlDeclaration: true,
    convertNullToXsiNil: true,
    convertBoolToString: true
);

echo $xml;
```

### XML to Array

```php
use Selfphp\DataConverter\Format\XmlToArrayConverter;

$xml = <<<XML
<user id="42" active="true">Alice</user>
XML;

$converter = new XmlToArrayConverter();
$array = $converter->convert($xml);

print_r($array);
// [
//     '@id' => '42',
//     '@active' => 'true',
//     '#text' => 'Alice'
// ]
```

### Array to JSON

```php
use Selfphp\DataConverter\Format\ArrayToJsonConverter;

$data = ['url' => 'https://example.com'];

$converter = (new ArrayToJsonConverter())
    ->withFlags(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

echo $converter->convert($data);
```

### JSON to Array

```php
use Selfphp\DataConverter\Format\JsonToArrayConverter;

$json = '{"name":"Alice","active":true}';

$converter = new JsonToArrayConverter();
$array = $converter->convert($json);
```

---

## 🧪 Tests

Run all PHPUnit tests:

```bash
vendor/bin/phpunit --testdox
```

---

## 📁 Project Structure

```
src/
└── Format/
    ├── ArrayToXmlConverter.php
    ├── XmlToArrayConverter.php
    ├── ArrayToJsonConverter.php
    └── JsonToArrayConverter.php

tests/
└── Format/
    ├── ArrayToXmlConverterTest.php
    ├── XmlToArrayConverterTest.php
    ├── ArrayToJsonConverterTest.php
    └── JsonToArrayConverterTest.php
```

---

## ⚠️ Limitations and Edge Cases

While the XmlToArrayConverter is suitable for most real-world use cases, there are a few edge cases and known limitations to be aware of:

### ❌ Mixed Content
XML nodes with both text and child elements (mixed content) are not fully preserved. For example:

```xml
<item>This is <b>bold</b> and normal text.</item>
```

Would result in:
```php
['b' => 'bold'] // Text parts around <b> are not preserved
```

### ⚠️ Empty Elements
Empty elements like `<foo/>` are interpreted as empty strings, not as `null` or empty arrays. If needed, you can post-process the result accordingly.

### ⚠️ All values are strings
XML data types (numbers, booleans) are not automatically casted. For example:
```xml
<active>true</active>
```
Becomes:
```php
['active' => 'true'] // not boolean true
```

### ❌ XML Namespaces
Namespaces (e.g. `xmlns` or prefixed elements) are ignored and stripped automatically. Support for namespaces may be added in a future release.

---

If you encounter any of these scenarios in real-world data, feel free to contribute or open an issue 🙌

---

## 🛠 Planned

- [x] XML → Array
- [x] JSON ↔ Array
- [ ] JSON ↔ XML
- [ ] CLI support (`php convert input.json`)
- [ ] Stream support

---

## 📄 License

MIT License – free for personal and commercial use.
