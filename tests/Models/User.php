<?php

namespace Effectix\CodeGen\Test\Models;

use Effectix\CodeGen\Traits\GeneratesCodes;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use GeneratesCodes;

    protected $table = 'users';
    protected $guarded = [];
}
