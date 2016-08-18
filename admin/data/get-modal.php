<?php
  $conn = $this->conn;
  $table_name = $this->table_name;
  $pagina = $_GET['pagina'];

  $search = $conn->query("SELECT * FROM $table_name WHERE pagina = '$pagina' OR pagina LIKE '%$pagina;%' OR pagina LIKE '%;$pagina%' OR pagina LIKE '%;$pagina;%' LIMIT 1");
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
