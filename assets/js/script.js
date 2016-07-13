(function($){

  // *** Função usada para construir o modal!
  function gerar_modal(modal,dados){
    // Calcula a posição central
    var modal_width = dados.largura;
    var screen_width = $(document).width();
    var center = ((screen_width/50) / 2) - 5;


    // CSS
    var bg = "#fff";
    if(dados.imagem != "") bg = "url("+dados.imagem+")";

    modal.css({
      width: dados.largura,
      "min-height": dados.altura,
      background:bg,
      "background-size": "cover",
      marginLeft: center+"%"
    });
    modal.find("#callBack_Body").append(dados.texto);

    // Gera os campos (se houver) ---
    if(dados.campos != ""){
      var breaker = dados.campos.split(" ");
      var builder = "";
      var form = modal.find("#callBack_form");
      form.append("<p id='data-id'> "+dados.id+" </p>");
      $.each(breaker,function(index,item){
        var cut = item.split(";");
        var nome = cut[0];
        var tipo = cut[1];

        if(tipo == "Texto"){
          builder = "<p><label>"+nome+":</label><input type='text' name='"+nome+"' class='field-required' /></p>";
        }else if(tipo == "Telefone"){
          builder = "<p><label>"+nome+":</label><input type='text' name='"+nome+"' class='field-required tel-masked' /></p>";
        }else if(tipo == "Mensagem"){
          builder = "<p><label>"+nome+":</label><textarea name='"+nome+"' class='field-required' ></textarea></p>";
        }else if(tipo == "Email"){
          builder = "<p><label>"+nome+":</label><input type='text' name='"+nome+"' class='field-required email-masked' /></p>";
        }
        form.append(builder);
      });
      builder = "<p><button>Enviar Dados</button></p>";
      form.append(builder);
    }
  }

  // *** Função usada para apresentar o modal!
  function chamar_modal(modal,dados,overlay){
    if(document.cookie.indexOf(dados.cookie) >= 0) return false;

    // Se o metodo for quando o usuário entra na página
    if(dados.chamada == "by_instant"){
      overlay.css("display","block");
      setTimeout(function(){modal.css("transform","rotateZ(-20deg)");},100);
      setTimeout(function(){modal.css("transform","rotateZ(20deg)");},400);
      setTimeout(function(){modal.css("transform","rotateZ(0deg)");},800);
      modal.addClass("show");

      // Coloca o cookie
      document.cookie = dados.cookie+"=set;expires=Thu, 01 Jan 2017 00:00:00 GMT";
    }

    // Se o metodo for quando o usuário estiver um certo tempo na página
    else if(dados.chamada == "by_time"){
      // Pega o tempo que foi colocado no admin e multiplica por 1000 (milsegundos)
      var time = (dados.tempo) * 1000;
      setTimeout(function(){
        overlay.css("display","block");
        setTimeout(function(){modal.css("transform","rotateZ(-20deg)");},100);
        setTimeout(function(){modal.css("transform","rotateZ(20deg)");},400);
        setTimeout(function(){modal.css("transform","rotateZ(0deg)");},800);
        modal.addClass("show");

        // Coloca o cookie
        document.cookie = dados.cookie+"=set;expires=Thu, 01 Jan 2017 00:00:00 GMT";
      },time);
    }

    // Se o metodo for quando o usuário sair do site
    else if(dados.chamada == "by_out"){
      $(document).on("mouseleave",function(){
        if(!modal.hasClass("show")){
          overlay.css("display","block");
          setTimeout(function(){modal.css("transform","rotateZ(-20deg)");},100);
          setTimeout(function(){modal.css("transform","rotateZ(20deg)");},400);
          setTimeout(function(){modal.css("transform","rotateZ(0deg)");},800);
          modal.addClass("show");

          // Coloca o cookie
          document.cookie = dados.cookie+"=set;expires=Thu, 01 Jan 2017 00:00:00 GMT";
        }
      });
    }

  }

  $(document).ready(function(){
    var callBack = $("#callBack_overlay");
    var callBack_window = $("#callBack_window");
    var close = $("#callBack_close");
    // Busca pelo modal
    var dados;
    $.ajax({
      url:ajaxurl,
      method:"GET",
      data:{
        action:"getmodal"
      }
    }).done(function(data){
      data = JSON.parse(data);
      dados = data;
        // Gera o Modal!
        gerar_modal(callBack_window,data);

        // Metodo de chamar o modal
        chamar_modal(callBack_window,data,callBack);
    });

    // Botão de fechar!
    close.on("click",function(){
      callBack.remove();
    });

    // *** Validação e Envio de Dados (se houver formulário)

    // Masks!!!
    $("#callBack_window").find("form").on("focus",".tel-masked",function(){
      $(this).mask('(00) 0000-0000r',{
        translation:{
          "r":{
            pattern: /[0-9]/, optional: true
          }
        }
      });
    });
    // Validação
    $("#callBack_window").find("form").on("click","button",function(e){
      e.preventDefault();
      var array = [];
      var form = $("#callBack_window").find("form");

      form.find("p .field-required").each(function(){
        var self = $(this);
        if(self.val() == ""){
          self.addClass("input-modal-error").focus;
          return false;
        }else{
          self.removeClass("input-modal-error");
          var name = self.attr("name");
          var value = self.val();
          array.push(name+":"+value);
        }
      });
      if($(".input-modal-error").length > 0) return false;
      var id = $("#data-id").text();
      // Envia os dados se esviterem ok!
      $.ajax({
        url: ajaxurl,
        method: "POST",
        data:{
          action:"modal_envia_dados_form",
          dados:array,
          id:id
        }
      }).done(function(data){
        alert("Agradecemos o seu interesse e em breve um de nossos consultores educacionais entrará em contato!");
        callBack.remove();
      });
    });

  });
})(jQuery);
