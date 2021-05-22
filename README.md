# laravel-setting

Yuraplohov/laravel-setting is a simple package for creating and using settings for your Laravel application.

## Install

You can install the package via composer:

```bash
composer require yuraplohov/laravel-setting
```

You should add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Yuraplohov\Setting\SettingServiceProvider::class,
];
```

You should publish the migration and the config/setting.php config file with:

```bash
php artisan vendor:publish --provider="Yuraplohov\Setting\SettingServiceProvider"
```

After the config and migration have been published and configured, you can create the table for this package by running:

```bash
php artisan migrate
```
