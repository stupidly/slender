<?php declare(strict_types=1);

namespace App\Auth\Jwt;

use App\Auth\Jwt\JwtLibInterface;
use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Container\ContainerInterface as Container;

class LcobucciJwtLib implements JwtLibInterface{

	protected $builder;
	protected $signer;
	protected $key;

	public function __construct(Container $container){
		$this->key = new Key($container->get('settings')->get('jwt.secret'));
		$this->signer = new Sha256();
	}
	
	public function create() : JwtLibInterface{
		$this->builder = new Builder();
		return $this;
	}
	public function withSubject(string $subject) : JwtLibInterface{
		$this->builder->setSubject($subject);
		return $this;
	}
	public function withId(string $id) : JwtLibInterface{
		$this->builder->setId($id);
		return $this;
	}
	public function withIssuer(string $issuer) : JwtLibInterface{
		$this->builder->setIssuer($issuer);
		return $this;
	}
	public function withIsuedAt(int $issuedAt) : JwtLibInterface{
		$this->builder->setIssuedAt($issuedAt);
		return $this;
	}
	public function withNotBefore(int $notBefore) : JwtLibInterface{
		$this->builder->setNotBefore($notBefore);
		return $this;
	}
	public function withExpiration(int $expiration) : JwtLibInterface{
		$this->builder->setExpiration($expiration);
		return $this;
	}
	public function encode() : string{
		$this->builder->sign($this->signer, $this->key);
		return (string)$this->builder->getToken();
	}
	public function decode(string $token) : array{
		$parser = new Parser();
		try{
			$token = $parser->parse($token);
		}catch(Exception $e){
			throw new Exception('Demaged JWT');
		}
		if(!$token->verify($this->signer, $this->key)){
			throw new Exception('JWT signature error');
		}
		return $token->getClaims();
	}
}