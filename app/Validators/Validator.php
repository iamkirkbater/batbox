<?php

namespace Batbox\Validators;

use Illuminate\Validation\Factory as ValidationFactory;
use Batbox\Exceptions\ValidationException;

abstract class Validator {

    /**
     * @var ValidationFactory
     */
    protected $validator;

    public function __construct( ValidationFactory $validator )
    {
        $this->validator = $validator;
    }

    public function validate( array $data, array $rules = array(), array $custom_errors = array() ) {
        if ( empty( $rules ) && ! empty( $this->rules ) && is_array( $this->rules ) ) {
            //no rules passed to function, use the default rules defined in sub-class
            $rules = $this->rules;
        }

        if ( empty( $custom_errors ) && ! empty( $this->errors ) && is_array( $this->errors ) ) {
            //no rules passed to function, use the default rules defined in sub-class
            $custom_errors = $this->errors;
        }

        //use Laravel's Validator and validate the data
        $validation = $this->validator->make( $data, $rules, $custom_errors );

        if ( $validation->fails() ) {
            throw new ValidationException( $validation->messages() );
        }

        return true;
    }
}