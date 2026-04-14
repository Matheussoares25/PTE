<?php
require_once __DIR__ . "/../models/Ranking.php";

class RankingController{

   private $rank;

   public function __construct(){
    $this->rank = new ranking();
   }

   public function buscar(){

   $res = $this->rank->ranking();

   echo json_encode($res);
   }
}