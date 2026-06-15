<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaSales extends Model
{
    protected $table = 'table_d';

    protected $primaryKey = 'kode_sales';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'kode_sales',
        'nama_sales'
    ];
}
