<?php
  $conn = $this->conn;
  $table_name = $this->table_name;

  $search = $conn->query("SELECT * FROM $table_name LIMIT 1");
  if($search->num_rows > 0){
    $data = $search->fetch_array();
    $c_cookie = $data['cookie'];
    if(isset($_COOKIE[$c_cookie])){
      $json = json_encode(array("msg"=>"Cookie"));
    }else{
      $json = json_encode($data);
    }
  }else{
    $json = json_encode(array("msg"=>"Não existe modal!"));
  }
  echo $json;
  exit();
?>
