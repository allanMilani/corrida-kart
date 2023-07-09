<?php

namespace services;

use models\Corridas;

class MountDiferencaTempoVencedor{
  public static function execute($fileLogId){
    $db = new Corridas();
    
    $dadosCorrida = $db->getDadosCorridas($fileLogId);

    if(empty($dadosCorrida)) return [];

    $data = [];
    foreach($dadosCorrida as $value){
      if(!empty($data[$value['numero_piloto']])){
        $data[$value['numero_piloto']]['tempo_volta'] = date('H:i:s', (strtotime($value['tempo_volta']) + strtotime($data[$value['numero_piloto']]['tempo_volta'])));
        $data[$value['numero_piloto']]['qtd_volta'] += 1;

        continue;
      }
      
      $data[$value['numero_piloto']] = [
        'cod_piloto' => $value['numero_piloto'],
        'piloto' => $value['piloto'],
        'tempo_volta' => $value['tempo_volta'],
        'qtd_volta' => 1,
      ];
    }

    usort($data, function($a, $b){
      return $a['tempo_volta'] > $b['tempo_volta']? 1 : 0;
    });

    for ($i = 1; $i < count($data); $i++) { 
      $data[$i]['diferenca'] = date('H:i:s', (strtotime($data[$i]['tempo_volta']) - strtotime($data[0]['tempo_volta'])));
    }

    return $data;
  }
}