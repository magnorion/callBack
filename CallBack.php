<?php
  /*
    * Plugin Name: CallBack
    * Description: Plugin para construção de modais.
    * Version: 1.0
    * Author: Magnorion
    * Author Uri: https://github.com/magnorion/
  */

  class CallBack{
    public $conn;
    public $table_name;
    function __construct(){
      if(!is_admin()){
        ### Hook para carregar o plugin no site
        add_action("wp_head",array($this,"plugin_build"));

        ### Chama a variavel ajaxurl
        add_action("wp_head",array($this,"ajaxurl"));

        ### Hook para carregar todos os arquivos
        add_action("wp_enqueue_scripts",array($this,"plugin_assets"));
      }else{
        ### Monta o menu no admin
        add_action("admin_menu",array($this,"plugin_menu"));

        ### Carrega as dependencias admin
        add_action("admin_enqueue_scripts",array($this,"admin_assets"));

        ### Chama a variavel ajaxurl
        add_action("wp_footer",array($this,"ajaxurl"));

        ### Ação para criação de um novo modal
        add_action("wp_ajax_create_modal",array($this,"create_modal"));
        add_action("wp_ajax_nopriv_create_modal",array($this,"create_modal"));

        ### Ação para donwload dos leads
        add_action("wp_ajax_catch_leads_modal",array($this,"catch_leads_modal"));
        add_action("wp_ajax_nopriv_catch_leads_modal",array($this,"catch_leads_modal"));

        ### Ação para remover um modal
        add_action("wp_ajax_remove_modal",array($this,"remove_modal"));
        add_action("wp_ajax_nopriv_remove_modal",array($this,"remove_modal"));
      }
    }

    ### Função que carrega todas as dependencias do plugin no site
    public function plugin_assets(){
      ### Diretório do plugin
      $dir = plugin_dir_url(__FILE__);
      wp_enqueue_script("jquery");
      ### Style e Script Core do plugin
      wp_enqueue_style("CallBack_Style",$dir."/assets/css/style.css");

      wp_enqueue_script("CallBack_Script",$dir."/assets/js/script.js");

      ### Livereload (Usado apenas em desenvolvimento!)
      wp_enqueue_script("livereload","http://localhost:460/livereload.js");
    }

    ### Função que carrega todas as dependencias da área admin
    public function admin_assets(){
      ### Diretório do plugin
      $dir = plugin_dir_url(__FILE__);

      ### WP Media
      wp_enqueue_media();

      ### Core do admin
      wp_enqueue_script("admin-callBack-script",$dir."admin/assets/js/script.js");
      wp_enqueue_style("admin-callBack-style",$dir."admin/assets/css/style.css");

      ### Plugins de Terceiros
      wp_enqueue_script("jquery",$dir."admin/assets/vendor/jquery/dist/jquery.min.js"); ### jQuery Core
      wp_enqueue_script("ckeditor",$dir."admin/assets/vendor/ckeditor/ckeditor.js");
      wp_enqueue_script("datatable-js",$dir."admin/assets/vendor/datatable/jquery.dataTables.min.js");
      wp_enqueue_style("datatable-css",$dir."admin/assets/vendor/datatable/jquery.dataTables.min.css");
      wp_enqueue_style("font-awesome",$dir."admin/assets/vendor/font-awesome/css/font-awesome.min.css");
      wp_enqueue_script("queryUI-js",$dir."admin/assets/vendor/jquery-ui/jquery-ui.min.js");
      wp_enqueue_style("jqueryUI-css",$dir."admin/assets/vendor/jquery-ui/themes/smoothness/jquery-ui.min.css");

      ### Livereload (Usado apenas em desenvolvimento!)
      wp_enqueue_script("livereload","http://localhost:460/livereload.js");
    }

    ### Função que cria o menu!
    public function plugin_menu(){
      add_menu_page( "Modais", "Modais", "manage_options", "CallBack/admin/index.php", "",plugin_dir_url(__FILE__)."admin/assets/imgs/icon.png", null );
      add_submenu_page( "CallBack/admin/index.php", 'Novo Modal', 'Novo Modal', 'manage_options', 'CallBack/admin/add_modal.php', '' );
    }

    ### Função que chama o HTML do plugin
    public function plugin_build(){
      require_once("build_plugin/index.php");
    }

    ### Função que cria a variavel ajaxurl
    public function ajaxurl(){
      echo "<script> var ajaxurl = '".admin_url("admin-ajax.php")."'; </script>";
    }

    ### Função dedicada para o banco
    public function data_base_info(){
      require_once("admin/data/data-base.php");
    }

    /*
     * Funções que serão usadas no ajax
    */
    ### Função: Apresenta o modal no site
    public function getmodal(){
      $this->data_base_info();
      require_once("admin/data/get-modal.php");
    }

    ### Função: Envia as informações do modal para o banco de dados (GERA LEAD)
    public function modal_envia_dados_form(){
      $this->data_base_info();
      require_once("admin/data/send-data-modal.php");
    }


    ### Função: Criar modal
    public function create_modal(){
      $this->data_base_info();
      require_once("admin/data/create-modal.php");
    }

    ### Função: Remove modal
    public function remove_modal(){
      require_once("admin/data/remove-modal.php");
    }

    ### Função: Download dos leads
    public function catch_leads_modal(){
      $this->data_base_info();
      require_once("admin/data/get-leads.php");
    }
  }

  ### Inicia o plugin e recebe as princiapis configurações!
  $CallBack = new CallBack();
  $CallBack->conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
  $CallBack->table_name = "callback_modais";

  /*
   * Funções que serão aplicadas no site!
  */

  ### Ação para pegar os dados de um modal
  add_action("wp_ajax_getmodal",array($CallBack,"getmodal"));
  add_action("wp_ajax_nopriv_getmodal",array($CallBack,"getmodal"));

  ### Ação para enviar os dados de um modal (form)
  add_action("wp_ajax_modal_envia_dados_form",array($CallBack,"modal_envia_dados_form"));
  add_action("wp_ajax_nopriv_modal_envia_dados_form",array($CallBack,"modal_envia_dados_form"));
?>
