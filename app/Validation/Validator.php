<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator {

    protected $errors;
    protected $messages;

    public function __construct() {
        $this->messages = require_once(__DIR__ . "/messages.php");
    }

    public function validate($request, array $rules) {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $e->findMessages($this->messages);
                $this->errors[$field] = $e->getMessages();
            }
        }


        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function validateField($field, $rule) {
        try {
            $rule->assert($field);
        } catch (NestedValidationException $e) {
            $messages = $e->findMessages($this->messages);
            if ($messages) {
                throw new \Exception(implode(" ", $messages));
            }
            throw new \Exception(implode(" ", $e->getMessages()));
        }
    }

    public function failed() {
        return !empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getErrorString() {
        $errors = [];
        foreach ($this->errors as $field => $messages) {
            $errors[] = implode("|", $messages);
        }
        return implode("|", $errors);
    }

}
