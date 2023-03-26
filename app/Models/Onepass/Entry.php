<?php

namespace App\Models\Onepass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $table = 'onepass_entries';

    protected $fillable = [
        'user_id',
        'category_id',
        'url',
        'password',
        'email',
        'login',
        'phone',
        'name',
        'note',
    ];
}
