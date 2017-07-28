# Change Log

All notable changes to this project will be documented in this file.

## [4.2.4]
### Changed
- link to german or english documentation and changelog respectively
- split logs per date

### Added
- download link for filtered log

## [4.2.3]
### Changed
- use default oxid behaviour for order success mails

## [4.2.2]
### Hotfix
- branding method problems

## [4.2.1]
### Changed
- branding method

## [4.2.0]
### Changed
- file structure

## [4.1.0]
### Added
- aftersales functionality (capture, refund)
- saving alias data for reuse

###Changed
- logging improvements
    - show session id and user in all log entries
    - logging entries in a filterable table
    - no duplicate entries in status overview

## [4.0.2]
### Changed
- check for order creation to prevent order with empty information and oxordernr = 0
- resolved order amount rounding error (e.g. with amount is 16.90)

## [4.0.1]
### Changed
- don't send order mails when handling deferred feedback

## [4.0.0]
### Added
- changelog

### Changed
- moved all files into module folder
- flow theme is standard, azure tpl will be delivered if azure theme is active
- smaller bugfixes
- special handling of apostroph in redirect request

## [3.3.0]
- see https://projects.mediaopt.de/projects/ogone-puc/wiki/Changelog for older changes
