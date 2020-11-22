<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checklist extends Model {
    use hasFactory;

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}
