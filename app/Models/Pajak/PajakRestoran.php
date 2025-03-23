<?php

namespace App\Models\Pajak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PajakRestoran extends Model
{
    use HasFactory;
    protected $table = 'pajak_restoran';
    protected $guarded = [];
}
