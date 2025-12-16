<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'idnotification';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idnotification','title','message','date','status','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
