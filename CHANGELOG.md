# Changelog

## 5.0.0 - 2026-07-13

### Added
- Support for Laravel 13 (`illuminate/support` ^13.0).
- Support for Orchestra Testbench 11 and PHPUnit 12.
- GitHub Actions test matrix entries for Laravel 13 on PHP 8.3 and 8.4.

## 4.0.0 - 2026-05-12

### Added
- Support for Laravel 10, 11 and 12.
- Support for `kreait/firebase-php` 6.x and 7.x.
- GitHub Actions test matrix (PHP 8.1–8.4 × Laravel 10/11/12).
- Test suite covering migration, model, routes, policy and push channel.

### Changed
- Bumped minimum PHP to 8.1.
- Migration converted to anonymous-class style.
- `PushToken::user()` now resolves via `config('auth.providers.users.model')` instead of hardcoded `\App\User`.
- `PushTokenPolicy::delete()` now type-hints `Illuminate\Contracts\Auth\Authenticatable`.
- `PushChannel::push()` visibility changed from `private` to `protected` for testability.
- `PushChannel` no longer uses the removed `Kreait\Firebase\ServiceAccount` class; credentials are passed directly to `Factory->withServiceAccount()`.

## 1.0.0 - 2020-09-01

- initial release
