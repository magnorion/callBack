(function($){

  // *** Função usada para construir o modal!
  function gerar_modal(modal,dados){
    // Calcula a posição central
    var modal_width = dados.largura;
    var screen_width = $(document).width();
    var center = ((screen_width/50) / 2) - 5;
    console.log(center);

    // CSS
    var bg = "#fff";
    if(dados.imagem != "") bg = "url("+dados.imagem+")";

    modal.css({
      width: dados.largura,
      "min-height": dados.altura,
      background:bg,
      "background-size": "cover",
      "margin":"5% auto",
      "left":"0%",
      "transform": "rotateZ(0deg)"
    });
    modal.find("#callBack_Body").append(dados.texto);

    // Gera os campos (se houver) ---
    if(dados.campos != "" && typeof dados.campos != "undefined"){
      var breaker = dados.campos.split(" ");
      var builder = "";
      var form = modal.find("#callBack_form");
      form.append("<p id='data-id'> "+dados.id+" </p>");
      $.each(breaker,function(index,item){
        var cut = item.split(";");
        var nome = cut[0];
        var tipo = cut[1];

       if(tipo == "Telefone" || tipo == "telefone"){
          builder = "<p class='telefone'><label>"+nome+" <small> Com DDD</small>:</label><input type='text' name='"+nome+"' class='tel-masked' /></p>";
        }else if(tipo == "Mensagem" || tipo == "Mensagem"){
          builder = "<p><label>"+nome+":</label><textarea name='"+nome+"' class='field-required' ></textarea></p>";
        }else if(tipo == "Email" || tipo == "email"){
          builder = "<p><label>"+nome+":</label><input type='text' name='"+nome+"' id='email' class='field-required email-masked' /></p><p><label>Confirmar e-mail:</label><input type='text' name='confirmacao' id='confirmar' class='field-required email-masked' /></p><div id='repita'></div>";
        }else if(tipo == "Nome" || tipo == "nome"){
          builder = "<p><label>Nome Completo:</label><input type='text' name='"+nome+"' class='field-required' /></p>";
        }else{
          builder = "<p><label>"+nome+":</label><input type='text' name='"+nome+"' class='field-required' /></p>";
        }
        form.append(builder);
      });
      builder = "<p><button>Enviar Dados</button></p>";
      form.append(builder);
    }
  }
  // *** Função usada para apresentar o modal!
  function chamar_modal(modal,dados,overlay){
    if(dados.cookie != ""){
      if(document.cookie.indexOf(dados.cookie) != "-1") return false;
    }
    // Se o metodo for quando o usuário entra na página
    if(dados.chamada == "by_instant"){
      overlay.css("display","block");
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
    var pagina = window.location.pathname;
    $.ajax({
      url:ajaxurl,
      method:"GET",
      data:{
        action:"getmodal",
        pagina:pagina
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
    close.live("click",function(){
      callBack.remove();
    });

    // *** Validação e Envio de Dados (se houver formulário)

    // Validação
    $("#callBack_window").find("form button").live("click",function(e){
      e.preventDefault();
      var array = [];
      var form = $("#callBack_window").find("form");

      form.find("p .field-required").each(function(){
        var self = $(this);
        if(self.val() == ""){
          self.addClass("input-modal-error").focus;
          return false;
        }else if(self.hasClass("email-masked")){
          var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if(!regex.test(self.val())){
            self.addClass("input-modal-error").focus;
            return false;
          }else{
            self.removeClass("input-modal-error");
            var name = self.attr("name");
            var value = self.val();
            array.push(name+":"+value);
          }
          if(self.prop("name") == "confirmacao"){
            var email_value = form.find("#email").val();
            if(self.val() != email_value){
              self.addClass("input-modal-error").focus;
              return false;
            }
            self.removeClass("input-modal-error");
            var name = self.attr("name");
            var value = self.val();
            array.push(name+":"+value);
          }
        }else{
          self.removeClass("input-modal-error");
          var name = self.attr("name");
          var value = self.val();
          array.push(name+":"+value);
        }
      });
      // Telefone
      var field = form.find("p.telefone");
      array.push("Telefone"+":"+field.find("input[type='text']").val());

      if($(".input-modal-error").length > 0) return false;
      var id = $("#data-id").text();
      // Envia os dados se esviterem ok!
      $.ajax({
        url:ajaxurl,
        method:"POST",
        data:{
          action:"modal_envia_dados_form",
          dados:array,
          id:id
        }
      }).done(function(data){
        var json = JSON.parse(data);
        var msg = "";
        if(json.msg == "Dados enviados!"){
          msg = "Agradecemos o seu interesse e em breve um de nossos consultores educacionais entrará em contato!";
          alert(msg);
          $("#callBack_close").trigger("click");
        }else{
          msg = "Houve um erro!";
          alert(msg);
          console.log(json);
        }
      });
    });

  });
})(jQuery);
