<?php

namespace services;

use models\Corridas;

class MountMelhorVoltaDaCorridaService{
  public static function execute($fileLogId){
    $db = new Corridas();
    
    $dadosCorrida = $db->getDadosMelhorVoltaCorrida($fileLogId);

    if(empty($dadosCorrida)) return [];

    $data = [];
    foreach($dadosCorrida as $value){
      $data[] = [
        'cod_piloto' => $value['numero_piloto'],
        'piloto' => $value['piloto'],
        'tempo_volta' => $value['tempo_volta'],
      ];
    }

    return $data;
  }
}