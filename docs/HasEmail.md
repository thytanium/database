# HasEmail

When your model has an `email` attribute this trait provides common methods for emails.

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasEmail;

class MyModel extends Model
{
    use HasEmail;
}
```

## Methods

### email

```php
MyModel::email('test@example.com');
```

Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### findByEmail

```php
MyModel::findByEmail('test@example.com');
```

Returns `MyModel` instance if found or `null` if not found.