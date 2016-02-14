<?php

namespace Batbox\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

abstract class BaseException extends Exception {

    protected $errors;

    public function __construct( $errors = null, $message = null, $code = 0, Exception $previous = null ) {
        $this->set_errors( $errors );

        parent::__construct( $message, $code, $previous );
    }

    protected function set_errors( $errors ) {
        if ( is_string( $errors ) ) {
            $errors = array(
                'error' => $errors,
            );
        }

        if ( is_array( $errors ) ) {
            $errors = new MessageBag( $errors );
        }

        $this->errors = $errors;
    }

    public function get_errors() {
        return $this->errors;
    }

}