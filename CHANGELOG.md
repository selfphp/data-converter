# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-06-09
### Added
- ArrayToJsonConverter with support for JSON flags via `withFlags()`
- JsonToArrayConverter with strict type enforcement and exception handling
- Tests for all valid and invalid scenarios
- Updated README and usage examples

## [1.0.0] - 2025-06-09
### Added
- XmlToArrayConverter with full test suite
- Support for:
    - Nested elements
    - Multiple same tags
    - Attribute handling (`@id`)
    - Text content (`#text`)
    - Error handling for invalid XML
- Documentation for usage and limitations
- Updated README.md

## [0.9.0] - 2025-06-09
### Added
- Initial release with array-to-XML converter
- DOMDocument output support
- Null and boolean conversion
- Invalid XML character filtering
- Full unit test coverage
