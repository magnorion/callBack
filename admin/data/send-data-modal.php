<?php
  $dados = $_POST['dados'];
  $id = $_POST['id'];

  $conn = $this->conn;

  $table_name = "callback_form";

  $fields_to_insert = "";
  $place_to_insert = "";

  foreach($dados as $dado){
    $breaker = explode(":",$dado);
		if($breaker[0] == "E-mail"){
			$breaker[0] = "email";
		}else if($breaker[0] == "Celular"){
			$breaker[0] = "telefone";
		}
		else if($breaker[0] == "confirmacao"){
			continue;
		}
    $place_to_insert .= $breaker[0].",";
    $data_to_escape = $conn->real_escape_string($breaker[1]);
    $fields_to_insert .= "'$data_to_escape',";
  }

  $place_to_insert .= "modal_id";
  $fields_to_insert .= trim("'$id'");

  $insert_all_data = $conn->query("INSERT INTO $table_name (".$place_to_insert.") VALUES (".$fields_to_insert.")");
  if($insert_all_data === TRUE){
    ### Pega os dados que foram inseridos no banco!
    $search_last = $conn->query("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1");
    $array = $search_last->fetch_array();

    ### Pega o nome do modal!
    $id = $array['modal_id'];
    $search_name = $conn->query("SELECT * FROM callback_modais WHERE id = '$id'");
    $array_nome = $search_name->fetch_array();
    $nome_modal = $array_nome['nome'];

    $codigoLead = 23;
    if(preg_match('/\s/',$array['nome']) > 0){
      $params = array(
  	    "fullname"=>$array['nome'],
  	    "emailaddress1"=>$array['email'],
  	    "mobilephone"=>$array['telefone'],
  	    "ucs_assunto"=>$array['assunto'],
  	    "ucs_mensagem"=>$array['mensagem'],
        "ucs_descricaomodal"=>$nome_modal,
  	    "ucs_newsourceleadcode"=>8,
  	    "ucs_origemdoclientepotencialgrupocruzeiroedu"=>$codigoLead
  	  );
    }else{
      $params = array(
        "firstname"=>$array['nome'],
  	    "emailaddress1"=>$array['email'],
  	    "mobilephone"=>$array['telefone'],
  	    "ucs_assunto"=>$array['assunto'],
  	    "ucs_mensagem"=>$array['mensagem'],
        "ucs_descricaomodal"=>$nome_modal,
  	    "ucs_newsourceleadcode"=>8,
  	    "ucs_origemdoclientepotencialgrupocruzeiroedu"=>$codigoLead
  	  );
    }

	  $postdata = http_build_query($params);
	  $opts = array('http' =>
	    array(
	      'method'  => 'POST',
	      'header'  => 'Content-type: application/x-www-form-urlencoded',
	      'content' => $postdata
	    )
	  );
	  $context  = stream_context_create($opts);
	  $result = file_get_contents("http://vestibular.cruzeirodosul.edu.br/Integracao.aspx", false, $context);

    $msg = "Dados enviados!";
  }else{
    $msg = $conn->error;
  }
  $json = json_encode(array("msg"=>$msg));
  echo $json;
  exit();
?>
