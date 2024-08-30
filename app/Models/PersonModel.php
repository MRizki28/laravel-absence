<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tb_person';
    protected $fillable = [
        'id', 
        'name',
        'image_person'
    ];
}
