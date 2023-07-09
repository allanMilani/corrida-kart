<?php

namespace Core;

use models\Corridas;

class Controller
{
  /**
   * Este método é responsável por chamar uma determinada view (página).
   *
   * @param  string  $model   É o model que será instanciado para usar em uma view, seja seus métodos ou atributos
   */
  public function model($model)
  {
    require 'models/' . $model . '.php';
    $classe = 'models\\' . $model;
    return new $classe();
  }

  /**
   * Este método é responsável por chamar uuma determinada view (página).
   *
   * @param  string  $view   A view que será chamada (ou requerida)
   * @param  array   $data   São os dados que serão exibido na view
   */
  public function view($view, $data = [])
  {
    require 'views/' . $view . '.php';
  }

  /**
   * Este método é herdado para todas as classes filhas que o chamaram quando
   * o método ou classe informada pelo usuário nçao forem encontrados.
   */
  public function page_not_found()
  {
    $this->view('erro404');
  }
}
