<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qualtity extends Model
{
    public $table = 'check_qualtity';

    public $fillable = [
        'model',
        'qualtity',
    ];    
}
