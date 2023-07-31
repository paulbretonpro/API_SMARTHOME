<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Captor extends Model
{
    use HasFactory;

    protected $table = "captor";
    public $timestamps = false;
    protected $visible = [
        'consumption',
        'datetime'
    ];

    protected $fillable = [
        "consumption",
        "datetime"
    ];

    protected function datetime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new Carbon($value),
        );
    }
}
