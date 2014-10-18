<?php

  /**
   * At the moment no logic.
   */
  class RouteNotFoundException extends \Exception {

    public function __construct()
    {
      #$_SESSION['error_message'] = 'No Routing found!';
    }
  }
