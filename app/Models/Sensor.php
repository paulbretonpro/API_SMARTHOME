<?php

namespace App\Models;

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
}
