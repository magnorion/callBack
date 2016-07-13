<?php
  $dados = $_POST['dados'];
  $id = $_POST['id'];

  $conn = $this->conn;
  $table_name = "callback_form";

  $fields_to_insert = "";
  $place_to_insert = "";

  foreach($dados as $dado){
    $breaker = explode(":",$dado);

    $place_to_insert .= $breaker[0].",";
    $data_to_escape = $conn->real_escape_string($breaker[1]);
    $fields_to_insert .= "'$data_to_escape',";
  }
  $place_to_insert .= "modal_id";
  $fields_to_insert .= trim("'$id'");

  $insert_all_data = $conn->query("INSERT INTO $table_name (".$place_to_insert.") VALUES (".$fields_to_insert.")");
  if($insert_all_data === TRUE){
    $msg = "Dados enviados!";
  }else{
    $msg = $conn->error;
  }
  $json = json_encode(array("msg"=>$msg));
  echo $json;
  exit();
?>
