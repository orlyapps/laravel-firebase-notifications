# Sending Push Notifications via Firebase with API/Database Integration

[![Latest Version on Packagist](https://img.shields.io/packagist/v/orlyapps/laravel-firebase-notifications.svg?style=flat-square)](https://packagist.org/packages/orlyapps/laravel-firebase-notifications)
[![Quality Score](https://img.shields.io/scrutinizer/g/orlyapps/laravel-firebase-notifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/orlyapps/laravel-firebase-notifications)
[![Total Downloads](https://img.shields.io/packagist/dt/orlyapps/laravel-firebase-notifications.svg?style=flat-square)](https://packagist.org/packages/orlyapps/laravel-firebase-notifications)

## Installation

You can install the package via composer:

```bash
composer require orlyapps/laravel-firebase-notifications
php artisan migrate
```

### To generate a private key file for your service account:
1. In the Firebase console, open Settings> Service Accounts (Dienstkonten) .
2. Click Generate New Private Key and confirm by clicking Generate Key.
3. Save the JSON file with the key securely.

```php
// User.php
class User extends Authenticatable
{
    use Notifiable, HasPushTokens;
}

// services.php
'fcm' => [
    'json_file_path' => storage_path('xxxxx-annxd-xxxx.json'),
]

// api.php
LaravelFirebaseNotifications::routes();
```

## Usage

```js
fetch("http://laravel.test/api/push-token", {
    headers: {
        accept: "application/json, text/plain, */*",
        authorization:
            "Bearer 25|Zy2O22cipiT1wQWWJ5Dxdp9h2dPKEBNscHkViRa1F7LPIaFMHjr3yR4Q6YVCp6hIRrhcavNGfHcO7EJ6",
        "content-type": "application/json",
    },
    body:
        '{"token":"f60Yy793HIWGJAti0PdQKh:APA91bGMhHeCdJLUoleisatgB931pepFq_PJp3smQvXY8ENEDiK9ldL5HhsIQ-4bCaoyd3lxndRjueWcrLhLDccCQ05_objqt4-V9HGceK0xgBsiyGG4atu8xMAi7vnclcAvIZ7G9wB_","type":"web"}',
    method: "POST",
    mode: "cors",
    credentials: "include",
});
```

```php
 \App\User::find(1)->notify(new TextNotification('test', 'body', 'https://orlyapps.de'));
```

## Usage Angular App

```
npm install firebase @angular/fire -save

```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email info@orlyapps.de instead of using the issue tracker.

## Credits

-   [Orlyapps](https://github.com/orlyapps)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
