<?php

namespace App\Models\Pajak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PajakHiburan extends Model
{
    use HasFactory;
    protected $table = 'pajak_hiburan';
    protected $guarded = [];
}
