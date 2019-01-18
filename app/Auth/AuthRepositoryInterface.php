<?php declare(strict_types=1);

namespace App\Auth;

use App\Models\User;

interface AuthRepositoryInterface{
	public function byCredentials($username, $password);
	public function byId($id);
	public function register($username, $password, $role) : User;
    public function hashPassword(string $password) : string;
}