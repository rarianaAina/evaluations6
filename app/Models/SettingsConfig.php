<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type'
    ];

    public function getValueAttribute($value)
    {
        switch($this->type) {
            case 'number':
                return floatval($value);
            case 'boolean':
                return boolval($value);
            default:
                return $value;
        }
    }
}