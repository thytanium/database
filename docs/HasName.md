# HasName

When your model has a `name` attribute this trait provides common methods for names.

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasName;

class MyModel extends Model
{
    use HasName;
}
```

## Methods

### static::name() Query Scope

```php
MyModel::name('some name');
```

Returns `Illuminate\Database\Eloquent\Builder`.

### static::findByName()

```php
MyModel::findByName('some name');
```

Returns `MyModel` instance if found or `null` if not found.