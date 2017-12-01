<h1 align="center">Tapestry Database Plugin</h1>
<p align="center"><em>Syncs project state with database</em></p>

<p align="center">
  <a href="https://travis-ci.org/tapestry-cloud/database-plugin"><img src="https://travis-ci.org/tapestry-cloud/database-plugin.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/tapestry-cloud/database-plugin"><img src="https://poser.pugx.org/tapestry-cloud/database-plugin/v/stable.svg" alt="Latest Stable Version"></a>
  <a href="LICENSE"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

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
