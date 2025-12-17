<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('content-type: application/json; charset=utf-8');

include("conn.php");

$sql = "SELECT * FROM usuarios where ativos = 1";
// $sql = "SELECT * FROM usuarios ORDER BY Email";

$res = $conexao->query($sql);

$cadastrados=array();

if($res -> num_rows > 0){
    while($row = $res->fetch_assoc()){

            if (!empty($row['Foto'])) {
            $row['Foto'] = 'data:image/jpeg;base64,' . base64_encode($row['Foto']);
        } else {
            
            $row['Foto'] = 'img/padrao.png';
        }

        $cadastrados[] = $row;
    }
}
echo json_encode($cadastrados, JSON_UNESCAPED_UNICODE);


?>

//callEndpoint  
async function callEndpoint(type = "GET", endpoint, body = {}) {
  let retorno = "";

  try {
    const config = {method: type,
      headers: {
        Authorization: localStorage.getItem("token"),
        User: localStorage.getItem("idUser"),
        "Content-Type": "application/json"
      }
    };

    if (type !== "GET") {
      config.body = JSON.stringify(body);
    }

    const response = await fetch(endpoint, config);
    retorno = await response.json();

    if (retorno.status === false) {
      if (retorno.msg && retorno.msg.includes("Unavailable Token")) {
        await Swal.fire({
          title: "Sessão expirada",
          text: "Faça login novamente",
          icon: "warning",
          timer: 5000
        });

        localStorage.clear();
        location.href = "index.html";
      } else {
        await Swal.fire({
          title: "Erro",
          text: "Ocorreu um erro desconhecido.",
          icon: "error"
        });
      }
    }

    return retorno;

  } catch (error) {
    console.error("Erro no callEndpoint:", error);
    return retorno;
  }
}