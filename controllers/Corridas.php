<?php

use core\Controller;
use services\ImportLogService;
use services\MountRankingService;
use services\MountMelhorTempoPorPilotoService;
use services\MountMelhorVoltaDaCorridaService;
use services\MountVelocidadeMediaService;
use services\MountDiferencaTempoVencedor;

class Corridas extends Controller{
  private $db;

  public function __construct(){
    $this->db = $this->model('Corridas');
  }

  public function index(){
    $this->view('index');
  }

  public function importLog(){
    // ob_clean();
    // header_remove();
    // header('Content-Type: application/json; charset=utf-8');

    if(empty($_FILES['log']['tmp_name'])){
      http_response_code(400);
      echo json_encode(array(
        'status' => 400,
        'message' => 'Por favor informe o arquivo de log'
      ));
      exit;
    }

    $dados = file($_FILES['log']['tmp_name']);

    if(empty($dados)){
      http_response_code(400);
      echo json_encode(array(
        'status' => 400,
        'message' => 'O arquivo informado está vazio'
      ));
      exit;
    }

    try{
      $fileLogId = $this->db->insertFileLog($_FILES['log']['name']);
      ImportLogService::execute($dados, $fileLogId);

      $moutRanking = MountRankingService::execute($fileLogId);
      $mountMelhorTempoPorPiloto = MountMelhorTempoPorPilotoService::execute($fileLogId);
      $mountMelhorVoltaDaCorrida = MountMelhorVoltaDaCorridaService::execute($fileLogId);
      $mountVelocidadeMedia = MountVelocidadeMediaService::execute($fileLogId);
      $mountDiferencaTempoVencedor = MountDiferencaTempoVencedor::execute($fileLogId);

      http_response_code(200);
      echo json_encode(array(
        'status' => 200,
        'message' => 'Arquivo processado com sucesso',
        'raking' => array_values($moutRanking),
        'melhor_volta_por_piloto' => $mountMelhorTempoPorPiloto,
        'melhor_volta_corrida' => $mountMelhorVoltaDaCorrida,
        'velocidade_media' => $mountVelocidadeMedia,
        'diferenca_tempo' => $mountDiferencaTempoVencedor,
      ));
      exit;
    } catch(\Throwable $e){
      http_response_code(500);
      echo json_encode(array(
        'status' => 500,
        'message' => $e->getMessage()
      ));
      exit;
    }
  }

}