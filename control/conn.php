<?php
class Conexao{
    private $host = "localhost";
    private $usuario = "root";
    private $senha = "123456";
    Private $banco = "PTE";
    public $conn;

    public function __construct(){
        try{
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->banco, $this->usuario, $this->senha);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
        }catch(PDOException $e){
            echo "Erro na conexão: " . $e->getMessage();
        }
    }
}
  

?>
