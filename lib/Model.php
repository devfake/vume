<?php

  namespace vume;

  use PDO;

  class Model {

    /**
     * Database layer.
     */
    protected $db = null;

    /**
     * Associated table for model.
     *
     * Overwrite this in a model subclass. Default value is the model subclasses name.
     */
    protected $table = null;

    /**
     * The primary key.
     *
     * Overwrite this in a model subclass. Default value is 'id'.
     */
    protected $primaryKey = 'id';

    /**
     * Create database layer.
     *
     * Enabled only if application is in database mode.
     */
    public function __construct()
    {
      if(DATABASE) {
        $this->db = new PDO(DRIVER . ':host=' . HOST . ';dbname=' . DB, USER, PW, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ]);
      }

      // Is no value for table specified in a subclass, the name of the model is taken.
      if( ! $this->table) {
        $this->table = strtolower(get_class($this));
      }
    }

    /**
     * Return all from model.
     */
    public function all()
    {
      $sql = 'SELECT * FROM ' . $this->table;
      $query = $this->db->prepare($sql);
      $query->execute();

      return $query->fetchAll();
    }

    /**
     * Find data by primary key.
     */
    public function find($id)
    {
      $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id';
      $query = $this->db->prepare($sql);
      $query->execute([':id' => $id]);

      return $query->fetch();
    }

    /**
     * Delete model by primary key.
     */
    public function delete($id)
    {
      $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id';
      $query = $this->db->prepare($sql);

      $query->execute([':id' => $id]);
    }

    /**
     * Get last inserted primary key. Useful for redirect to current created data.
     */
    public function id()
    {
      return $this->db->lastInsertId();
    }
  }
