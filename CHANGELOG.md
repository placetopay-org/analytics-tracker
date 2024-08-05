# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.1] - 2024-08-05

### Changed

- Fix `shouldTrackEvents` method. Now the `defaultPayload` and `Payload` are merged before sending to the validation `shouldTrack`.

## [1.1.0] - 2024-08-02

### Added

- Add `shouldTrackEvents` method to `AnalyticsTracker` contract.

## [1.0.1] - 2024-04-26

### Changed

- Facade name from `AnalyticsTracker` to `Analytics`

### Fixed

- Facade Accessor

## [1.0.0] - 2024-04-26

### Added

- Initial functionality to identify and track events
