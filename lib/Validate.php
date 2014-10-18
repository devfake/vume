<?php

  namespace vume;

  class Validate {

    /**
     * Stores validation errors.
     */
    private $errors = [];

    /**
     * Validation for form posts.
     *
     * (!) Checks only for required fields, yet.
     */
    public function __construct($validate)
    {
      foreach($validate as $name => $input) {
        if($input === '') {
          $this->errors[$name] = 'The field ' . $name . ' is required.';
        }
      }

      $this->storeErrors();
      $this->saveOldInput();
    }

    /**
     * Save old input in session.
     */
    private function saveOldInput()
    {
      //Only if errors available.
      if($this->errors) {
        foreach($_POST as $name => $data) {
          session('vume_input.' . $name)->set($data);
        }
      }
    }

    /**
     * Save input errors in session.
     */
    private function storeErrors()
    {
      foreach($this->errors as $name => $message) {
        session('vume_errors.' . $name)->set($message);
      }
    }

    /**
     * Return all errors.
     */
    public function errors()
    {
      return $this->errors;
    }

    /**
     * Fails method.
     *
     * Return true if validation has errors.
     */
    public function fails()
    {
      if($this->errors) return true;
    }

    /**
     * Passes method.
     *
     * Return true if validation has no errors.
     */
    public function passes()
    {
      if( ! $this->errors) return true;
    }
  }
