# Zhylon OAuth2 Provider for Laravel Socialite

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tobymaxham/zhylon-oauth.svg?style=flat-square)](https://packagist.org/packages/tobymaxham/zhylon-oauth)
[![Tests](https://github.com/tobymaxham/zhylon-oauth/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/tobymaxham/zhylon-oauth/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/tobymaxham/zhylon-oauth.svg?style=flat-square)](https://packagist.org/packages/tobymaxham/zhylon-oauth)

---

## Installation

```bash
composer require tobymaxham/zhylon-oauth
```

## Configuration & Basic Usage

As a registered MX-User or Team Member at [Zhylon Systems](https://zhylon.de/), you can create an OAuth-Clients for your application.

### Add configuration to `config/services.php`

```php
'zhylon' => [    
   'client_id' => env('ZHYLON_CLIENT_ID'),  
   'client_secret' => env('ZHYLON_CLIENT_SECRET'),  
   'redirect' => env('ZHYLON_REDIRECT_URI'), 
],
```

If you need to configure another Zhylon-Server-Instance, you can also configure this instance by adding the following configuration:

```php
'zhylon' => [
   ...
   'instance' => env('ZHYLON_INSTANCE_URI', 'https://zhylon.de'),
],
```

## Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('zhylon')->redirect();
```

### Returned User fields

- ``id``
- ``name``
- ``email``
- ``profile_photo_url``

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [TobyMaxham](https://github.com/TobyMaxham)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
