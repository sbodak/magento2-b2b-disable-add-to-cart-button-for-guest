# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-04-18

### Added
- PHP 7.3, 7.4, 8.0, 8.1, 8.2, 8.3, and 8.4 compatibility
- Unit tests for `IsSalablePlugin` covering all scenarios
- `phpunit.xml` configuration for running unit tests
- `Magento_Customer` added to module sequence in `module.xml`

### Changed
- **Breaking**: Source code moved to `src/` directory; PSR-4 autoload root updated accordingly
- `etc/di.xml` moved to `etc/frontend/di.xml` — plugin now applies on the frontend area only (admin panel configuration remains unchanged)
- `afterIsSalable` plugin method now correctly accepts `$subject` and `$result` parameters, respecting the original product salability value for logged-in customers
- `setup_version` removed from `module.xml` (deprecated since Magento 2.3, removed in 2.4)
- Minimum Magento framework version requirement bumped to `>=103.0.0` (Magento 2.4.8)
- Module version bumped to `2.0.0`

### Fixed
- `afterIsSalable` previously ignored the original `$result` value; logged-in customers now correctly see non-salable products as non-salable
- `afterIsSalable` previously returned `true` unconditionally when the feature was disabled, ignoring the product's actual salability state

## [1.0.3] - 2017-xx-xx

### Added
- Initial release
- Admin configuration option to enable/disable add-to-cart for guest users
- `IsSalablePlugin` to block `Product::isSalable()` for unauthenticated visitors
- PHP 7.0, 7.1, 7.2 compatibility
- Magento 2.1, 2.2, 2.3 compatibility
