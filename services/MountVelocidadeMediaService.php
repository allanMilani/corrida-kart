<?php

namespace services;

use models\Corridas;

class MountVelocidadeMediaService{
  public static function execute($fileLogId){
    $db = new Corridas();
    
    $dadosCorrida = $db->getDadosCorridas($fileLogId);

    if(empty($dadosCorrida)) return [];

    $data = [];
    foreach($dadosCorrida as $value){
      if(!empty($data[$value['numero_piloto']])){
        $data[$value['numero_piloto']]['velocidade_media'] += $value['velocidade_media'];
        $data[$value['numero_piloto']]['qtd_volta'] += 1;
        continue;
      }

      $data[$value['numero_piloto']] = [
        'cod_piloto' => $value['numero_piloto'],
        'piloto' => $value['piloto'],
        'velocidade_media' => $value['velocidade_media'],
        'qtd_volta' => 1,
      ];
    }

    foreach($data as &$value){
      $value['velocidade_media'] = number_format(($value['velocidade_media'] / $value['qtd_volta']), 3, ',', '.');
    }

    return $data;
  }
}