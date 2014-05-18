Laravel Flysystem
=================


[![Build Status](https://img.shields.io/travis/GrahamCampbell/Laravel-Flysystem/master.svg)](https://travis-ci.org/GrahamCampbell/Laravel-Flysystem)
[![Coverage Status](https://img.shields.io/coveralls/GrahamCampbell/Laravel-Flysystem/master.svg)](https://coveralls.io/r/GrahamCampbell/Laravel-Flysystem)
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg)](https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/GrahamCampbell/Laravel-Flysystem.svg)](https://github.com/GrahamCampbell/Laravel-Flysystem/releases)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Flysystem/badges/quality-score.png?s=f37f619e28817a3d4e143e4216cd875216a6f5f1)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Flysystem)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5002239a-89e2-43bc-8a51-ee35b064ef50/mini.png)](https://insight.sensiolabs.com/projects/5002239a-89e2-43bc-8a51-ee35b064ef50)


## What Is Laravel Flysystem?

Laravel Flysystem is a [Flysystem](https://github.com/thephpleague/flysystem) bridge for [Laravel 4.1](http://laravel.com).

* Laravel Flysystem was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell).
* Laravel Flysystem relies on Frank de Jonge's [Flysystem](https://github.com/thephpleague/flysystem) package and my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package.
* Laravel Flysystem uses [Travis CI](https://travis-ci.org/GrahamCampbell/Laravel-Flysystem) with [Coveralls](https://coveralls.io/r/GrahamCampbell/Laravel-Flysystem) to check everything is working.
* Laravel Flysystem uses [Scrutinizer CI](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Flysystem) and [SensioLabsInsight](https://insight.sensiolabs.com/projects/5002239a-89e2-43bc-8a51-ee35b064ef50) to run additional checks.
* Laravel Flysystem uses [Composer](https://getcomposer.org) to load and manage dependencies.
* Laravel Flysystem provides a [change log](https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Flysystem/releases), and [api docs](http://grahamcampbell.github.io/Laravel-Flysystem).
* Laravel Flysystem is licensed under the Apache License, available [here](https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md).


## System Requirements

* PHP 5.4.7+ or HHVM 3.0+ (HHVM support is pretty sketchy in some dependencies).
* You will need [Laravel 4.1](http://laravel.com) because this package is designed for it.
* You will need [Composer](https://getcomposer.org) installed to load the dependencies of Laravel Flysystem.


## Installation

Please check the system requirements before installing Laravel Flysystem.

To get the latest version of Laravel Flysystem, simply require `"graham-campbell/flysystem": "0.4.*@alpha"` in your `composer.json` file.

There are some additional dependencies you will need to install for some of the features:

* The awss3 connector requires `"aws/aws-sdk-php": "2.6.*"` in your `composer.json`.
* The rackspace connector requires `"rackspace/php-opencloud": "1.9.*"` in your `composer.json`.
* The dropbox connector requires `"dropbox/dropbox-sdk": "1.1.*"` in your `composer.json`.
* The webdav connector requires `"sabre/dav": "1.8.*"` in your `composer.json`.

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Flysystem is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Flysystem\FlysystemServiceProvider'`

You can register the Flysystem facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'Flysystem' => 'GrahamCampbell\Flysystem\Facades\Flysystem'`


## Configuration

Laravel Flysystem requires connection configuration.

To get started, first publish the package config file:

    php artisan config:publish graham-campbell/flysystem

There are three config options:

**Default Connection Name**

This option (`'default'`) is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `'local'`.

**Flysystem Connections**

This option (`'connections'`) is where each of the connections are setup for your application. Examples of configuring each supported driver are included in the config file. You can of course have multiple connections per driver.

**Flysystem Cache**

This option (`'cache'`) is where each of the cache configurations setup for your application. There are currently two drivers: illuminate and adapter. Examples of configuration are included. You can of course have multiple connections per driver as shown.


## Usage

**Managers\FlysystemManager**

This is the class of most interest. It is bound to the ioc container as `'flysystem'` and can be accessed using the `Facades\Flysystem` facade. This abstract class implements the ManagerInterface by extending AbstractManager. The interface and abstract class are both part of my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage). Note that the connection class returned will always be an instance of a class that implements `\League\Flysystem\FilesystemInterface` which will be `\League\Flysystem\Filesystem` by default.

**Facades\Flysystem**

This facade will dynamically pass static method calls to the `'flysystem'` object in the ioc container which by default is the `Managers\FlysystemManager` class.

**FlysystemServiceProvider**

This class contains no public methods of interest. This class should be added to the providers array in `app/config/app.php`. This class will setup ioc bindings.

**Real Examples**

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `local`, and it will just work straight away:

```php
use GrahamCampbell\Flysystem\Facades\Flysystem // you can alias this in app/config/app.php if you like

Flysystem::put('hi.txt', 'foo'); // we're done here - how easy was that, it just works!

Flysystem::read('hi.txt'); // this will return foo
```

The flysystem manager will behave like it is a `\League\Flysystem\Filesystem` class. If you want to call specific connections, you can do with the `connection` method:

```php
use GrahamCampbell\Flysystem\Facades\Flysystem // you can alias this in app/config/app.php if you like

Flysystem::connection('foo')->put('test.txt', 'bar'); // note the foo connection is not available by default

Flysystem::connection('bar')->read('test.txt'); // this will return bar
```

With that in mind, note that `Flysystem::connection('local')->read('test.txt')` is the same as writing `Flysystem::read('test.txt')`.

For more information on how to use the `\League\Flysystem\Filesystem` class we are calling behind the scenes here, check out the docs at https://github.com/thephpleague/flysystem#general-usage.

**Further Information**

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.

Feel free to check out the [API Documentation](http://grahamcampbell.github.io/Laravel-Flysystem
) for Laravel Flysystem.

You will eventually be able to see an example of implementation in [Laravel Assets](https://github.com/GrahamCampbell/Laravel-Assets) when ever I get around to actually writing that package.


## Updating Your Fork

Before submitting a pull request, you should ensure that your fork is up to date.

You may fork Laravel Flysystem:

    git remote add upstream git://github.com/GrahamCampbell/Laravel-Flysystem.git

The first command is only necessary the first time. If you have issues merging, you will need to get a merge tool such as [P4Merge](http://perforce.com/product/components/perforce_visual_merge_and_diff_tools).

You can then update the branch:

    git pull --rebase upstream master
    git push --force origin <branch_name>

Once it is set up, run `git mergetool`. Once all conflicts are fixed, run `git rebase --continue`, and `git push --force origin <branch_name>`.


## Pull Requests

Please review these guidelines before submitting any pull requests.

* When submitting bug fixes, check if a maintenance branch exists for an older series, then pull against that older branch if the bug is present in it.
* Before sending a pull request for a new feature, you should first create an issue with [Proposal] in the title.
* Please follow the [PSR-2 Coding Style](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and [PHP-FIG Naming Conventions](https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-naming-conventions.md).


## License

Apache License

Copyright 2014 Graham Campbell

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
