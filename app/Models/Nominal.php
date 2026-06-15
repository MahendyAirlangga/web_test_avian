<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nominal extends Model
{
    protected $table = 'table_b';

    protected $primaryKey = 'kode_toko';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'kode_toko',
        'nominal_transaksi'
    ];
}
