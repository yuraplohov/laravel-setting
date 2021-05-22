# laravel-setting

Yuraplohov/laravel-setting is a simple package for creating and using settings for your Laravel application.<br /> 

This package automatically caches settings, if the `cache` parameter in the config file is `true`.<br /> 

The package provides two static methods for managing settings: `get()` and `set()`.<br />

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

## Usage

This package allows one Eloquent model `Setting`. Connect it like this:

```php
use Yuraplohov\Setting\Models\Setting;
```

You can create some settings in seeder:

```php
Setting::create([
    'name' => 'Setting name or short description',
    'key' => 'unique_setting_key',
    'value' => 'setting_value',
    'type' => Setting::TYPE_STRING,
    'unit' => null,
]);
```

You can use one of four constants for `'type'` property. There are: TYPE_STRING, TYPE_INT, TYPE_FLOAT, TYPE_BOOL.

`'Unit'` property is a unit of value for integer and float types. For example: 'seconds' or 'USD'.

You can get any setting value by unique key:

```php
$value => Setting::get('unique_setting_key');
```
Method `get()` automatically converts the value to the specified type.


Also you can change the value of any setting:

```php
Setting::set('unique_setting_key', 250);
```

You can select all settings from database with `cast()` method. This method automatically converts values to the specified types:

```php
$settings = Setting::all()->each->cast();
```

You can clear the settings cache:

```php
Setting::clearCache();
```
