<?php

  namespace vume;

  class View {

    /**
     * Store all data for views.
     */
    private $data = [];

    /**
     * Add data to view.
     *
     * Todo: compact() and array for first parameter.
     */
    public function with($name, $data)
    {
      $this->data[$name] = $data;

      return $this;
    }

    /**
     * Iterate over datas (shared too) and include the master view template.
     */
    public function __destruct()
    {
      foreach($this->data as $name => $data) {
        $$name = $data;
      }

      if(session('vume_share')->exists()) {
        foreach(session('vume_share')->get() as $name => $data) {
          $$name = $data;
        }
      }

      require view_path . 'master.php';

      session('vume_share')->remove();
    }
  }
