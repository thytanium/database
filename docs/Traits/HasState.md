# HasState

When your model has a `state` attribute this trait provides common methods for states.

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasState;

class MyModel extends Model
{
    use HasState;
}
```

> You need a `states` database table. A migration can be made for it, read [States console command](../Console/StatesCommand.md) for more information. 

## Predefined states

> If you don't have any states you can fill your database with these predefined states by running `php artisan db:states --seed`. Read [States console command](../Console/StatesCommand.md) for more information.

* Inactive
* Active
* Banned
* Suspended
* Accepted
* Published
* Draft

## Additional states

You can add additional states by inserting rows in `states` db table.
You just need to provide and `id` and a `name` for the state.

All methods described below apply for all existing states in db, 
not just the predefined ones.

## Valid states

Since all states may not be valid for your model, 
you can define which ones are valid:

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasState;

class MyModel extends Model
{
    use HasState;

    public $validStates = ['Inactive', 'Active'];
}
```

This way you can forget about validating states. When setting a state in your model, 
this package will automatically check it's valid for this model or 
throw a `Thytanium\Database\Exceptions\InvalidStateException` when the new state is invalid.

> Of course, you can also omit the `$validStates` property and work with all states.

> This verification is about checking if a new state is between the valid states 
defined for your model. 
If the new state is filled by the end user and you want to verify 
the state actually exists in db then you can use the `exists` validation rule.

## Methods

### setState

Sets a new state for the model.

```php
$myModel->setState('Inactive');

// Persist this change
$myModel->save();
```

You can also chain methods.

```php
$myModel->setState('Inactive')->save();
```

### isState

Checks if the model has a specific state.

```php
$myModel->isState('Inactive'); // True or false
```

### validState

Checks if a new state is valid for the model.

```php
$myModel->validState('Suspended'); // True or false
```

### hasState

```php
MyModel::hasState('Active');
```

Looks for models with state `Active`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.