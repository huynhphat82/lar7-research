<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnMessage extends Model
{
    use SoftDeletes;

    protected $table = 'trn_message';
}
