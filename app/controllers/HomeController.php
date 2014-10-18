<?php

  class HomeController extends vume\Controller {

    /**
     * Homesite.
     */
    public function index()
    {
      return $this->view('home');
    }
  }
