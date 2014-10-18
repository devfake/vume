<?php

  namespace vume;

  class Controller {

    /**
     * Save view path in session.
     *
     * You can use paths with dotnotation -> views.home or views/home.
     * Return views/home.php as default.
     */
    protected function view($view)
    {
      $view = str_replace('.', '/', $view);
      $viewPath = view_path . $view . '.php';

      if( ! file_exists($viewPath)) {
        //return new \ViewNotFoundException();
        echo 'View <b>' . $view . '</b> not found.';
        return;
      }

      // Transport view path over session.
      session('vume_view')->set($viewPath);

      return new View();
    }

    /**
     * Share same data for all views.
     */
    protected function share($name, $data = null)
    {
      session('vume_share.' . $name)->set($data);
    }

    /**
     * Validation for form posts.
     */
    protected function validate($input)
    {
      return new Validate($input);
    }

    /**
     * Redirect to given url.
     */
    protected function redirect()
    {
      return new Redirect();
    }
  }
