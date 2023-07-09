<?php

namespace services;

use models\Corridas;

class ImportLogService{
  public static function execute($data, $fileLogId){
    $db = new Corridas();

    foreach($data as $key => $value){
      if($key <= 0) continue;

      $linha = explode(' ', str_replace(' – ', ' ', $value));
      $db->insertLogCorrida(
        (!empty($linha[0])? $linha[0] : null),
        (!empty($linha[1])? (int)$linha[1] : null),
        (!empty($linha[2])? $linha[2] : null),
        (!empty($linha[3])? $linha[3] : null),
        (!empty($linha[4])? $linha[4] : null),
        (!empty($linha[5])? str_replace(',', '.', $linha[5]) : null),
        $fileLogId
      );
    }
  }
}