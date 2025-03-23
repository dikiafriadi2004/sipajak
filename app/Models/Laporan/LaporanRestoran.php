<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRestoran extends Model
{
    use HasFactory;
    protected $table = 'laporan_pajak_restoran';
    protected $guarded = [];
}
