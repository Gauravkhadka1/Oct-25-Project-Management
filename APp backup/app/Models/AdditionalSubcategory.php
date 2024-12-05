<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalSubcategory extends Model
{
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
