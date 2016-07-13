<?php
  $conn = $this->conn;
  $table_name = "callback_form";
  $id = $_GET['dados'];

  $search = $conn->query("SELECT * FROM $table_name WHERE modal_id = $id");
  $counter = $search->num_rows;

  if($counter < 1){
    $msg = "Este modal não possui leads!";
  }else{
    $head = "Nome;Telefone;Mensagem;Assunto;Email;Curso;Data;Horario\n";
    $rows = "";

    ### Gera o arquivo
    $search_name = $conn->query("SELECT * FROM $this->table_name WHERE id = '$id' LIMIT 1");
    $dado = $search_name->fetch_array();
    $nome = $dado['nome'];

    ### Nome do arquivo ---
    $title = $dado['cookie']."_".date("Ymd");
    $file = __DIR__."/leads/".$title.".csv";
    $file_url = $title.".csv";

    for($i=1;$i<=$counter;$i++){
      $array = $search->fetch_array();
      $breaker = explode(" ",$array['data_cadastro']);
      $data = date("d/m/Y",strtotime($breaker[0]));
      $hora = $breaker[1];

      $rows .= $array['nome'].";".$array['telefone'].";".$array['mensagem'].";".$array['assunto'].";".$array['email'].";".$array['curso'].";".$data.";".$hora."\n";
    }

    ### Se o arquivo existe, ele será apagado!
    if(file_exists($file)){
      unlink($file);
    }

    ### Cria o arquivo e o edita!
    $fopen = fopen($file,"w+");
    fwrite($fopen,$head);
    fwrite($fopen,$rows);
    fclose($fopen);
    $msg = plugin_dir_url(__DIR__)."data/leads/".$file_url;
  }
  $json = json_encode(array("msg"=>$msg));
  echo $json;
  exit();
?>
