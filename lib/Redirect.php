<?php

  namespace vume;

  class Redirect {

    /**
     * Redirect to given url.
     */
    public function to($url)
    {
      return header('Location: ' . $this->validateURL($url));
    }

    /**
     * Redirect back to previous url.
     *
     * As default redirect to application url.
     */
    public function back()
    {
      if( ! session('vume_url')->exists()) {
        return header('Location: ' . URL);
      }

      return header('Location: ' . URL . session('vume_url')->get());
    }

    /**
     * Make the url valid if required.
     */
    private function validateURL($url)
    {
      if( ! preg_match("@^http|s?://@", $url)) {
        $url = 'http://' . $url;
      }

      if( ! preg_match("#^(http(s?):\/\/)?(((www\.)?+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3}))|(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b))|localhost|(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$#", $url)) {
        //throw new \NoValidURLException();
        echo $url . ' is not a valid url.';
        exit;
      }

      return $url;
    }
  }
