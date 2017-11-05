# HasName

When your model has a `name` attribute this trait provides common methods for names.

- [Usage](#usage)
- [Methods](#methods)
    - [name](#name)
    - [findByName](#findbyname)

## Usage

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasName;

class MyModel extends Model
{
    use HasName;
}
```

## Methods

### name

```php
MyModel::name('some name');
```

Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### findByName

```php
MyModel::findByName('some name');
```

Returns `MyModel` instance if found or `null` if not found.