# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to the following versioning pattern:

Given a version number MAJOR.MINOR.PATCH, increment:

- MAJOR version when the **API** version is incremented. This may include backwards incompatible changes;
- MINOR version when **breaking changes** are introduced OR **new functionalities** are added in a backwards compatible manner;
- PATCH version when backwards compatible bug **fixes** are implemented.


## [Unreleased]
## [2.5.2] - 2021-06-06
### Fixed
- Imports on main file to avoid conflicts with user files

## [2.5.1] - 2021-06-01
### Fixed
- Ignored Invoice.discounts parameter

## [2.5.0] - 2021-05-17
### Added
- Invoice.Payment sub-resource to allow retrieval of invoice payment information

## [2.4.1] - 2021-04-06
### Fixed
- Uncaught Exception when passing datetimes as strings

## [2.4.0] - 2021-01-21
### Added
- Transfer->accountType property to allow "checking", "salary" or "savings" account specification
- Transfer->externalId property to allow users to take control over duplication filters

## [2.3.0] - 2021-01-20
### Added
- Organization user
- Add Organization User

## [2.2.1] - 2020-12-07
### Fixed
- UTF8 encoding issues

## [2.2.0] - 2020-11-19
### Added
- Invoice resource to load your account with dynamic QR Codes
- DictKey resource to get PIX key's parameters
- Deposit resource to receive transfers passively
- PIX support in Transfer resource
- BrcodePayment support to pay static and dynamic PIX QR Codes

## [2.1.0] - 2020-10-28
### Added
- BoletoHolmes to investigate boleto status according to CIP

## [2.0.0] - 2020-10-19
### Added
- ids parameter to transaction.query
- ids parameter to transfer.query
- PaymentRequest resource to pass payments through manual approval flow

## [0.6.0] - 2020-08-20
### Added
- transfer->scheduled parameter to allow Transfer scheduling
- StarkBank\Transfer::delete to cancel scheduled Transfers
- Transaction query by tags

## [0.5.1] - 2020-07-07
### Fixed
- HTTP 411 response on PHP Ubuntu

## [0.5.0] - 2020-06-05
### Added
- Travis CI integration
- Boleto PDF layout option
- Global error language setting
- Transfer query taxId parameter
### Changed
- StarkBank\User::setDefault() to StarkBank\Settings::setUser()
### Fixed
- Null JSON warning
### Removed
- PHP 7.0 compatibility

### Change
- Test user credentials to environment variable instead of hard-code

## [0.4.1] - 2020-05-15
### Added
- Support for PHP 7.0 & 7.1

## [0.4.0] - 2020-05-12
### Added
- "receiver_name" & "receiver_tax_id" properties to Boleto entities

## [0.3.1] - 2020-05-04
### Fixed
- Docstrings

## [0.3.0] - 2020-05-04
### Added
- Support for direct arrays in create methods
- "balance" property to Transaction entities

## [0.2.0] - 2020-04-29
### Added
- "discounts" property to Boleto entities
- Support for PHP 7.2
### Changed
- Internal folder structure
- Constructor internal pattern
### Fixed
- Docstrings
- Boleto payment test case

## [0.1.1] - 2020-04-18
### Changed
- Internal file names

## [0.1.0] - 2020-04-17
### Added
- Full Stark Bank API v2 compatibility
