<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $table = "weather";

    public $timestamps = false;

    protected $visible = [
        'humidity',
        'temperature',
        'feels_like',
        'wind_speed',
        'datetime'
    ];


    protected function datetime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new Carbon($value),
        );
    }
}
