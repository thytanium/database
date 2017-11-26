<?php

namespace Tests\Eloquent\Traits;

use Tests\Unit\Eloquent\Models\TestModel;
use Tests\Unit\Eloquent\Models\User;
use Thytanium\Tests\TestCase;

class BelongsToUserTest extends TestCase
{
    /**
     * Test user() relationship.
     * 
     * @return void
     */
    public function test_relationship()
    {
        $model = new TestModel(['name' => 'model-1', 'user_id' => 1]);

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Relations\BelongsTo', 
            $model->user()
        );
    }

    /**
     * Test forUser() scope.
     * 
     * @return void
     */
    public function test_for_user_scope()
    {
        $query = TestModel::forUser(1)->getQuery();

        $this->assertArraySubset([
            [
                'column' => 'user_id',
                'operator' => '=',
                'value' => 1,
            ],
        ], $query->wheres);

        $user = new User(['id' => 1, 'name' => 'user-1']);
        $query = TestModel::forUser($user)->getQuery();

        $this->assertArraySubset([
            [
                'column' => 'user_id',
                'operator' => '=',
                'value' => 1,
            ],
        ], $query->wheres);
    }

    /**
     * Test setUser().
     * 
     * @return void
     */
    public function test_set_user()
    {
        $user = new User(['id' => 1, 'name' => 'user-1']);
        $model = new TestModel(['name' => 'model-1']);

        $model = $model->setUser($user);

        $this->assertEquals($model->user_id, $user->id);
        $this->assertEquals($model->user, $user);
    }
}
