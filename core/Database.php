<?php

namespace Core;

use PDO;

class Database extends PDO
{
  private $name = 'corridas';
  private $user = 'root';
  private $password = 'root';
  private $host = 'mysql';

  private $conn;

  public function __construct()
  {
    $this->conn = new PDO("mysql:host=$this->host;dbname=$this->name;", $this->user, $this->password);
  }


  /*
  * Este metodo recebe a query para preparar a execução (Prevenção de SQL Injection)
  * @param  PDOStatement  $stmt   Contém a query ja 'preparada'.
  * @param  string        $key    É a mesma chave informada na query.
  * @param  string        $value  Valor de uma determinada chave.
  */

  private function set_parameters($stmt, $key, $value)
  {
    if (in_array($key, [':OFFSET', ':LIMIT'])) {
      $stmt->bindParam($key, $value, PDO::PARAM_INT);
    } else {
      $stmt->bindParam($key, $value);
    }
  }

  /*
  * Percorre o array de elemento para adicionar o paramentro na query
  * @param  PDOStatement  $stmt         Contém a query ja 'preparada'.
  * @param  array         $parameters   Array associativo contendo chave e valores para fornece a query
  */
  private function mount_query($stmt, $parameters)
  {
    foreach ($parameters as $key => $value) {
      $this->set_parameters($stmt, $key, $value);
    }
  }

  /**
   * Executa a query no banco
   * @param  string   $query       Instrução SQL que será executada no banco de dados.
   * @param  array    $parameters  Array associativo contendo as chaves informada na query e seus respectivos valores
   *
   * @return PDOStatement
   */
  public function execute_query($query, $parameters = [])
  {
    $stmt = $this->conn->prepare($query);
    $this->mount_query($stmt, $parameters);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Executa a query no banco
   * @param  string   $query       Instrução SQL que será executada no banco de dados.
   * @param  array    $parameters  Array associativo contendo as chaves informada na query e seus respectivos valores
   *
   * @return PDOStatement
   */
  public function return_last_id($query, $parameters = []){
    $stmt = $this->conn->prepare($query);
    $this->mount_query($stmt, $parameters);
    $stmt->execute();
    return $this->conn->lastInsertId();
  }

}
