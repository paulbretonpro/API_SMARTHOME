<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = "sensor";

    public $timestamps = false;

    protected $visible = [
        'humidity',
        'temperature',
        'datetime'
    ];

    protected $fillable = [
        'humidity',
        'temperature',
        'datetime'
    ];

    protected function datetime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new Carbon($value),
        );
    }
}
