<?php
  $conn = $CallBack->conn;
  $search = $conn->query("SELECT id,nome,tipo_modal,campos,inicio,saida FROM $CallBack->table_name");
  $counter = $search->num_rows;
  $data = array();
  for($i=1;$i<=$counter;$i++){
    array_push($data,$search->fetch_array());
  }
?>
<h1> CallBack </h1>
<h2> Todos os modais! </h2>
<table id="all_modals" class="display" cellspacing="0">
  <thead>
    <tr>
        <th>Nome Modal</th>
        <th>Tipo</th>
        <th>Captação?</th>
        <th>Data Criado</th>
        <th>Data Saida</th>
        <th>Ação</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($data as $key => $values){
        echo "<tr>";
        echo "<td>".$values[1]."</td>";
        echo "<td>".$values[2]."</td>";
        if($values[3] == ""){
          echo "<td>Não</td>";
        }else{
          echo "<td>Sim</td>";
        }
        echo "<td>".$values[4]."</td>";
        echo "<td>".$values[5]."</td>";
          echo '<td>';
            //echo '<span><a href="#" data-edit="'.$values[0].'" class="edit-modal"><i class="fa fa-pencil"></i> Editar</a></span> &nbsp;';
            echo '<span><a href="#" data-catch="'.$values[0].'" class="catch-modal"><i class="fa fa-file-excel-o"></i> Pegar os dados</a></span> &nbsp;';
            echo '<span id="remove-modal"><a href="#" data-remove="'.$values[0].'" class="remove-modal"><i class="fa fa-trash"></i> Remover</a></span>';
          echo '</td>';
        echo "</tr>";
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th>Nome Modal</th>
      <th>Tipo</th>
      <th>Captação?</th>
      <th>Data Criado</th>
      <th>Data Saida</th>
      <th>Ação</th>
    </tr>
  </tfoot>
</table>
