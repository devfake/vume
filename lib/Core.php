<?php

  namespace vume;

  class Core {

    /**
     * Stores the current environment for the database.
     */
    private $environment;

    /**
     * Start the complete logic.
     *
     * Adding ob_start() and ob_end_flush() for allow header redirection after outputs.
     */
    public function __construct()
    {
      ob_start();

      $this->scanConfigDir();
      $this->phpErrors();
      $this->autoloadModels();
      // $this->autoloadExceptions();

      require 'Session.php';
      require 'Helpers.php';
      require 'View.php';
      require 'Redirect.php';
      require 'Validate.php';
      require 'Controller.php';
      require 'Model.php';
      require 'Route.php';

      // Save current uri for redirect()->back() method.
      session('vume_url')->set($this->getURI());

      $route = new Route();
      require '../app/routes.php';
      $route->run();

      ob_end_flush();
    }

    /**
     * Scan the config folder and call setConfigs() method to define them as constants.
     */
    private function scanConfigDir()
    {
      $configFiles = array_slice(scandir('../config/'), 2);
      if(file_exists('../config/config.ini')) {
        $configIni = parse_ini_file('../config/config.ini');
      }

      foreach($configFiles as $configFile) {
        if($configFile == 'config.ini') continue;

        $config = require '../config/' . $configFile;
        $this->setConfigs($config, $configFile);
      }
    }

    /**
     * Detect current URL and return the URI.
     */
    private function getURI()
    {
      $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . trim($_SERVER['REQUEST_URI'], '/');

      return str_replace(URL, '', $url);
    }

    /**
     * Define configs as constants.
     *
     * Use they case insensitive.
     */
    private function setConfigs(array $configs, $configFile = null)
    {
      foreach($configs as $key => $value) {
        if($configFile == 'database.php') {
          if($key == $this->environment) $this->setConfigs($value);
        } else {
          if($key == 'environment') $this->environment = $value;
          if(is_array($value)) {
            $this->setConfigs($value);
            continue;
          }
          define($key, $value, true);
        }
      }
    }

    /**
     * Handle php errors.
     *
     * Enabled only in debug mode.
     */
    private function phpErrors()
    {
      if(DEBUG) {
        error_reporting(E_ALL);
        ini_set('log_errors', 'off');
        ini_set('display_errors', '1');
      } else {
        error_reporting(0);
        ini_set('log_errors', 'on');

        // Todo: Make it work!
        // ini_set('error_log', 'errors/error.log');

        ini_set('display_errors', '-1');
      }
    }

    /**
     * Autoloader for models.
     */
    private function autoloadModels()
    {
      spl_autoload_register(function($model) {
        require model_path . $model . '.php';
      });
    }

    /**
     * Autoloader for exceptions.
     */
    private function autoloadExceptions()
    {
      // Todo: Make it work for multiple autoloader.
    }
  }
