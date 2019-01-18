<?php declare(strict_types=1);

namespace App\Models;

use App\Auth\Jwt\JwtSubjectInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements JwtSubjectInterface{

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

	public function getJwtSubject() : string{
		return $this->username;
	}
}