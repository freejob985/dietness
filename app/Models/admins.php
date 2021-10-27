<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class admins extends Authenticatable
{
    use HasFactory;
     protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $fillable = ['name','email','password'];
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "admins_permission", "admin_id", "permission_id");
    }

    public function hasPermission($name)
    {
        return $this->permissions->contains("name", $name);
    }
}
