WordPress Database
==================

## Installation

```
composer require awethemes/database
```

## Basic Example

```php
<?php

use Awethemes\Database\Database;

$builder = Database::newQuery()->select( '*' )->from( 'posts' );

var_dump( $posts = $builder->get() );

var_dump( $builder->toSql() ); // select * from `{$wpdb->posts}`
```

The query above can be shorten by this:

```php
$posts = Database::table( 'posts' )->get();
```
