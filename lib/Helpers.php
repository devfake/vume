<?php

  /**
   * Check if request is ajax.
   */
  function ajax()
  {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }

  /**
   * Return formatting print_r.
   */
  function pp($content)
  {
    echo '<pre>';
    print_r($content);
    echo '</pre>';
  }

  /**
   * Save debug message in session. Only in debug mode.
   */
  function debug($message)
  {
    if(DEBUG) {
      #session('message', $message);
    }
  }

  /**
   * Automatic cache-busting files.
   */
  function autoCache($file)
  {
    $timestamp = filemtime(public_path . $file);

    return URL . $file . '?v=' . $timestamp;
  }

  /**
   * Alias for compact().
   */
  function c($name)
  {
    return compact($name);
  }

  /**
   * Alias for $_POST[].
   */
  function input($input = null, $escape = true)
  {
    if($input) {
      if($escape) {
        return htmlspecialchars($_POST[$input], ENT_QUOTES, 'UTF-8');
      }
      return $_POST[$input];
    }

    return $_POST;
  }

  /**
   * Alias for redirect()->to().
   */
  function to($url)
  {
    $redirect = new \vume\Redirect();

    return $redirect->to($url);
  }

  /**
   * Alias for redirect()->to().
   */
  function back()
  {
    $redirect = new \vume\Redirect();

    return $redirect->back();
  }

  /**
   * Return input error message.
   */
  function error($name)
  {
    if(session('vume_errors.' . $name)->exists()) {
      return '<span class="form-error">' . session('vume_errors.' . $name)->message() . '</span>';
    }
  }

  /**
   * Return old input. Useful when the user is redirected back.
   */
  function inputOld($name, $default = null)
  {
    if(session('vume_input.' . $name)->exists()) {
      return session('vume_input.' . $name)->message();
    }

    return $default;
  }

  /**
   * Alias for $_SESSION[].
   *
   * https://github.com/devfake/Readable-Session-Helper
   */
  function session($keys = null)
  {
    return new \vume\Session($keys, '.');
  }
