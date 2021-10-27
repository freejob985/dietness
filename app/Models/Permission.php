<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name','title_ar','title_en'];

    public $timestamps = false;

    public function admins()
    {
        return $this->belongsToMany(admins::class, "admins_permission", "permission_id", "admin_id");
    }
}
