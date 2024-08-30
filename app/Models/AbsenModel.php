<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tb_absensi';
    protected $fillable = [
        'id', 
        'id_person',
        'status'
    ];

    public function person()
    {
        return $this->belongsTo(PersonModel::class, 'id_person', 'id');
    }
}
