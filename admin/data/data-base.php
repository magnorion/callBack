<?php
  // Checa se a tabela dos modais existe!
  $table_name = "callback_modais";
  $conn = $this->conn;
  $check = $conn->query("SELECT * FROM $table_name LIMIT 1");
  if($check === FALSE || $check->num_rows < 1){
    $sql = "CREATE TABLE $table_name (
      id int(6) AUTO_INCREMENT PRIMARY KEY,
      tipo_modal VARCHAR(100) NOT NULL,
      nome VARCHAR(100) NOT NULL,
      cookie VARCHAR(100) NOT NULL,
      inicio VARCHAR(15) NOT NULL,
      saida VARCHAR(15) NOT NULL,
      largura VARCHAR(10) NOT NULL,
      altura VARCHAR(10) NOT NULL,
      imagem VARCHAR(100),
      campos VARCHAR(100),
      youtube VARCHAR(100),
      texto VARCHAR(900),
      chamada VARCHAR(10),
      tempo VARCHAR(10),
      data_criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $create_table = $conn->query($sql);
  }

  // Checa se a tabela dos formulários existe!
  $table_name = "callback_form";
  $conn = $this->conn;
  $check = $conn->query("SELECT * FROM $table_name LIMIT 1");
  if($check === FALSE || $check->num_rows < 1){
    $sql = "CREATE TABLE $table_name (
      id int(6) AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(150) NOT NULL,
      telefone VARCHAR(20),
      Mensagem VARCHAR(400),
      assunto VARCHAR(100),
      email VARCHAR(150),
      curso VARCHAR(150),
      modal_id INT(6) NOT NULL,
      data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $create_table = $conn->query($sql);
  }
?>
