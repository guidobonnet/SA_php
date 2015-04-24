<?php

namespace Models;

class PostsModel {
  
  /* DB Table structure
   * CREATE TABLE `posts` (
   *   `id` int(11) unsigned NOT NULL,
   *   `title` varchar(255) NOT NULL,
   *   `message` text NOT NULL,
   *   `author` int(11) unsigned NOT NULL
   * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
   */
  
  protected $db;
  public function __construct($db)
  {
    $this->db = $db;
  }
  
  /* show all the posts */
  public function index($author = false) {
    if($author) {
      $sql = "SELECT id, title FROM posts WHERE author = ?";
      return $this->db->fetchAll($sql, array($author));
    } else {
      $sql = "SELECT id, title FROM posts";
      return $this->db->fetchAll($sql);
    }
  }
  
  /* create a post */
  public function create($title, $message, $author) {
    $sql = "INSERT INTO posts (title, message, author) VALUES (?, ?, ?)";
    return $this->db->executeQuery($sql, array($title, $message, $author));
  }
  
  /* read a single post */
  public function read($id) {
    $sql = "SELECT posts.*, authors.name as author_name FROM posts LEFT JOIN authors ON authors.id = posts.author WHERE posts.id = ?";
    return $this->db->fetchAssoc($sql, array($id));
  }
  
  /* update a single post */
  public function update($id, $title, $message, $author) {
    $sql = "UPDATE posts SET title = ?, message = ?, author = ? WHERE id = ?";
    return $this->db->executeUpdate($sql, array($title, $message, $author, $id));
  }
  
  /* delete a singe post */
  public function delete($id) {
    $sql = "DELETE FROM posts WHERE id = ?";
    return $this->db->executeQuery($sql, array($id));
  }
}