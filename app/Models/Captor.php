<?php

namespace App\Models;

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
}
