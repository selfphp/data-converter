# selfphp/data-converter

> A lightweight and extensible PHP library to convert structured data between formats.  
> **Currently supports:** array → XML conversion.

---

## 🚀 Features

- ✅ Convert associative arrays to XML
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
    └── ArrayToXmlConverter.php

tests/
└── Format/
    └── ArrayToXmlConverterTest.php
```

---

## 🛠 Planned

- [ ] XML → Array
- [ ] JSON ↔ XML
- [ ] CLI support (`php convert input.json`)
- [ ] Stream support

---

## 📄 License

MIT License – free for personal and commercial use.
