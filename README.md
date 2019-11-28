NS blog
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


Start the migration (console):
```php
php yii migrate --migrationPath=@vendor/koperdog/yii2-nsblog/migrations
```