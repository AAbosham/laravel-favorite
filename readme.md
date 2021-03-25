# Favoritable

Favoritable is a package for Laravel.

## Installation

From the command line, run:

```
composer require aabosham/favoritable
```

```
php artisan migrate
```

```php
<?php

namespace App;

use Aabosham\Favoritable\Favoritable;

class Post extends Model
{
    use favoritable;

    ...
}
```

```php
$model->isFavoritedBy();


$model->favorite();

$model->favoritesCount();
```

### Scoping

```php
$models = Model::FavoritedBy(User $user)->get();
```