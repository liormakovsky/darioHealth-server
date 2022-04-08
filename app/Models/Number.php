<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'numbers';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'num_number',
        'num_created'
    ];

}
