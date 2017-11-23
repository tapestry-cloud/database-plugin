# Tapestry Database Plugin

[![Build Status](https://travis-ci.org/tapestry-cloud/database-plugin.svg?branch=master)](https://travis-ci.org/tapestry-cloud/database-plugin)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Gitmoji](https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg?style=flat-square)](https://gitmoji.carloscuesta.me)

This is a work in progress, which once complete will allow you to export the Tapestry project state to a database for manipulation by third party tools. In the case of Tapestry this is a precursor to the API plugin for an in browser admin panel.

### Install

To install run `composer require tapestry-cloud/database-plugin`

### Setup

Update your site configuration to include your database configuration:

```php
// ...
    'plugins' => [
        'database' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . DIRECTORY_SEPARATOR . 'db.sqlite'
        ]
    ]
// ...
```

Next within your site kernel.php you will need to register the plugins service provider within its boot method:

```php
public function boot(){
    // ...
    
    $this->tapestry->register(\TapestryCloud\Database\ServiceProvider::class);
    
    // ...
}
```

Upon you next running tapestry build your database will be updated with the current project state.

### Development

To run migrations use:

```
vendor\bin\doctrine.bat orm:schema-tool:update --force
```