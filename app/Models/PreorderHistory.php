<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreorderHistory extends Model
{
    use HasFactory;

    protected $fillable = ['preorder_id', 'old_status', 'new_status', 'note'];

    public function preorder()
    {
        return $this->belongsTo(Preorder::class);
    }
}
