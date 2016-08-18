<?php
  ### Recebe os dados
  $dados = $_POST['dados'];

  $tipo_modal = $dados['tipo_modal'];
  $nome = $dados['nome'];
  $cookie = $dados['cookie'];
  $inicio = $dados['inicio'];
  $saida = $dados['saida'];
  $largura = $dados['largura'];
  $altura = $dados['altura'];
  $imagem = $dados['imagem'];
  $campos = $dados['campos'];
  if(empty($campos)){
    $campos = "";
  }else{
    $campos = implode(" ",$dados['campos']);
  }

  $youtube = $dados['youtube'];
  $texto = $dados['texto'];
  $chamada = $dados['chamada'];
  $tempo = $dados['tempo'];
  $pagina = $dados['pagina'];

  $data_to_insert = "'$tipo_modal','$nome','$cookie','$inicio','$saida','$largura','$altura','$imagem','$campos','$youtube','$texto','$chamada','$tempo','$pagina'";

  $place_to_insert = "tipo_modal, nome, cookie, inicio, saida, largura, altura, imagem, campos, youtube, texto, chamada, tempo, pagina";
  $conn = $this->conn;
  $create_modal_data = $conn->query("INSERT INTO callback_modais(".$place_to_insert.") VALUES (".$data_to_insert.")");
  if($create_modal_data === TRUE){
    $msg = "Dados enviados!";
  }else{
    $msg = $conn->error;
  }
  $json = json_encode(array("msg"=>$msg));
  echo $json;
  exit();
?>
