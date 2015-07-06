Laravel Flysystem
=================

Laravel Flysystem was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a [Flysystem](https://github.com/thephpleague/flysystem) bridge for [Laravel 5](http://laravel.com). It utilises my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Flysystem/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).

![Laravel Flysystem](https://cloud.githubusercontent.com/assets/2829600/4432299/c12eac50-468c-11e4-93c3-5d587a2a56fa.PNG)

<p align="center">
<a href="https://travis-ci.org/GrahamCampbell/Laravel-Flysystem"><img src="https://img.shields.io/travis/GrahamCampbell/Laravel-Flysystem/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Flysystem/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Flysystem.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Flysystem"><img src="https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Flysystem.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Flysystem/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Flysystem.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Flysystem, simply add the following line to the require block of your `composer.json` file:

```
"graham-campbell/flysystem": "~3.1"
```

There are some additional dependencies you will need to install for some of the features:

* The AwsS3 adapter requires `"league/flysystem-aws-s3-v3": "~1.0"` in your `composer.json`.
* The Azure adapter requires `"league/flysystem-azure": "~1.0"` in your `composer.json`.
* The Copy adapter requires `"league/flysystem-copy": "~1.0"` in your `composer.json`.
* The Dropbox adapter requires `"league/flysystem-dropbox": "~1.0"` in your `composer.json`.
* The GridFS adapter requires `"league/flysystem-gridfs": "~1.0"` in your `composer.json`.
* The Rackspace adapter requires `"league/flysystem-rackspace": "~1.0"` in your `composer.json`.
* The Sftp adapter requires `"league/flysystem-sftp": "~1.0"` in your `composer.json`.
* The WebDav adapter requires `"league/flysystem-webdav": "~1.0"` in your `composer.json`.
* The ZipAdapter adapter requires `"league/flysystem-ziparchive": "~1.0"` in your `composer.json`.
* The adapter caching support requires `"league/flysystem-cached-adapter": "~1.0"` in your `composer.json`.
* The eventable filesystem support requires `"league/flysystem-eventable-filesystem": "~1.0"` in your `composer.json`.

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Flysystem is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Flysystem\FlysystemServiceProvider'`

You can register the Flysystem facade in the `aliases` key of your `config/app.php` file if you like.

* `'Flysystem' => 'GrahamCampbell\Flysystem\Facades\Flysystem'`


## Configuration

Laravel Flysystem requires connection configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```

This will create a `config/flysystem.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are three config options:

##### Default Connection Name

This option (`'default'`) is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `'local'`.

##### Flysystem Connections

This option (`'connections'`) is where each of the connections are setup for your application. Examples of configuring each supported driver are included in the config file. You can of course have multiple connections per driver.

##### Flysystem Cache

This option (`'cache'`) is where each of the cache configurations setup for your application. There are currently two drivers: illuminate and adapter. Examples of configuration are included. You can of course have multiple connections per driver as shown.


## Usage

##### FlysystemManager

This is the class of most interest. It is bound to the ioc container as `'flysystem'` and can be accessed using the `Facades\Flysystem` facade. This class implements the `ManagerInterface` by extending `AbstractManager`. The interface and abstract class are both part of my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage). Note that the connection class returned will always be an instance of a class that implements `\League\Flysystem\FilesystemInterface` which will be `\League\Flysystem\Filesystem` by default.

##### Facades\Flysystem

This facade will dynamically pass static method calls to the `'flysystem'` object in the ioc container which by default is the `FlysystemManager` class.

##### FlysystemServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### Real Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `local`, and it will just work straight away:

```php
use GrahamCampbell\Flysystem\Facades\Flysystem;
// you can alias this in config/app.php if you like

Flysystem::put('hi.txt', 'foo');
// we're done here - how easy was that, it just works!

Flysystem::read('hi.txt'); // this will return foo
```

The flysystem manager will behave like it is a `\League\Flysystem\Filesystem` class. If you want to call specific connections, you can do with the `connection` method:

```php
use GrahamCampbell\Flysystem\Facades\Flysystem;

// note the foo connection does not ship with this package, it's hypothetical
Flysystem::connection('foo')->put('test.txt', 'bar');

// now we can read that file
Flysystem::connection('foo')->read('test.txt'); // this will return bar
```

With that in mind, note that:

```php
use GrahamCampbell\Flysystem\Facades\Flysystem;

// writing this:
Flysystem::connection('local')->read('test.txt');

// is identical to writing this:
Flysystem::read('test.txt');

// and is also identical to writing this:
Flysystem::connection()->read('test.txt');

// this is because the local connection is configured to be the default
Flysystem::getDefaultConnection(); // this will return local

// we can change the default connection
Flysystem::setDefaultConnection('foo'); // the default is now foo
```

If you prefer to use dependency injection over facades like me, then you can easily inject the manager like so:

```php
use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Support\Facades\App; // you probably have this aliased already

class Foo
{
    protected $flysystem;

    public function __construct(FlysystemManager $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    public function bar()
    {
        $this->flysystem->read('test.txt');
    }
}

App::make('Foo')->bar();
```

For more information on how to use the `\League\Flysystem\Filesystem` class we are calling behind the scenes here, check out the docs at https://github.com/thephpleague/flysystem#general-usage, and the manager class at https://github.com/GrahamCampbell/Laravel-Manager#usage.

##### Further Information

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.


## License

Laravel Flysystem is licensed under [The MIT License (MIT)](LICENSE).
