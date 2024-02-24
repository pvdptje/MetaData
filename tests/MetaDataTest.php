<?php namespace DigitalRuby\MetaData\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MetaDataTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('test_models');
        Schema::create('test_models', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function getMockModel(): Model
    {
        $model = new class extends Model {
            use \DigitalRuby\MetaData\HasMetaData;

            protected $table = 'test_models';
        };

        $model->name = 'test';
        $model->save();

        return $model;
    }
    
    public function test_it_can_set_meta_data()
    {
        $model = $this->getMockModel();        
        $model->setMeta('test_key', 'value');
        
        $this->assertDatabaseHas('meta_data', ['key' => 'test_key', 'value' => 'value', 'entity_id' => $model->id, 'entity_type' => get_class($model)]);
    }

    public function test_it_can_get_meta_data()
    {
        $model = $this->getMockModel();        
        $model->setMeta('test_key', 'value');
        
        $this->assertEquals('value', $model->getMeta('test_key'));
    }

    public function test_it_can_get_all_meta_data()
    {
        $model = $this->getMockModel();
        
        $model->setMeta('test_key', 'value');
        $model->setMeta('test_key2', 'value2');
        
        $this->assertEquals(['test_key' => 'value', 'test_key2' => 'value2'], $model->getAllMeta()->toArray());
    }

    public function test_it_can_save_array_as_json()
    {
        $model = $this->getMockModel();        
        $model->setMeta('test_key', ['value' => 'value']);
        
        $this->assertEquals(['value' => 'value'], $model->getMeta('test_key'));
    }

    public function test_it_can_fetch_all_meta_with_arrays()
    {
        $model = $this->getMockModel();        
        $model->setMeta('test_key', ['value' => 'value']);
        $model->setMeta('test_key2', ['value2' => 'value2']);
        
        $this->assertEquals(['test_key' => ['value' => 'value'], 'test_key2' => ['value2' => 'value2']], $model->getAllMeta()->toArray());
    }

    public function test_it_can_delete_meta_data()
    {
        $model = $this->getMockModel();        
        $model->setMeta('test_key', 'value');        
        $model->deleteMeta('test_key');
        
        $this->assertDatabaseMissing('meta_data', ['key' => 'test_key', 'entity_id' => $model->id, 'entity_type' => get_class($model)]);
    }
}