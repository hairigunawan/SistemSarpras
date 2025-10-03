<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Role.php
class Role extends Model
{
    // ...
    protected $primaryKey = 'id_role';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }
}
