Blog extension for Yii2
=======
Nested category, post for multilanguage, multidomain site

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist koperdog/yii2-nsblog "*"
```

or add

```
"koperdog/yii2-nsblog": "*"
```

to the require section of your `composer.json` file.

and add the module to backend config:
```php
'modules' => [
    //...
    'blog' => [
        'class' => 'koperdog\yii2nsblog\backend\Module',
    ],
    //...
],
```
Also you need add the module to frontend config:
```php
'modules' => [
    //...
    'blog' => [
        'class' => 'koperdog\yii2nsblog\frontend\Module',
    ],
    //...
],
'urlManager' => [
    'rules' => [
        [
            'class' => 'koperdog\yii2nsblog\components\CategoryUrlRule',
            //'prefix' => 'blog'
        ],
        [
            'class' => 'koperdog\yii2nsblog\components\PageUrlRule',
            //'prefix' => 'blog'
        ],
    ],
],
```
Then you should start the migration (console):
```php
php yii migrate --migrationPath=@vendor/koperdog/yii2-nsblog/migrations
```

Usage
-----

Go to backend /blog/categories or /blog/pages

Also you can clone this repository for create your extension (for example, a store, etc.)