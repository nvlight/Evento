<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_at',
        'date',
    ];

    public function tagValue(){
        return $this->hasOne(TagValue::class); // это тоже сработает
        //return $this->hasOne(TagValue::class, 'evento_id', 'id');
    }
}
