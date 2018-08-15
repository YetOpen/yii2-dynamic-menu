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
And set components to use as a default authManager
```php
'components' => [
	'authManager' => [
		'class' => 'yii\rbac\DbManager',
	],
],
```
Set Url Manager for root configuration (*common/config &reg;* recommended)
```php
'components' => [
    ...
	'urlManager' => [
                        'class' => 'yii\web\UrlManager',
                        'showScriptName' => false,   // Disable index.php
                        'enablePrettyUrl' => true,   // Disable r= routes
                        'rules' => [
                                '<controller:[-\w]+>/<id:\d+>' => '<controller>/view',
                                '<controller:[-\w]+>/<action:[-\w]+>/<id:\d+>' => '<controller>/<action>',
                                '<controller:[-\w]+>/<action:[-\w]+>' => '<controller>/<action>',
                                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' =>'<module>/<controller>/<action>',
                    '<module:\w+>/<controller:\w+>/<action:\w+>' =>	'<module>/<controller>/<action>',
                 ]
    		],
	...
 ],
```
Config .htaccess for enable **Rewrite Module** (rewrite index.php and get parametres) for  (*backend/web &reg;* recommended) or/and (*frontend/web &reg;* recommended) 
```.htaccess
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php
```
## Allowed routes 
#### (required to enable route verification)
Add 'as access' to config file (backend or/and frontend config.php).
To add public route add new rout in ***allowActions*** section.(backend or/and frontend config.php)
**!Atention this config allow routes for guests**
```php
'as access' => [
		'class' => esempla\rbac\filters\AccessControl::class,
			'allowActions' => [
				'site/*',
				'rbac/*',
				'test/*'
			]
    ]
```
## Default Roles
To set default roles add parameters to your params.php (common/params.php &reg;recommended)
```php
 return [
      'default_roles' => json_encode(
                  [
                      [
                          "created_user" => "example_id",
                          "created_datetime" => "example_date",
                          "role" => "user",
                          "permissions" => [
                              "/site/index",
                              "/site/manage",
                                ...
                          ]
                      ],
                      [
                          "created_user" => "example_id",
                          "created_datetime" => "example_date",
                          "role" => "user2",
                          "permissions" => [
                              "/site/index",
                              "/site/manage",
                                ...
                          ]
      
                      ]
                  ]),
    ];
```
## Internationalization
```php
 'components' => [
        ...
		'sourceLanguage' => 'en-US',
		'i18n' => [
			'translations' => [
					'*' => [
							'class' => 'yii\i18n\PhpMessageSource',
							'basePath' => '@vendor/esempla/yii2-rbac/src/messages',
							'sourceLanguage' => 'en-US',
							'forceTranslation'=>true,
							'fileMap' => [
								'rbac' => 'rbac.php',
							],
						] 
				    ]       
	            ]
        ...
]
```
## Testing
Add configs to your backend/frontend<code>config/test.php</code>
  ```php
        'components' => [
				urlManager' => [
					'showScriptName' => true,
				],
				'db' => [
				'class' => 'yii\db\Connection',
				'dsn' => 'pgsql:host=localhost;port=5432;dbname=secondday',
				'username' => 'postgres',
				'password' => 'postgres',
				'charset' => 'utf8',
			],
        ],
   ```
##
#### To access RBAC  go to /rbac/index
##

#### From Terminal execute following:
```sh
           cd vendor/esempla/yii2-rbac &&
		   php ../../../vendor/bin/codecept run unit
```
#### **All tests must be return true**



