<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaSales extends Model
{
    protected $table = 'table_c';

    protected $primaryKey = 'kode_toko';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'kode_toko',
        'area_sales',
    ];
}
