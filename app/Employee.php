<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
    protected $table = 'employee';
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'umur',
        'tgl_lahir',
        'tgl_gabung',
        'status',
        'created_at',
        'updated_at'
    ];
}