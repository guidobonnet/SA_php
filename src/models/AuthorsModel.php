<?php

namespace Models;

class AuthorsModel {
  
  /* DB Table structure
   * CREATE TABLE `authors` (
   *   `id` int(11) unsigned NOT NULL,
   *   `name` varchar(255) NOT NULL
   * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
   */
  
  protected $db;
  public function __construct($db)
  {
    $this->db = $db;
  }
  
  /* show all authors */
  public function index() {
    $sql = "SELECT id, name FROM authors";
    return $this->db->fetchAll($sql);
  }
  
  /* create an author */
  public function create($name) {
    $sql = "INSERT INTO authors (name) VALUES (?)";
    return $this->db->executeQuery($sql, array($name));
  }
  
  /* read a single author */
  public function read($id) {
    $sql = "SELECT authors.* FROM authors WHERE id = ?";
    return $this->db->fetchAssoc($sql, array($id));
  }
  
  /* update a single author */
  public function update($id, $name) {
    $sql = "UPDATE authors SET name = ? WHERE id = ?";
    return $this->db->executeUpdate($sql, array($name, $id));
  }
  
  /* delete a single author */
  public function delete($id) {
    $sql = "DELETE FROM authors WHERE id = ?";
    return $this->db->executeQuery($sql, array($id));
  }
}