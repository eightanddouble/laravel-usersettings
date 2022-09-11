
# Laravel UserSettings

  

[![Latest Version on Packagist](https://img.shields.io/packagist/v/eightanddouble/laravel-usersettings.svg?style=flat-square)](https://packagist.org/packages/eightanddouble/laravel-usersettings) [![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/eightanddouble/laravel-usersettings/run-tests?label=tests)](https://github.com/eightanddouble/laravel-usersettings/actions?query=workflow%3Arun-tests+branch%3Amain) [![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/eightanddouble/laravel-usersettings/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/eightanddouble/laravel-usersettings/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) [![Total Downloads](https://img.shields.io/packagist/dt/eightanddouble/laravel-usersettings.svg?style=flat-square)](https://packagist.org/packages/eightanddouble/laravel-usersettings)

A Laravel package to manager per user basis settings. This package creates a column in the table of Users model and stores settings in JSON format and retrieves them using dot notation string representation of the array.
  

## Installation

  

You can install the package via composer:

  

```bash

composer require eightanddouble/laravel-usersettings

```  

Next, you can publish the config file with:  

```bash

php artisan vendor:publish --tag="laravel-usersettings-config"

```  

This is the contents of the published config file:
  

```php

<?php
return [ 
	/*
	 * The User model class upon which the per user settings are to be applied
	 */
	'users' => \App\Models\User::class,
	/*
	 * The desired name of the column which will be created once the package is migrated
	 */
	'settings_column' => 'settings',
	/*
	 * The unique identifier of the User model class defined above
	 */
	'default_constraint_key' => 'id',
];

```
The config defaults to the default User model location and creates a column named 'settings' and uses the primary key 'id' to filter and select the targeted User model. If you wish to change any of these, do so now before proceeding with migration.

Then, You can publish and run the migrations with: 

```bash

php artisan vendor:publish --tag="laravel-usersettings-migrations"

php artisan migrate

```
## Setup

Once after installation is done, its almost only that.
For those who might use, there is a command 
```bash
php artisan usersettings:init
```
This command initialises all the User models, the existing ones with a default settings list, if you wish. The default settings list is given in the User model as a public property called `init_settings`
```php
class  User  extends  Authenticatable
{
	// Generic User Model
	
	// Default settings that will be used by usersettings:init command
	public  $init_settings = [
		'theme' => [
			'color' => 'red',
		],
	];
}
```
You can schedule this command to run when a new user is created for now.
Automatic initialization of the settings upon User creation will be added in the next update.
  

## Usage with Examples

  After including the facade,

```php
use UserSettings;
```
The package facilitates the following methods:

```php
UserSettings::set();
UserSettings::get();
UserSettings::forget();
UserSettings::has();
UserSettings::all();
```

### Without Constraint Value

When you use the calls above without any constraint value specified, it targets the logged in user.

#### Get all settings

Here is an empty User Settings and the changes it undergoes for each of the above calls.

```php
//Initial
$settings = UserSettings::all()
dd($settings);

//Output
[]
```

#### Set

Sets a plain setting:
```php
UserSettings::set('foo', 'foo_value');

//Current Settings
[
	"foo" => "foo_value"
]
```
Sets a grouped setting:
```php
UserSettings::set('bar.baz', 'baz_value');

//Current Settings
[
	"foo" => "foo_value",
	"bar" => [
		"baz" => "baz_value"
	]
]
```
Sets multiple settings at once:
```php
UserSettings::set([
	'thud.plugh' => 'plugh_value',
	'thud.fred' => 'fred_value',
	'grunt.gorp' => 'gorp_value'
]);

//Current Settings
[
	"foo" => "foo_value",
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```
In case if an existing group name is used as setting name to set a value, it replaces the group. Like below,
```php
UserSettings::set('bar', 'bar_value');

//Current Settings
[
	"foo" => "foo_value",
	"bar" => 'bar_value'
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```
And, vice versa, if a setting name is used as a group name, it replaces the setting. 
```php
UserSettings::set('bar.baz', 'baz_value');

//Current Settings
[
	"foo" => "foo_value",
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```
Now, if you mention a setting present already as a grouped one, a new group with a new nested setting is created.
```php
UserSettings::set('fum.foo', 'foo_value_1');

//Current Settings
[
	"foo" => "foo_value",
	"fum" => [
		"foo" => "foo_value_1"
	],
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```
#### Get
Get a plain setting
```php
$setting = UserSettings::get('foo');

//$setting
foo_value
```
Get a nested setting
```php
$setting = UserSettings::get('grunt.gorp');

//$setting
gorp_value
```
Get a group of settings
```php
$setting = UserSettings::get('thud');

//$setting
[
	"plugh" => "plugh_value",
	"fred" => "fred_value"
]
```

Get a plain/nested setting with default value if the setting is not present
```php
$setting = UserSettings::get('rey', default: 'rey_value');

//$setting
rey_value
```
Get a grouped setting with default value if the setting group is not present
```php
$setting = UserSettings::get('huy', default: ["hum" => "hum_value", "vum" => "vum_value"]);

//$setting
[
	"hum" => "hum_value",
	"vum" => "vum_value"
]
```

#### Forget

Unset or delete a setting by calling `forget`.

Remove or forget a single/nested setting
```php
UserSettings::forget('fum.foo');

//Current Settings
[
	"foo" => "foo_value",
	"fum" => [],
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```
Remove or forget a group setting
```php
UserSettings::forget('fum');

//Current Settings
[
	"foo" => "foo_value",
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]
```

#### Has

Check for the existence of a setting use `has`. This will return a boolean.

```php
UserSettings::has('foo'); // true
UserSettings::has('fum'); // false
UserSettings::has('thud.plugh'); // true
```

#### All
Retrieve all settings as an associative array using `all`. Returns an array
```php
$settings = UserSettings::all();

// $settings
[
	"foo" => "foo_value",
	"bar" => [
		"baz" => "baz_value"
	],
	"thud" => [
		"plugh" => "plugh_value",
		"fred" => "fred_value"
	],
	"grunt" => [
		"gorp" => "gorp_value"
	],
]

```

### With Constraint Value

As an example, when you as admin want to work on the settings of a particular user, you need to mention the user's `id` as the custom constraint value. The default field of constraint is `id`. This can be changed in the config.

For example puposes, lets assume we want to target the user with ID 12.
```php
// Set
UserSettings::set('foo', 'foo_value', 12);
UserSettings::set('foo.bar', 'bar_value', 12);
UserSettings::set([
	'thud.plugh' => 'plugh_value',
	'thud.fred' => 'fred_value',
	'grunt.gorp' => 'gorp_value'
], 12);

// Get
UserSettings::get('foo', 12);
UserSettings::get('grunt.gorp', 12)
UserSettings::get('rey', 12, default: 'rey_value'); // The constraint needs to be the second param when default is specified for get

// Forget
UserSettings::forget('fum.foo', 12);
UserSettings::forget('fum', 12);

// Has
UserSettings::has('foo', 12);
UserSettings::has('thud.plugh', 12)

// All
UserSettings::all(12)
```




## To Do

 1. Try to add type constraints to the values
 2. Add more appropriate and streamlines tests

.

## Testing  

```bash

composer test

```

  

## Changelog

  

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

  

## Contributing

  

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

  

## Security Vulnerabilities

  

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

  

## Credits

  

- [Praveen K](https://github.com/pravnkay)

- [All Contributors](../../contributors)

  

## License

  

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.