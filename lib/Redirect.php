<?php

  namespace vume;

  class Redirect {

    /**
     * Redirect to given url.
     */
    public function to($url)
    {
      return header('Location: ' . $url);
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
  }
