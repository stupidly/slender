<?php declare(strict_types=1);

namespace App\Auth\Eloquent;

use App\Auth\Auth;
use App\Auth\AuthRepositoryInterface;
use App\Models\User;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;

class EloquentAuthRepository implements AuthRepositoryInterface, ContainerAwareInterface{

	use ContainerAwareTrait;

    public function byCredentials($username, $password)
    {
        if (!$user = User::where('username', $username)->first()) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function byId($id)
    {
        return User::find($id);
    }

	public function register($username, $password, $role) : User{
		$user = User::create([
            "username" => $username,
            "password" => $this->hashPassword($password),
            "role" => $role
        ]);
        return $user;
	}

    public function hashPassword(string $password) : string {
    	$authConfig = $this->container->get('settings')->get('auth');
    	$alg = constant($authConfig['alg']);
    	$salt = $authConfig['salt'];
    	$cost = $authConfig['cost'];
    	return password_hash($password, $alg, [
    		'salt' => $salt,
    		'cost' => $cost
    	]);
    }
}