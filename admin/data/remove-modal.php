<?php
  $conn = $this->conn;
  $dado = $_POST['dado'];

  $del = $conn->query("DELETE FROM $this->table_name WHERE id = $dado");
  if($del === TRUE){
    $msg = "Modal removido!";
  }else{
    $msg = $conn->error;
  }
  $json = json_encode(array("msg"=>$msg));
  echo $json;
  exit();
?>
