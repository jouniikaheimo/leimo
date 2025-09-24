<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $record = static::query()->where('key', $key)->first();

        if (! $record) {
            return $default;
        }

        $value = $record->value;

        if (is_numeric($value)) {
            return $value + 0; // cast to int/float
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        $store = is_array($value) ? json_encode($value) : (string) $value;

        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $store],
        );
    }
}

