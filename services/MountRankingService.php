<?php

namespace services;

use models\Corridas;

class MountRankingService{
  public static function execute($fileLogId){
    $db = new Corridas();
    
    $dadosCorrida = $db->getDadosCorridas($fileLogId);

    if(empty($dadosCorrida)) return [];

    $data = [];
    foreach($dadosCorrida as $value){
      if(!empty($data[$value['numero_piloto']])){
        if($value['volta'] == 4){
          usort($data, function($a, $b){
            return $a['tempo_volta'] > $b['tempo_volta']? 1 : 0;
          });

          $data[0]['tempo_volta'] = date('H:i:s', (strtotime($value['tempo_volta']) + strtotime($data[0]['tempo_volta'])));
          $data[0]['qtd_volta'] += 1;
          break;
        }

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

    $colocacao = 1;
    foreach($data as &$value){
      $value['colocacao'] = $colocacao.'º lugar';
      $colocacao++;
    }

    return $data;
  }
}