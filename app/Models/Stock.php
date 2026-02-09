<?php

namespace App\Models;

use App\Support\UuidScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use UuidScopeTrait, SoftDeletes;

    protected $dates = [

    ];

    protected $fillable = [
        'uuid',
        'symbol',
        'name'
    ];

    protected $hidden = [
        'id'
    ];
}
