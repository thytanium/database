<?php

namespace Tests\Unit\Util;

use Illuminate\Support\Arr;
use Illuminate\Translation\ArrayLoader;
use Mockery as m;
use Thytanium\Database\Util\Dropdown;
use Thytanium\Tests\TestCase;

class DropdownTest extends TestCase
{
    /**
     * Build test Dropdown.
     * 
     * @return Dropdown
     */
    protected function dropdown()
    {
        return new Dropdown([
            1 => 'model-1',
            2 => 'model-2',
        ]);
    }

    /**
     * Data for testing.
     *
     * @param  array $params
     * @return array
     */
    protected function dropdownBuilder($params = [])
    {
        $data = [
            (object) ['id' => 1, 'name' => 'model-1', 'email' => 'some_email@thytanium.com'],
            (object) ['id' => 2, 'name' => 'model-2', 'email' => 'some_other_email@thytanium.com'],
        ];

        return call_user_func_array('Thytanium\Database\Util\Dropdown::build', Arr::prepend($params, $data));
    }

    /**
     * Test the most basic case.
     * 
     * @return void
     */
    public function test_basic_case()
    {
        $result = $this->dropdownBuilder();

        $this->assertEquals([
            1 => 'model-1',
            2 => 'model-2',
        ], $result->toArray());
    }

    /**
     * Test with different column names.
     * 
     * @return void
     */
    public function test_with_other_columns()
    {
        $result = $this->dropdownBuilder(['email', 'name']);

        $this->assertEquals([
            'some_email@thytanium.com' => 'model-1',
            'some_other_email@thytanium.com' => 'model-2',
        ], $result->toArray());

        $result = $this->dropdownBuilder(['name', 'email']);

        $this->assertEquals([
            'model-1' => 'some_email@thytanium.com',
            'model-2' => 'some_other_email@thytanium.com',
        ], $result->toArray());
    }

    /**
     * Test with callables as column names.
     * 
     * @return void
     */
    public function test_with_callables()
    {
        $result = $this->dropdownBuilder([function ($model) {
            return "{$model->id}.{$model->email}";
        }, 'name']);

        $this->assertEquals([
            '1.some_email@thytanium.com' => 'model-1',
            '2.some_other_email@thytanium.com' => 'model-2',
        ], $result->toArray());

        $result = $this->dropdownBuilder(['id', function ($model) {
            return "{$model->email} : {$model->name}";
        }]);

        $this->assertEquals([
            1 => 'some_email@thytanium.com : model-1',
            2 => 'some_other_email@thytanium.com : model-2',
        ], $result->toArray());
    }

    /**
     * Test with callables as column names with index.
     * 
     * @return void
     */
    public function test_callable_with_index()
    {
        $result = $this->dropdownBuilder([function ($model, $index) {
            return $index;
        }]);

        $this->assertEquals([
            0 => 'model-1',
            1 => 'model-2',
        ], $result->toArray());
    }

    /**
     * Test ArrayAccess and Countable abilites.
     * 
     * @return void
     */
    public function test_array_access()
    {
        $dropdown = $this->dropdown();

        $this->assertArrayNotHasKey(0, $dropdown);
        $this->assertCount(2, $dropdown);
        $this->assertEquals('model-1', $dropdown[1]);
        $this->assertEquals('model-2', $dropdown[2]);

        $dropdown[] = 'model-3';

        $this->assertArrayNotHasKey(0, $dropdown);
        $this->assertCount(3, $dropdown);
        $this->assertEquals('model-3', $dropdown[3]);

        $dropdown['id-4'] = 'model-4';

        $this->assertArrayNotHasKey(0, $dropdown);
        $this->assertEquals('model-4', $dropdown['id-4']);

        $this->assertFalse(isset($dropdown[0]));
        $this->assertFalse(isset($dropdown[4]));
        $this->assertTrue(isset($dropdown['id-4']));

        unset($dropdown[1]);

        $this->assertArrayNotHasKey(1, $dropdown);
        $this->assertCount(3, $dropdown);
        $this->assertFalse(isset($dropdown[1]));
    }

    /**
     * Test Arrayable abilities.
     * 
     * @return void
     */
    public function test_arrayable()
    {
        $dropdown = $this->dropdown();

        $this->assertEquals([
            1 => 'model-1',
            2 => 'model-2',
        ], $dropdown->toArray());
    }

    /**
     * Test append() method.
     * 
     * @return void
     */
    public function test_append()
    {
        $dropdown = $this->dropdown();

        $dropdown->append('model-x');

        $this->assertEquals('model-x', Arr::last($dropdown->toArray()));
        $this->assertEquals('model-x', $dropdown['']);

        $dropdown->append('model-x', 'key');

        $this->assertEquals('model-x', Arr::last($dropdown->toArray()));
        $this->assertEquals('model-x', $dropdown['key']);
    }

    /**
     * Test prepend() method.
     * 
     * @return void
     */
    public function test_prepend()
    {
        $dropdown = $this->dropdown();

        $dropdown->prepend('model-x');

        $this->assertEquals('model-x', Arr::first($dropdown->toArray()));
        $this->assertEquals('model-x', $dropdown['']);

        $dropdown->prepend('model-x', 'key');

        $this->assertEquals('model-x', Arr::first($dropdown->toArray()));
        $this->assertEquals('model-x', $dropdown['key']);
    }

    /**
     * Test lang() method.
     * 
     * @return void
     */
    public function test_lang()
    {
        // Translation namespace
        $namespace = 'namespace';

        $dropdown = $this->dropdown();

        $translator = m::mock('Illuminate\Translation\Translator[has,get]', [new ArrayLoader, 'xx']);

        $translator->shouldReceive('has')
            ->with("{$namespace}.model-1")
            ->once()
            ->andReturn(true);

        $translator->shouldReceive('has')
            ->with("{$namespace}.model-2")
            ->once()
            ->andReturn(true);

        $translator->shouldReceive('get')
            ->with("{$namespace}.model-1")
            ->once()
            ->andReturn('translated_model-1');

        $translator->shouldReceive('get')
            ->with("{$namespace}.model-2")
            ->once()
            ->andReturn('translated_model-2');

        $dropdown->setTranslator($translator);

        $this->assertEquals([
            1 => 'translated_model-1',
            2 => 'translated_model-2',
        ], $dropdown->lang($namespace)->toArray());
    }
}
