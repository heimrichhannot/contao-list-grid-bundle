# Changelog

All notable changes to this project will be documented in this file.

## [0.4.5] - 2023-01-05
- Added: support for list bundle pagination mixed content
- Changed: min php version is now 7.4
- Changed: min contao version is now 4.9
- Changed: some code modernization
- Changed: removed test setup
- Fixed: added missing dependencies

## [0.4.4] - 2022-08-15
- Removed: usage of Patchwork/Utf8

## [0.4.3] - 2022-03-02
- Changed: allow php 8

## [0.4.2] - 2021-04-15
- fixed context of EventListener

## [0.4.1] - 2021-02-09
- fixed ambiguous pid issue in ListEventListener

## [0.4.0] - 2021-01-06
- fixed options_callback at listGrid_placeholderTemplate

## [0.3.2] - 2020-06-15

### Changed
- set services public

## [0.3.1] - 2020-01-09

### Fixed
- error on setting template name in list element when there are multiple lists with listgrids on one page

## [0.3.0] - 2019-07-22

### Added
- override listgrid in list configuration

## [0.2.0] - 2018-10-30

### Added
- `start` and `stop` for visibility of list-grid content element

## [0.1.4] - 2018-09-14

### Fixed
- used wrong class in `Plugin::setLoadAfter` for List bundle

## [0.1.3] - 2018-07-30

### Fixed
- php cs fixer config
- gitignore -> vendor

## [0.1.2] - 2018-07-23

### Fixed
- perPage count not updated correctly
- fixed readme

## [0.1.1] - 2018-06-29

### Fixed

- endless recursion

## [0.1.0] - 2018-06-20

Initial version
