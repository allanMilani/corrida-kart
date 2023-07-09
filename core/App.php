<?php

namespace Core;

class App
{
  protected $controller = 'Corridas';
  protected $method = 'index';
  protected $page404 = false;
  protected $params = [];

  public function __construct()
  {
    $URL_ARRAY = $this->parse_url();
    $this->get_controller_from_url($URL_ARRAY);
    $this->get_method_from_url($URL_ARRAY);
    $this->get_params_from_url($URL_ARRAY);
    //invoca o método da classe passando parametros
    call_user_func_array(
      [
        $this->controller,
        $this->method
      ],
      $this->params
    );
  }

  /**
   * Este método pega as informações da URL (ap�s o dominio do site) e retorna esses dados
   * @return array
   */
  private function parse_url()
  {
    $dir = explode(DIRECTORY_SEPARATOR, __DIR__);
    $remover = $dir[(count($dir) - 2)];
    $REQUEST_URI = array_values(array_filter(explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1)), function ($value) use ($remover) {
      return $value !== $remover;
    }));

    return $REQUEST_URI;
  }


  /**
   * Este método verifica se o array informado possui dados na posição 0 (controlador)
   * caso exista, verifica se existe um arquivo com aquele nome no diretório Application/controllers
   * e instancia um objet contido no arquivo, caso contrário a variável $page404 recebe true
   * 
   * @param array $url Array contendo informações ou n�o do controlador, m�todo e par�metro
   */
  private function get_controller_from_url($url)
  {
    if (isset($url[0]) && !empty($url[0])) {
      if (file_exists('controllers/' . ucfirst($url[0]) . '.php')) {
        $this->controller = ucfirst($url[0]);
      } else {
        $this->page404 = true;
      }
    }

    require 'controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller();
  }

  /**
   * Este método verifica se o array informado possui dados na posição 1 (método)
   * case exista, verifica so o método existe naquele determinado controlador
   * e atribui a variável $method da classe.
   * 
   * @param array $url Array contendo informações ou não do controlador, método e parâmetro
   */
  private function get_method_from_url($url)
  {
    if (isset($url[1]) && !empty($url[1])) {
      $method = str_replace(".php", "", explode("?", $url[1], 2)[0]);

      if (empty($method[0])) $method = $this->method;

      $method = explode('-', $method);
      $method = array_map(function($value){
        return ucfirst($value);
      }, $method);
      $method[0] = strtolower($method[0]);
      $method = implode('', $method);

      if (method_exists($this->controller, str_replace(".php", "", $method)) && !$this->page404) {
        $this->method = str_replace(".php", "", $method);
      } else {
        $this->method = 'page_not_found';
      }
    }
  }

  /**
   * Este método verifica se o array informado possui a quantidade de elementos maior que 2
   * ($url[0] é o controller e $url[1] o método/ação a executar), case seja, é atribuido
   * a variável $params da classe um novo array a partir da posição 2 do $url
   */
  private function get_params_from_url($url)
  {
    if (count($url) > 2) {
      $this->params = array_slice($url, 2);
    }
  }
}
