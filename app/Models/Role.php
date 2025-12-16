<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
