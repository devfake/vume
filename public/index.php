<?php

  /**
   * VUME
   *
   * PHP MVC Framework & Project Boilerplate.
   *
   * @author Viktor Geringer <devfakeplus@googlemail.com>
   * @version 0.3.0
   * @license The MIT License (MIT)
   */

  // Load composer autoloader.
  if(file_exists('../vendor/autoload.php')) require '../vendor/autoload.php';

  // Load applications core.
  require '../lib/Core.php';

  // Start the core.
  $core = new vume\Core();
