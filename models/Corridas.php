<?php

namespace models;

use core\Database;
use Exception;
use PDO;
use PDOException;

class Corridas {
  public function insertLogCorrida($hora, $numPiloto, $piloto, $volta, $tempoVolta, $velocidadeMedia, $fileLogId){
    try{
      $conn = new Database();
      $result = $conn->execute_query(
        'INSERT INTO log_corridas (
            hora, 
            numero_piloto, 
            piloto, 
            volta, 
            tempo_volta, 
            velocidade_media,
            file_log_id
          ) VALUES (
            :HORA, 
            :NUMEROPILOTO, 
            :PILOTO, 
            :VOLTA, 
            :TEMPO, 
            :VELOCIDADE,
            :FILELOGID
          )',
        [
          ':HORA'         => $hora,
          ':NUMEROPILOTO' => $numPiloto,
          ':PILOTO'       => $piloto,
          ':VOLTA'        => $volta,
          ':TEMPO'        => $tempoVolta,
          ':VELOCIDADE'   => $velocidadeMedia,
          ':FILELOGID'    => $fileLogId
        ]
      );

      return $result->rowCount();
    } catch(PDOException $e){
      throw new Exception($e->getMessage(), 1);
    }
  }

  public function insertFileLog($nome){
    try{
      $conn = new Database();
      $result = $conn->return_last_id(
        'INSERT INTO file_logs (arquivo) VALUE (:ARQUIVO)',
        [
          ':ARQUIVO' => $nome
        ]
      );

      return $result;
    } catch(PDOException $e){
      throw new Exception($e->getMessage(), 1);
    }
  }

  public function getDadosCorridas($fileLogId){
    try{
      $conn = new Database();
      $result = $conn->execute_query(
        '
          SELECT 
            * 
          FROM
            log_corridas
          WHERE
            file_log_id = :FILELOGID
          ORDER BY
            volta, tempo_volta
        ',
        [
          ':FILELOGID' => $fileLogId
        ]
      );

      return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
      throw new Exception($e->getMessage(), 1);
    }
  }

  public function getDadosMelhorVoltaPorPilotoCorridas($fileLogId){
    try{
      $conn = new Database();
      $result = $conn->execute_query(
        '
          SELECT 
            lc1.piloto,
            lc1.volta,
            lc1.tempo_volta,
            lc1.numero_piloto
          FROM
            log_corridas lc1
            INNER JOIN (
              SELECT 
                MIN(tempo_volta) as tempo_volta,
                numero_piloto
              FROM
                log_corridas
              GROUP BY 
                numero_piloto
            ) lc2 ON lc2.tempo_volta = lc1.tempo_volta AND lc2.numero_piloto = lc1.numero_piloto
          WHERE
            lc1.file_log_id = :FILELOGID
          GROUP BY
            lc1.numero_piloto
        ',
        [
          ':FILELOGID' => $fileLogId
        ]
      );

      return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
      throw new Exception($e->getMessage(), 1);
    }
  }

  public function getDadosMelhorVoltaCorrida($fileLogId){
    try{
      $conn = new Database();
      $result = $conn->execute_query(
        '
          SELECT 
            lc1.piloto,
            lc1.volta,
            lc1.tempo_volta,
            lc1.numero_piloto
          FROM
            log_corridas lc1
            INNER JOIN (
              SELECT 
                MIN(tempo_volta) as tempo_volta,
                numero_piloto
              FROM
                log_corridas
            ) lc2 ON lc2.tempo_volta = lc1.tempo_volta AND lc2.numero_piloto = lc1.numero_piloto
          WHERE
            lc1.file_log_id = :FILELOGID
          GROUP BY
            lc1.numero_piloto
          LIMIT 1
        ',
        [
          ':FILELOGID' => $fileLogId
        ]
      );

      return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
      throw new Exception($e->getMessage(), 1);
    }
  }

}