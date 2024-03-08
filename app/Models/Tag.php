<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'group_id'];
    public $timestamps = false;

    public function scopeSolists($query)
    {
        return $query->whereNotIn('group_id', [4, 5]);
    }

    public function scopeEnsembles($query)
    {
        return $query->whereIn('group_id', [4, 5]);
    }
}
