<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferenceOption extends Model
{
    use HasFactory;

    protected $fillable = ["name", "type"];
    public $timestamps = true;

    public function scopeRecent($query)
    {
        return $query->orderByDesc("created_at");
    }
}
