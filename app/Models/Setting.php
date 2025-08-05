<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value', 
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    // Helper method to get setting value
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($setting->value) ? (float) $setting->value : $default;
            case 'json':
                return json_decode($setting->value, true) ?? $default;
            default:
                return $setting->value ?? $default;
        }
    }

    // Helper method to set setting value
    public static function set($key, $value, $type = 'text', $group = 'general', $description = null)
    {
        if ($type === 'json') {
            $value = is_array($value) ? json_encode($value) : $value;
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );
    }
}
