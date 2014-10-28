<?php

  namespace vume;

  class Route {

    /**
     * Store all routes.
     */
    private $routes = ['get' => [], 'post' => []];

    /**
     * Store all parameters.
     */
    private $parameters = [];

    /**
     * Requestet uri.
     */
    private $uri;

    /**
     * Store possible matched parameters from regex.
     */
    private $matches;

    /**
     * Unleash and run the router.
     */
    public function run()
    {
      $this->uri = isset($_GET['url']) ? '/' . $_GET['url'] : '/';

      foreach($this->routes[$this->requestMethod()] as $pattern => $callback) {

        if( ! $this->isClosure($callback)) {
          list($controller, $action) = explode('@', $callback);
        }

        $pattern = '/' . trim($pattern, '/');

        if($this->match($pattern)) {
          if( ! $this->isClosure($callback)) {
            require controller_path . $controller . '.php';
            $controller = new $controller();

            // Remove the element for complete uri.
            array_shift($this->matches);

            // Store possible matched parameter in a flat array
            // for call it in call_user_func_array().F
            foreach($this->matches as $params => $value) {
              $this->parameters[] = $value[0];
            }

            return call_user_func_array([$controller, $action], $this->parameters);
          }

          // Return the anonymous function.
          return $callback(new Controller());
        }
      }

      //throw new \RouteNotFoundException();
      echo 'Route not found.';
    }

    /**
     * Match the URI against the {pattern}.
     */
    private function match($pattern)
    {
      $pattern = preg_replace('#\{[^/-]+\}#', '(.+)', $pattern);

      return preg_match_all('#^' . $pattern . '$#', $this->uri, $this->matches);
    }

    /**
     * Add routes for get requests.
     */
    public function get($pattern, $callback)
    {
      $this->routes['get'][$pattern] = $callback;
    }

    /**
     * Add routes for post requests.
     */
    public function post($pattern, $callback)
    {
      $this->routes['post'][$pattern] = $callback;
    }

    /**
     * Make resource routes.
     */
    public function resource($pattern, $callback = null)
    {
      $pattern = trim($pattern, '/');
      $callback = $callback ?: ucfirst($pattern) . 'Controller';

      $this->get($pattern, $callback . '@index');
      $this->get($pattern . '/new', $callback . '@create');
      $this->get($pattern . '/{id}/edit', $callback . '@change');
      $this->get($pattern . '/{id}', $callback . '@show');

      $this->post($pattern, $callback . '@store');
      $this->post($pattern . '/{id}/delete', $callback . '@destroy');
      $this->post($pattern . '/{id}', $callback . '@update');
    }

    /**
     * Return the routes.
     */
    public function getRoutes($requestMethod = null)
    {
      return $requestMethod ? $this->routes[$requestMethod] : $this->routes;
    }

    /**
     * Return the request Method. Possible 'get' or 'post'.
     */
    private function requestMethod()
    {
      return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if callback is a closure.
     */
    private function isClosure($callback) {
      return is_object($callback);
    }
  }
