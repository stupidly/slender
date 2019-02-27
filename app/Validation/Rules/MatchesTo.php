<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class MatchesTo extends AbstractRule {

    protected $password;

    public function __construct($password) {
        $this->password = $password;
    }

    public function validate($input) {
        return ($this->password == $input);
    }

}
