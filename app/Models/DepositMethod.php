<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model
{
    protected $fillable = ['name', 'network', 'address', 'qr_code_url', 'is_active'];

}
