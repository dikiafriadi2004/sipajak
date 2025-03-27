<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHiburan extends Model
{
    use HasFactory;
    protected $table = 'laporan_pajak_hiburan';
    protected $guarded = [];
}
