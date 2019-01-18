<?php

namespace App\Auth\Jwt;

interface JwtSubjectInterface{
	public function getJwtSubject() : string;
}