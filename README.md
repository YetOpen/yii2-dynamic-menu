<h1>Dynamically configurable menu</h1>

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: Check the [composer.json](https://packagist.org/packages/esempla/yii2-rbac) for this extension's requirements and dependencies.
Read this [web tip /wiki](https://packagist.org/packages/esempla/yii2-rbac) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require esempla/yii2-dynamic-menu "1.0.*"
```

or add

```
"esempla/yii2-dynamic-menu": "1.0.*"
```

to the ```require``` section of your `composer.json` file.

## Migrations
The extension has been created with database table. You should execute database migrations.(*PostgreSQL &reg;* recommended)

```php
php yii migrate/up --migrationPath=vendor/esempla/yii2-dynamic-menu//src/migrations
```

## Module
The extension has been created as a module. You should configure the module with a name of `authManager` as shown below:
```php
'modules' => [
	...
	'menu' => [
        'class' => 'esempla\dynamicmenu\DynamicMenu',
    ],
	...
],
```
## Usage
Add to your layouts view
```php
<?php echo \esempla\dynamicmenu\widgets\DynamicMenuWidget::widget(); ?>

```
##
#### To access Dynamic Menu  go to /menu/dynamic-menu
##



