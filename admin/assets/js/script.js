(function($){
  $(document).ready(function(){
    // Página Inicial ###########################################################
    //Tabela com os modais
    if($("#all_modals").length > 0){
      $("#all_modals").DataTable({
        "language": {
              "lengthMenu": "Mostrando _MENU_ modais por página",
              "zeroRecords": "Nenhum modal encontrado!",
              "info": "Mostrando _PAGE_ de _PAGES_",
              "infoEmpty": "Nenhum modal encontrado!",
              "loadingRecords": "Carregando...",
              "processing":     "Processando...",
              "search":         "Buscar:",
              "paginate": {
                  "first":      "Primeiro",
                  "last":       "Último",
                  "next":       "Próximo",
                  "previous":   "Anterior"
              },
              "infoFiltered": "(Filtrado _MAX_)"
          }
      });

      // Remover modal!
      $(".remove-modal").on("click",function(){
        var remove_data = $(this).data("remove");
        var confir = confirm("Deseja remover este modal?");
        var row = $(this).parents("tr");
        if(confir){
          $.ajax({
            url:ajaxurl,
            method:"POST",
            data:{
              action:"remove_modal",
              dado:remove_data
            }
          }).done(function(data){
            var json = JSON.parse(data);
            if(json.msg == "Modal removido!"){
              row.animate({opacity:0},500,function(){
                row.remove();
              });
            }else{
              console.log(json);
            }
          });
        }
      });

      // Pegar os dados!
      $(".catch-modal").on("click",function(e){
        e.preventDefault();
        var self = $(this);
        var id = self.data("catch");
        $.ajax({
          url:ajaxurl,
          method:"GET",
          data:{
            action: "catch_leads_modal",
            dados:id
          }
        }).done(function(data){
          var json = JSON.parse(data);
          if(json.msg != "Este modal não possui leads!"){
            var url = json.msg;
            window.location.assign(url);
          }else{
            alert(json.msg);
          }
        });
      });
    }
    // Página de Criação/Edição de modal ############################################
    if($("#callBack_admin_body").length > 0){
      // Editor
      CKEDITOR.replace('editor');
      // Calendario
      $(".date-picker").datepicker({dateFormat:'dd/mm/yy'});

      // Ajuste no campo da imagem
      $("input[name='imagem']").val("");

      // Botão que chama o uploader
      $("#get_image").on("click",function(e){
        e.preventDefault();
        file_frame = wp.media.frames.file_frame = wp.media({
          title: $( this ).data( 'uploader_title' ),
          button: {
            text: $( this ).data( 'uploader_button_text' ),
          },
          multiple: false
        });
        file_frame.on( 'select', function() {
          attachment = file_frame.state().get('selection').first().toJSON();
          $("input[name='imagem']").val("").val(attachment.url);
        });
        file_frame.open();
      });

      // Criar um novo Campo para captação
      $(".body-left p select").on("change",function(){
        var value = $(this).val();
        if(value == "Sim"){
          $("#captacao_build").css({display:"block"}).animate({opacity:1},400);
        }else{
          $("#captacao_build").animate({opacity:0},400,function(){
            $("#captacao_build").css({display:"none"});
          });
        }
      });
      $("#new_input").on("click",function(e){
        e.preventDefault();
        var dialog = $("div.ui-dialog.ui-widget.ui-widget-content.ui-corner-all.ui-front.ui-draggable.ui-resizable");

        $( "#dialog-form" ).dialog({
          height: 300,
          width: 350,
          modal: true,
          overlay:{backgroundColor:"#000",opacity:0.7},
          resizable: false,
          close: function() {

          }
        });
      });
      // *** Validação dos campos de criação
      $("#new_field_send").on("click",function(e){
        e.preventDefault();
        var form = $("#new_field_form");
        form.find("input[type='text'],select").each(function(){
          if($(this).val() == ""){
            $(this).addClass("input-error-modal").focus();
          }else{
            $(this).removeClass("input-error-modal");
          }
        });
        if($(".input-error-modal").length > 0) return false;
        var campos = {
          nome: form.find("input[name='name']").val(),
          tipo: form.find("select").val()
        };

        $("#captacao_build table").append("<tr class='field-row'><td class='nome'>"+campos.nome+"</td> <td class='tipo'>"+campos.tipo+"</td><td class='remove'> <span><i class='fa fa-trash'></i> Remover Campo</span> </td></tr>");
        $( "#dialog-form" ).dialog("close");
      });
      // *** remove um campo que foi criado
      $("#captacao_build table").on("click","tr td.remove span",function(){
        var self = $(this);
        self.parents("tr").animate({opacity:0},1000,function(){
          self.parents("tr").remove();
        });
      });

      // Se selecionar a chamada por tempo
      $("select[name='chamada']").on("change",function(){
        var value = $(this).val();
        $("#input_time input").val("");
        if(value == "by_time"){
          $("#input_time").css("display","block").animate({opacity:1},1000);
        }else{
          $("#input_time").css({"display":"none","opacity":0});
        }
      });

      // Validação e envio de dados de criação do modal!
      $("#send_data").on("click",function(e){
        e.preventDefault();
        $("#create_modal_form").find(".required").each(function(){
          var self = $(this);
          if(self.val() == ""){
            self.addClass("input-error");
          }else{
            self.removeClass("input-error");
          }
        });
        // Se houver algum campo obrigtório vazio, não deixa continuar!
        if($("#create_modal_form").find(".input-error").length > 0) return false;

        // Pega todos os campos do modal
        var campo = [];
        $("table.input_adds").find(".field-row").each(function(){
          var self = $(this);
          var nome = self.find("td.nome").text();
          var tipo = self.find("td.tipo").text();

          campo.push(nome+";"+tipo);
        });

        var dados = {
          tipo_modal: $("select[name='tipo_modal']").val(),
          nome: $("input[name='nome']").val(),
          cookie: $("input[name='cookie']").val(),
          inicio: $("input[name='inicio']").val(),
          saida: $("input[name='saida']").val(),
          largura: $("input[name='largura']").val(),
          altura: $("input[name='altura']").val(),
          imagem: $("input[name='imagem']").val(),
          campos: campo,
          youtube: $("input[name='youtube']").val(),
          texto: CKEDITOR.instances.editor.getData(),
          chamada: $("select[name='chamada']").val(),
          pagina: $("input[name='pagina']").val(),
          tempo: $("input[name='tempo']").val()
        };

        $.ajax({
          url:ajaxurl,
          method:"POST",
          data:{
            action: "create_modal",
            dados: dados
          }
        }).success(function(data){          
          var json = JSON.parse(data);
          if(json.msg == "Dados enviados!"){
            alert("Modal Criado!");
            var url = window.location.href.split("?")[0]+"?page=CallBack/admin/index.php";
            window.location.assign(url);
          }else{
            alert("Desculpe, houve algum erro!");
            console.log(json.msg);
          }
        });
      });
    }
  });
})(jQuery);
