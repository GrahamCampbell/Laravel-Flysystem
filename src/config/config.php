<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

return array(

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Flysystem Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Examples of
    | configuring each supported driver is shown below. You can of course have
    | multiple connections per driver.
    |
    */

    'connections' => array(

        'awss3' => array(
            'driver'    => 'awss3',
            'key'       => 'your-key',
            'secret'    => 'your-secret',
            'bucket'    => 'your-bucket',
            // 'region'    => 'your-region',
            // 'base_url'  => 'your-url',
            // 'options'   => array(),
            // 'prefix'    => 'your-prefix',
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'copy' => array(
            'driver'          => 'copy',
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
            // 'prefix'          => 'your-prefix',
            // 'eventable'       => true,
            // 'cache'           => 'foo'
        ),

        'dropbox' => array(
            'driver'    => 'dropbox',
            'token'     => 'your-token',
            'app'       => 'your-app',
            // 'prefix'    => 'your-prefix',
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'ftp' => array(
            'driver'    => 'ftp',
            'host'      => 'ftp.example.com',
            'port'      => 21,
            'username'  => 'your-username',
            'password'  => 'your-password',
            // 'root'      => '/path/to/root',
            // 'passive'   => true,
            // 'ssl'       => true,
            // 'timeout'   => 20,
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'local' => array(
            'driver'    => 'local',
            'path'      => storage_path('files'),
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'null' => array(
            'driver'    => 'null',
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'rackspace' => array(
            'driver'    => 'rackspace',
            'endpoint'  => 'your-endpoint',
            'username'  => 'your-username',
            'password'  => 'your-password',
            'container' => 'your-container',
            'urltype'   => 'internalURL', // The URL type ("publicURL" or "internalURL")
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'sftp' => array(
            'driver'     => 'sftp',
            'host'       => 'sftp.example.com',
            'port'       => 22,
            'username'   => 'your-username',
            'password'   => 'your-password',
            // 'privateKey' => 'path/to/or/contents/of/privatekey',
            // 'root'       => '/path/to/root',
            // 'timeout'    => 20,
            // 'eventable'  => true,
            // 'cache'      => 'foo'
        ),

        'webdav' => array(
            'driver'    => 'webdav',
            'baseUri'   => 'http://example.org/dav/',
            'userName'  => 'your-username',
            'password'  => 'your-password',
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

        'zip' => array(
            'driver'    => 'zip',
            'path'      => storage_path('files.zip'),
            // 'eventable' => true,
            // 'cache'     => 'foo'
        ),

    ),

    /*
    |--------------------------------------------------------------------------
    | Flysystem Cache
    |--------------------------------------------------------------------------
    |
    | Here are each of the cache configurations setup for your application.
    | There are currently two drivers: illuminate and adapter. Examples of
    | configuration are included. You can of course have multiple connections
    | per driver as shown.
    |
    */

    'cache' => array(

        'foo' => array(
            'driver'    => 'illuminate',
            'connector' => null, // null means use default driver
            'key'       => 'foo',
            // 'ttl'       => 300
        ),

        'bar' => array(
            'driver'    => 'illuminate',
            'connector' => 'redis', // app/config/cache.php
            'key'       => 'bar',
            'ttl'       => 600,
        ),

        'adapter' => array(
            'driver'  => 'adapter',
            'adapter' => 'local', // as defined in connections
            'file'    => 'flysystem.json',
            'ttl'     => 600,
        ),

    ),

);
