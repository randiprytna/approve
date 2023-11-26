<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    public function complaintImages()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
