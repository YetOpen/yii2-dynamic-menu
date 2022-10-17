# Dynamically configurable menu

This extension allow you to generate menus from backend, and load them dynamically according to user roles.

Rendering is based on `dmstr/yii2-adminlte-asset` menu widget.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: Check the [composer.json](https://packagist.org/packages/esempla/yii2-rbac) for this extension's requirements and dependencies.
Read this [web tip /wiki](https://packagist.org/packages/esempla/yii2-rbac) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```bash
php composer.phar require esempla/yii2-dynamic-menu "1.0.*"
```

or add

```bash
"esempla/yii2-dynamic-menu": "1.0.*"
```

to the `require` section of your `composer.json` file.

## Migrations

The extension has been created with database table. You should execute database migrations.(*PostgreSQL &reg;* recommended)

```php
php yii migrate/up --migrationPath=vendor/esempla/yii2-dynamic-menu/src/migrations
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

###  Configuration options

* `skipDuplicateHref` (default: true): if enabled, when merging multiple menus, the loader will skip items with duplicate HREF.
This requires every item, even expandable ones, to have a different href (not empty). To retain empty items, they should have a unique "#anchor" link;

## Usage

Add to your layouts view

```php
<?php echo \esempla\dynamicmenu\widgets\DynamicMenuWidget::widget(); ?>
```

By default, the widget will search for all user assigned roles and display the menu items matching the search.
It can be forced to load one or more roles, despite the user's ones, by passing the `roles` parameter. The parameter accepts either
a string or an array of strings.

## Menus management

To access Dynamic Menu management, go to app route `/menu/dynamic-menu`

### Item fields

For each menu item, the "classic" elements can be declared:

* Text: the text to display, with optional Font-Awesome icon
* URL: it can either be a direct url, or a route array in the form of a string. In both cases, the string is passed to `Url::toRoute()` for processing
* Target: html href target
* Tooltip: optional link tooltip
* Visibility condition: item visibility condition (optional). The string will be split either by `|` or `&`, and found tokens (permissions) will be passed to
`Yii::$app->user->can()`, and concatenated with the declared logical operator. No nested condition or parenthesis are accepted. Permissions can be prefixed
by `!` for negative test. Some special cases strings are managed: `ISGUEST` will be translated to `Yii::$app->user->isGuest`.
