<h1> CallBack </h1>
<h2> Adicionar um novo Modal! </h2>
<form id="create_modal_form">
  <section id="callBack_admin_body">
    <h3> Dados do Modal </h3>
    <div class="body-left ">
      <p>
        <label> Tipo de Modal </label>
        <select name="tipo_modal" class="required">
          <option value=""> Selecione um tipo de Modal </option>
          <option value="Apenas Texto"> Apenas Texto </option>
          <option value="Formulário"> Formulário </option>
          <!-- <option value="Video"> Video </option> -->
        </select>
      </p>
    </div>
    <div class="body-left ">
      <p>
        <label> Nome do Modal: </label>
        <input type="text" name="nome" class="required" />
      </p>
      <p>
        <label> Cookie: </label>
        <input type="text" name="cookie" class="required" />
      </p>
      <p>
        <label> <i class="fa fa-calendar-check-o"></i> Data Inicio: </label>
        <input type="text" name="inicio" class="date-picker required" />
      </p>
      <p>
        <label> <i class="fa fa-calendar-times-o"></i> Data Fim: </label>
        <input type="text" name="saida" class="date-picker required" />
      </p>
    </div>
    <br/>
    <div class="the_editor">
      <h3> Construção </h3>
      <div class="body-left ">
        <p>
          <label> <i class="fa fa-arrows-h"></i> Largura: </label>
          <input type="text" name="largura" class="required" />
        </p>
        <p>
          <label> <i class="fa fa-arrows-v"></i> Altura: </label>
          <input type="text" name="altura" class="required" />
        </p>
      </div>
      <p>
        <button id="get_image"> <i class="fa fa-image"></i> Imagem de Fundo </button>
        <input type="text" name="imagem" class="image-url" placeholder="http://" disabled="disabled" />
      </p>
      <div class="body-left">
        <p>
          <label> Este modal é de captação? </label>
          <br/>
          <select name="captacao">
            <option value="Não"> Não </option>
            <option value="Sim"> Sim </option>
          </select>
        </p>
        <!-- <p>
          <label> <i class="fa fa-youtube-play"></i> YouTube Video: </label>
          <input type="text" placeholder="Apenas o link" name="youtube" />
        </p> -->
      </div>
      <div id="captacao_build">
        <button id="new_input"> <i class="fa fa-plus"></i> Adicione um novo campo </button>
        <table class="input_adds" cellspacing="0">
          <tr>
              <th>Nome Modal</th>
              <th>Tipo</th>
              <th>Ação</th>
          </tr>
        </table>
      </div>
      <label> Texto do modal: </label>
      <textarea name="texto" id="editor"></textarea>
    </div>
    <h3> Chamada do Modal </h3>
    <div class="body-left ">
      <p>
        <label> Como o modal deve ser chamado? </label>
        <br/>
        <select name="chamada" class="required">
          <option value=""> Selecione um tipo de chamada </option>
          <option value="by_instant"> Quando entrar no site </option>
          <option value="by_time"> Por tempo </option>
          <option value="by_out"> Por tirar o mouse </option>
        </select>
      </p>
      <p id="input_time">
        <label> <i class="fa fa-clock-o"></i> Tempo: </label>
        <input placeholder="Em segundos" type="text" name="tempo" />
      </p>
    </div>
    <button id="send_data"> <i class="fa fa-plus"></i> Criar Modal </button>
  </section>
</form>

<!-- JQUERY UI DIALOG -->
<div id="dialog-form">
  <form id="new_field_form">
    <label> Nome do Campo: </label>
    <input placeholder="Nome do campo" type="text" name="name" />
    <label> Tipo de campo: </label>
    <select name="input_type">
      <option value=""> Selecione um tipo </option>
      <option value="Texto"> Texto </option>
      <option value="Telefone"> Telefone </option>
      <option value="Mensagem"> Mensagem </option>
    </select>
    <input id="new_field_send" type="submit" value="Criar Campo">
  </form>
</div>
