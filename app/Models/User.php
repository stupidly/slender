<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

	protected $table = 'users';

	protected $primaryKey = 'username';

	protected $fillable = [
		"role",
		"username",
		"password",
		"enabled",
	];

	public $incrementing = false;

	protected $hidden = [
		'password'
	];
}