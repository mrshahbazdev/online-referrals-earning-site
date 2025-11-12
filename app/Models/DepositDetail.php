<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositDetail extends Model
{
    protected $fillable = ['network', 'address', 'qr_code_url'];
}
