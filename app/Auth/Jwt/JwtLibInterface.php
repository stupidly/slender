<?php declare(strict_types=1);

namespace App\Auth\Jwt;

interface JwtLibInterface{
	public function create() : self;
	public function withSubject(string $subject) : self;
	public function withId(string $id) : self;
	public function withIssuer(string $issuer) : self;
	public function withIsuedAt(int $issuedAt) : self;
	public function withNotBefore(int $notBefore) : self;
	public function withExpiration(int $expiration) : self;
	public function encode() : string;
	public function decode(string $token) : array;
}