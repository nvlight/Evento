<?php

namespace App\Models\Onepass;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'onepass_categories';

    protected $fillable = [
        'user_id',
        'name',
        'img',
        'note',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
