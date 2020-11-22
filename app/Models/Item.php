<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model {
    use hasFactory;

    public function checklist()
    {
        return $this->belongsTo('App\Models\Checklist');
    }
}
