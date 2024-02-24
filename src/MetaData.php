<?php namespace DigitalRuby\MetaData;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model {

    protected $fillable = ['key', 'value'];

    public function entity()
    {
        return $this->morphTo();
    }
    
}