# HasPosition

- [Usage](#Usage)
- [Methods](#Methods)
    - [moveUp](#moveUp)
    - [moveDown](#moveDown)
    - [moveTo](#moveTo)
    - [moveFirst](#moveFirst)
    - [moveLast](#moveLast)
    - [swapPositions](#swapPositions)
    - [positionTaken](#positionTaken)
    - [nextPosition](#nextPosition)
    - [position](#position)
    - [positionGt](#positionGt)
    - [positionGte](#positionGte)
    - [positionLt](#positionLt)
    - [positionLte](#positionLte)
    - [positionBetween](#positionBetween)
- [Pivots](#Pivots)

## Usage

When your model has a `position` attribute which defines hierarchy/importance among other models.

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasPosition;

class MyModel extends Model
{
    use HasPosition;
}
```

## Methods

### moveUp

```php
$myModel->moveUp();
```
Moves the model up 1 space. The model located above is moved down.

```php
$myModel->moveUp(3);
```
Moves the model up 3 spaces. The models located above are moved down.

### moveDown

```php
$myModel->moveDown();
```
Moves the model down 1 space. The model located below is moved up.

```php
$myModel->moveDown(3);
```
Moves the model down 3 spaces. The models located below are moved up.

### moveTo

```php
$myModel->moveTo(9);
```
Moves the model to the specified position. The models between are moved up/down accordingly.

### moveFirst
```php
$myModel->moveFirst();
```
Moves the model to the absolute top. The models above are move down.

### moveLast
```php
$myModel->moveLast();
```
Moves the model to the absolute bottom. The models below are move up.

### swapPositions
```php
$myModel->swapPositions(3); // Specify position
$myModel->swapPositions($anotherModel); // Specify another model
```
Swap positions between 2 models.

### positionTaken

```php
MyModel::positionTaken(3) // True or false
```
Determines if a specific position has already been taken.

### nextPosition

```php
MyModel::nextPosition();
```
Returns the next available position for a new model.

### position

```php
MyModel::position(1);
```

Looks for models with position `1`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### positionGt

```php
MyModel::positionGt(1);
```

Looks for models with position greater than `1`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### positionGte

```php
MyModel::positionGte(1);
```

Looks for models with position greater than or equal `1`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### positionLt

```php
MyModel::positionLt(1);
```

Looks for models with position less than `1`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### positionLte

```php
MyModel::positionLte(1);
```

Looks for models with position less than or equal `1`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

### positionBetween

```php
MyModel::positionBetween(1, 3);
```

Looks for models with positions between `1` and `3`.
Returns `Illuminate\Database\Eloquent\Builder`.
You can then use any `Builder` method like `get()`, `count()`, `where()` or custom query scopes.

## Pivots

It might happen that your model has a position column bound to another columns. Like this:

Name | Type | Position
--- | --- | ---
model A | type A | 1
model B | type A | 2
model C | type B | 1

In this case, the position is bound to the column **Type**, `type A` has an order, `type B` has an order, and so on.

Don't worry, this package can make this work for you.

You only need to define a `$positionPivots` array property in your model:

```php
use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasPosition;

class MyModel extends Model
{
    use HasPosition;

    public $positionPivots = ['type'];
}
```

You can define as many position pivots as you want.
