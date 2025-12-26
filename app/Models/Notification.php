<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string $title
 * @property string $message
 * @property Carbon $date
 * @property string $status
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';

	protected $casts = [
		'date' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'title',
		'subject',
		'message',
		'date',
		'status',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
