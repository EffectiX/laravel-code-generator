<?php

namespace Effectix\CodeGen\Test\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $table = 'prizes';
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
