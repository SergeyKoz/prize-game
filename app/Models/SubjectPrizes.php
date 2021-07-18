<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SubjectPrizes extends Model
{
    protected $table = 'subject_prizes';

    protected $fillable = [
        'user_id',
        'title',
    ];
}
