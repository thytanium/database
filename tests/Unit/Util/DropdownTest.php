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
    protected function buildDropdown()
    {
        return new Dropdown([
            1 => 'model-1',
            2 => 'model-2',
        ]);
    }

    /**
     * Test ArrayAccess and Countable abilites.
     * 
     * @return void
     */
    public function test_array_access()
    {
        $dropdown = $this->buildDropdown();

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
        $dropdown = $this->buildDropdown();

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
        $dropdown = $this->buildDropdown();

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
        $dropdown = $this->buildDropdown();

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

        $dropdown = $this->buildDropdown();

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
