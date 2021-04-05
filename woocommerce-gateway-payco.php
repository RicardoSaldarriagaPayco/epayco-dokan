<?php
/**
 * @since             1.0.0
 * @package           ePayco_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       ePayco WooCommerce
 * Description:       Plugin ePayco WooCommerce.
 * Version:           5.1.x
 * Author:            ePayco
 * Author URI:        http://epayco.co
 *Lice
 * Text Domain:       epayco-woocommerce
 * Domain Path:       /languages
 */


if (!defined('WPINC')) {
    die;
}


require_once(dirname(__FILE__) . '/lib/EpaycoOrder.php');
require_once(dirname(__FILE__) . '/lib/EpaycoDokan.php');
//require_once(dirname(__FILE__) . '/style.css');
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('plugins_loaded', 'init_epayco_woocommerce', 0);
   
    function wporg_options_page()
        {
            global $submenu;
            $slug = 'payco';
            $capability    = 'manage_woocommerce';
            // add_menu_page(
            //     'ePayco',
            //     'ePayco',
            //     'manage_options',
            //     'payco',
            //     'Payco' ,
            //     plugin_dir_url(__FILE__) . 'images/payco.png',
            //     4
            // );
            add_menu_page( __( 'ePayco', 'epayco' ), __( 'ePayco', 'epayco' ), $capability, $slug,  'Payco' , 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g fill="#9EA3A8" fill-rule="nonzero"><path d="M2.565 1.909s10.352-.665 10.352 7.231-2.735 9.481-5.134 9.994c0 0 9.974 2.125 9.974-8.995S5.035.624 2.565 1.91z"/><path d="M10.982 15.353s-.999 3.07-4.018 3.461c-3.02.39-3.582-1.062-5.688-.962 0 0-.171-1.441 1.529-1.644 1.7-.202 4.885.193 7.004-.991 0 0 1.253-.582 1.499-.862l-.326.998z"/><path d="M2.407 2.465V15.74a3.29 3.29 0 01.32-.056 18.803 18.803 0 011.794-.083c.624 0 1.306-.022 1.987-.078v-4.465c0-1.485-.107-3.001 0-4.484.116-.895.782-1.66 1.73-1.988.759-.25 1.602-.2 2.316.135-3.414-2.24-7.25-2.284-8.147-2.255z"/></g></svg>' ), 55.4 );
            // if ( current_user_can( $capability ) ) {
            //     $submenu[ $slug ][] = [ __( 'Dashboard', 'epayco' ), $capability, 'admin.php?page=' . $slug . '#/' ];
            //     $submenu[ $slug ][] = [ __( 'Withdraw', 'depayco' ), $capability, 'admin.php?page=' . $slug . '#/withdraw?status=pending' ];
            // }
        }
        add_action('admin_menu', 'wporg_options_page');
function payco()
{

$tabs = array(
    'admin'  =>  'Admin',
    'ver'  =>  'Ver',
    'editar'  =>  'Editar',
    'eliminar' => 'Eliminar'
    );
  ?>
  <div id="icon-themes" class="icon32"><br/></div>
  <h2 class="nav-tab-wrapper">
    <?php
    foreach ( $tabs as $tab => $name ) {
      ?>

      <a class="nav-tab" href="?page=payco&tab=<?php echo $tab; ?>">
        <?php echo $name;
         ?>
      </a>
      <?php
    }
    ?>
      </h2>
  <?php
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'admin' ) {
        //settings_fieldse($option_group);
        $redirect_url =get_site_url() . "/wp-admin/admin.php/?page=wc-settings&tab=checkout&section=epayco";
        header('Location: '.$redirect_url);
        die();
   
      }
      elseif ( isset( $_GET['tab'] ) && $_GET['tab'] == 'editar' ) {
        include('epayco-funcion.php');
        settings_fieldse('wporg_options');
      }
        elseif (isset( $_GET['tab'] ) && $_GET['tab'] == 'ver') {
        include_once('sbs_canceled.php');
        settings_fieldsese('wporg_options');
      }
      elseif (isset( $_GET['tab'] ) && $_GET['tab'] == 'eliminar') {
        include_once('deleted.php');
        settings_fieldses('wporg_options');
      }
        //include_once('admin_payco.php');       
             // close the wrap  
            echo '<div class="wrap metabox-holder">';
    // snip snip
             echo '</div>';
            // output security fields for the registered setting "wporg_options"
            //settings_fieldse('wporg_options');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('wporg');
            // output save settings button
           // submit_button('Save Settings')
 
            ?>
     </form>

    <?php

        if (isset($_POST['guardar'])) {

        $apikey =$_POST['YOU_PUBLIC_API_KEY'];
        $privatekey=$_POST['YOU_PRIVATE_API_KEY'];
        $lenguage=$_POST['lenguage'];
        $test=$_POST['TEST'];
        global $wpdb;
        $table = $wpdb->prefix.'payco';


        $data = array('id' => 1, 'search' => 'Payco',
        'privatekey'=> $privatekey, 'apikey'=>$apikey,'lenguage'=>$lenguage,'test'=>$test );
        $format = array('id'=>1);
        $wpdb->update($table,$data,$format);

        }

            echo "";
}
    function init_epayco_woocommerce()
    {
    

       
        if (!class_exists('WC_Payment_Gateway')) {
            return;
        }
        class WC_ePayco extends WC_Payment_Gateway
        {
            public $max_monto;
            public function __construct()
            {
                $this->id = 'epayco';
                $this->icon = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png';
                $this->method_title = __('ePayco Checkout', 'epayco_woocommerce');
                $this->method_description = __('Acepta tarjetas de credito, depositos y transferencias.', 'epayco_woocommerce');
                $this->order_button_text = __('Pagar', 'epayco_woocommerce');
                $this->has_fields = false;
                $this->supports = array('products');
                $this->init_form_fields();
                $this->init_settings();
                $this->msg['message']   = "";
                $this->msg['class']     = "";
                $this->title = $this->get_option('epayco_title');
                $this->epayco_customerid = $this->get_option('epayco_customerid');
                $this->epayco_secretkey = $this->get_option('epayco_secretkey');
                $this->epayco_publickey = $this->get_option('epayco_publickey');
                $this->monto_maximo = $this->get_option('monto_maximo');
                $this->max_monto = $this->get_option('monto_maximo');
                $this->description = $this->get_option('description');
                $this->epayco_testmode = $this->get_option('epayco_testmode');
                if ($this->get_option('epayco_reduce_stock_pending') !== null ) {
                    $this->epayco_reduce_stock_pending = $this->get_option('epayco_reduce_stock_pending');
                }else{
                     $this->epayco_reduce_stock_pending = "yes";
                }
                $this->epayco_type_checkout=$this->get_option('epayco_type_checkout');
                $this->p_split_primary_receiver_fee=$this->get_option('p_split_primary_receiver_fee');
                
                $this->epayco_endorder_state=$this->get_option('epayco_endorder_state');
                $this->epayco_url_response=$this->get_option('epayco_url_response');
                $this->epayco_url_confirmation=$this->get_option('epayco_url_confirmation');
                $this->epayco_lang=$this->get_option('epayco_lang')?$this->get_option('epayco_lang'):'es';
                $this->response_data = $this->get_option('response_data');
                add_filter('woocommerce_thankyou_order_received_text', array(&$this, 'order_received_message'), 10, 2 );
                add_action('ePayco_init', array( $this, 'ePayco_successful_request'));
                add_action('woocommerce_receipt_' . $this->id, array(&$this, 'receipt_page'));
                add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_ePayco_response' ) );
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
                add_action('wp_ajax_nopriv_returndata',array($this,'datareturnepayco_ajax'));
                if ($this->epayco_testmode == "yes") {
                    if (class_exists('WC_Logger')) {
                        $this->log = new WC_Logger();
                    } else {
                        $this->log = WC_ePayco::woocommerce_instance()->logger();
                    }
                }
            }

            function order_received_message( $text, $order ) {
                if(!empty($_GET['msg'])){
                    return $text .' '.$_GET['msg'];
                }
                return $text;
            }

            public function is_valid_for_use()
            {
                return in_array(get_woocommerce_currency(), array('COP', 'USD'));
            }

            public function admin_options()
            {
                ?>
                <style>
                    tbody{
                    }
                    .epayco-table tr:not(:first-child) {
                        border-top: 1px solid #ededed;
                    }
                    .epayco-table tr th{
                            padding-left: 15px;
                            text-align: -webkit-right;
                    }
                    .epayco-table input[type="text"]{
                            padding: 8px 13px!important;
                            border-radius: 3px;
                            width: 100%!important;
                    }
                    .epayco-table .description{
                        color: #afaeae;
                    }
                    .epayco-table select{
                            padding: 8px 13px!important;
                            border-radius: 3px;
                            width: 100%!important;
                            height: 37px!important;
                    }
                    .epayco-required::before{
                        content: '* ';
                        font-size: 16px;
                        color: #F00;
                        font-weight: bold;
                    }

                </style>

                <div class="container-fluid">
                    <div class="panel panel-default" style="">
                        <img  src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-pencil"></i>Configuración <?php _e('ePayco', 'epayco_woocommerce'); ?></h3>
                        </div>

                        <div style ="color: #31708f; background-color: #d9edf7; border-color: #bce8f1;padding: 10px;border-radius: 5px;">
                            <b>Este modulo le permite aceptar pagos seguros por la plataforma de pagos ePayco</b>
                            <br>Si el cliente decide pagar por ePayco, el estado del pedido cambiara a ePayco Esperando Pago
                            <br>Cuando el pago sea Aceptado o Rechazado ePayco envia una configuracion a la tienda para cambiar el estado del pedido.
                        </div>

                        <div class="panel-body" style="padding: 15px 0;background: #fff;margin-top: 15px;border-radius: 5px;border: 1px solid #dcdcdc;border-top: 1px solid #dcdcdc;">
                                <table class="form-table epayco-table">
                                <?php
                            if ($this->is_valid_for_use()) :
                                $this->generate_settings_html();
                            else :
                            if ( is_admin() && ! defined( 'DOING_AJAX')) {
                                echo '<div class="error"><p><strong>' . __( 'ePayco: Requiere que la moneda sea USD O COP', 'epayco-woocommerce' ) . '</strong>: ' . sprintf(__('%s', 'woocommerce-mercadopago' ), '<a href="' . admin_url() . 'admin.php?page=wc-settings&tab=general#s2id_woocommerce_currency">' . __( 'Click aquí para configurar!', 'epayco_woocommerce') . '</a>' ) . '</p></div>';
                                        }
                                    endif;
                                ?>
                                </table>
                                <center>
                                <div id="ruta" name="ruta" hidden="true">
                                    <?php   echo plugin_dir_url(__FILE__) .'lib/EpaycoSetup.php';?>
                                </div>
                               
                                <label>customer id</label>
                                <input type="text" name="vendor" id="vendor">
                               
                                <select name="attribute_taxonomy" class="attribute_taxonomy" id="mySelect1" hidden="true">
                                <option value="fijo" selected>fijo</option>
                                <option value="porcentaje">porcentaje</option>
                                </select>
                               
                                <input type="text" name="valor" hidden="true" id="valor" value="1">
                                <label>correo</label>
                                <input type="text" name="correo" id="correo">
                                <button type="button" class="button add_attribute" onclick="myFunction()"><?php esc_html_e( 'Add', 'woocommerce' ); ?></button>
                                <div class="" id="user-switching-installer-notice" style="padding: 3px 10px; position: relative; display: flex; align-items: center;" name="chec_">
                                <p style="flex: 2;visibility: hidden;" name="guardado">Guardado!</p>

                                </div>
                                </center> 
                                    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
                                    <script type="text/javascript">
                                        function myFunction() {
                                            
                                            var cust_id = document.getElementById("vendor").value;
                                            var x = document.getElementById("mySelect1").value;
                                            var valor = document.getElementById("valor").value;
                                            var correo = document.getElementById("correo").value;
                                            
                                            var ruta = document.getElementsByName("ruta")[0].innerText;
                                            var data = {
                                                "epayco_id":cust_id,
                                                "select":x,
                                                "valor":valor,
                                                "email": correo
                                            }
                                            $.ajax({
                                                type:"POST",
                                                url:ruta,
                                                data:data,
                                                success: function(datos){
                                                    debugger
                                                    console.log(datos)
                                                    if(datos =="0"){
                                                        alertarError()
                                                    $(':input[type="button"]').prop('disabled', false);
                                                    
                                                    }else{
                                                        alertar()
                                                        $(':input[type="button"]').prop('disabled', false);
                                                       
                                                    }
                                                }
                                            });
                                        }
                                        function alertar(){
						document.getElementsByName('chec_')[0].classList.add('updated')
						document.getElementsByName('guardado')[0].style.visibility = 'visible';
						$("#snoAlertBox").fadeIn();
  						 closeSnoAlertBox();
					}
                    function closeSnoAlertBox() {
					window.setTimeout(function() {
						$("#snoAlertBox").fadeOut(300)
						}, 3000);
					window.setTimeout(function() {
						document.getElementsByName('chec_')[0].classList.remove('updated')
						document.getElementsByName('guardado')[0].style.visibility = 'hidden';
						location.reload();
						}, 3000);	
					};

                    function alertarError(){
						document.getElementsByName('chec_')[0].classList.add('error')
						document.getElementsByName('guardado')[0].style.visibility = 'hidden';
						$("#snoAlertBox").fadeIn();
  						 closeSnoAlertBoxError();
					}
					function closeSnoAlertBoxError() {
					window.setTimeout(function() {
						$("#snoAlertBox").fadeOut(300000)
						}, 2000);
					window.setTimeout(function() {
						document.getElementsByName('chec_')[0].classList.remove('error')
						document.getElementsByName('guardado')[0].style.visibility = 'hidden';
						}, 3000);	
					};
                                    </script>
                        </div>
                    </div>
                </div>
                <?php
            }
            
            public function init_form_fields()
            {
                $this->form_fields = array(
                    'enabled' => array(
                    'title' => __('Habilitar/Deshabilitar', 'epayco_woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Habilitar ePayco Checkout', 'epayco_woocommerce'),
                    'default' => 'yes'
                    ),
                    'epayco_title' => array(
                        'title' => __('<span class="epayco-required">Título</span>', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('Corresponde al titulo que el usuario ve durante el checkout.', 'epayco_woocommerce'),
                        'default' => __('Checkout ePayco (Tarjetas de crédito,debito,efectivo)', 'epayco_woocommerce'),
                        //'desc_tip' => true,
                    ),
                    'description' => array(
                        'title' => __('<span class="epayco-required">Descripción</span>', 'epayco_woocommerce'),
                        'type' => 'textarea',
                        'description' => __('Corresponde a la descripción que verá el usuaro durante el checkout', 'epayco_woocommerce'),
                        'default' => __('Checkout ePayco (Tarjetas de crédito,debito,efectivo)', 'epayco_woocommerce'),
                        //'desc_tip' => true,
                    ),
                    'epayco_customerid' => array(
                        'title' => __('<span class="epayco-required">P_CUST_ID_CLIENTE</span>', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('p_split_merchant_receiver y p_split_primary_receiver, Id Comercio dueño del producto o servicio y recibidor primario', 'epayco_woocommerce'),
                        'default' => '',
                        //'desc_tip' => true,
                        'placeholder' => '',
                    ),
                    'epayco_secretkey' => array(
                        'title' => __('<span class="epayco-required">P_KEY</span>', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('LLave para firmar la información enviada y recibida de ePayco. Lo puede encontrar en su panel de clientes en la opción configuración.', 'epayco_woocommerce'),
                        'default' => '',
                        'placeholder' => ''
                    ),
                    'epayco_publickey' => array(
                        'title' => __('<span class="epayco-required">PUBLIC_KEY</span>', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('LLave para autenticar y consumir los servicios de ePayco, Proporcionado en su panel de clientes en la opción configuración.', 'epayco_woocommerce'),
                        'default' => '',
                        'placeholder' => ''
                    ),
                    'epayco_testmode' => array(
                        'title' => __('Sitio en pruebas', 'epayco_woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar el modo de pruebas', 'epayco_woocommerce'),
                        'description' => __('Habilite para realizar pruebas', 'epayco_woocommerce'),
                        'default' => 'no',
                    ),
                    'epayco_type_checkout' => array(
                        'title' => __('Tipo de Split', 'epayco_woocommerce'),
                        'type' => 'select',
                        'css' =>'line-height: inherit',
                        'label' => __('Seleccione un tipo de Split:', 'epayco_woocommerce'),
                        'description' => __('Tipo de Split Fijo o Porcentaje', 'epayco_woocommerce'),
                        'options' => array('false'=>"Fijo","true"=>"Porcentaje"),
                    ),
                    'p_split_primary_receiver_fee' => array(
                        'title' => __('<span class="epayco-required">p_split_primary_receiver_fee</span>', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('Comisión del recibidor primario.', 'epayco_woocommerce'),
                        'default' => '',
                        'placeholder' => ''
                    ),
                    'epayco_endorder_state' => array(
                        'title' => __('Estado Final del Pedido', 'epayco_woocommerce'),
                        'type' => 'select',
                        'css' =>'line-height: inherit',
                        'description' => __('Seleccione el estado del pedido que se aplicará a la hora de aceptar y confirmar el pago de la orden', 'epayco_woocommerce'),
                        'options' => array(
                            'epayco-processing'=>"ePayco Procesando Pago",
                            "epayco-completed"=>"ePayco Pago Completado",
                            'processing'=>"Procesando",
                            "completed"=>"Completado"
                        ),
                    ),
                    'epayco_url_response' => array(
                        'title' => __('Página de Respuesta', 'epayco_woocommerce'),
                        'type' => 'select',
                        'css' =>'line-height: inherit',
                        'description' => __('Url de la tienda donde se redirecciona al usuario luego de pagar el pedido', 'epayco_woocommerce'),
                        'options'       => $this->get_pages(__('Seleccionar pagina', 'payco-woocommerce')),
                    ),
                    'epayco_url_confirmation' => array(
                        'title' => __('Página de Confirmación', 'epayco_woocommerce'),
                        'type' => 'select',
                        'css' =>'line-height: inherit',
                        'description' => __('Url de la tienda donde ePayco confirma el pago', 'epayco_woocommerce'),
                        'options'       => $this->get_pages(__('Seleccionar pagina', 'payco-woocommerce')),
                    ),
                    'epayco_reduce_stock_pending' => array(
                        'title' => __('Reducir el stock en transacciones pendientes', 'epayco_woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar', 'epayco_woocommerce'),
                        'description' => __('Habilite para reducir el stock en transacciones pendientes', 'epayco_woocommerce'),
                        'default' => 'yes',
                    ),
                    'epayco_lang' => array(
                        'title' => __('Idioma del Checkout', 'epayco_woocommerce'),
                        'type' => 'select',
                        'css' =>'line-height: inherit',
                        'description' => __('Seleccione el idioma del checkout', 'epayco_woocommerce'),
                        'options' => array('es'=>"Español","en"=>"Inglés"),
                    ),
                    'response_data' => array(
                        'title' => __('Habilitar envió de atributos a través de la URL de respuesta', 'epayco_woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar el modo redireccion con data', 'epayco_woocommerce'),
                        'description' => __('Al habilitar esta opción puede exponer información sensible de sus clientes, el uso de esta opción es bajo su responsabilidad, conozca esta información en el siguiente  <a href="https://docs.epayco.co/payments/checkout#scroll-response-p" target="_blank">link.</a>', 'epayco_woocommerce'),
                        'default' => 'no',
                    ),
                        'monto_maximo' => array(
                        'title' => __('monto maximo', 'epayco_woocommerce'),
                        'type' => 'text',
                        'description' => __('ingresa el monto maximo permitido ha pagar por el metodo de pago', 'epayco_woocommerce'),
                        'default' => '3000000',
                        //'desc_tip' => true,
                        'placeholder' => '3000000',
                    ),
                );
            }


            /**
             * @param $order_id
             * @return array
             */
            public function process_payment($order_id)
            {
                $order = new WC_Order($order_id);
                $order->reduce_order_stock();
                if (version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')) {
                    return array(
                        'result'    => 'success',
                        'redirect'  => add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))
                    );
                } else {
                    return array(
                        'result'    => 'success',
                        'redirect'  => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))
                    );
                }
            }


            function get_pages($title = false, $indent = true) {

                $wp_pages = get_pages('sort_column=menu_order');
                $page_list = array();
                if ($title) $page_list[] = $title;
                foreach ($wp_pages as $page) {
                    $prefix = '';
                    // show indented child pages?
                    if ($indent) {
                        $has_parent = $page->post_parent;
                        while($has_parent) {
                            $prefix .=  ' - ';
                            $next_page = get_page($has_parent);
                            $has_parent = $next_page->post_parent;
                        }
                    }

                    // add to page list array array
                    $page_list[$page->ID] = $prefix . $page->post_title;
                }
                return $page_list;
            }


            /**
             * @param $order_id
             */

            public function receipt_page($order_id)
            {
                global $woocommerce;
                global $wpdb;
                $order = new WC_Order($order_id);
                $descripcionParts = array();
                foreach ($order->get_items() as $product) {
                    $clearData = str_replace('_', ' ', $this->string_sanitize($product['name']));
                    $descripcionParts[] = $clearData;
                }
                $descripcion = implode(' - ', $descripcionParts);
                $currency = strtolower(get_woocommerce_currency());
                $testMode = $this->epayco_testmode == "yes" ? "true" : "false";
                $basedCountry = WC()->countries->get_base_country();
                $p_split_type=$this->epayco_type_checkout;
                $external=$this->epayco_type_checkout;
                $redirect_url =get_site_url() . "/";
                $confirm_url=get_site_url() . "/";
                $redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
                $confirm_url = add_query_arg( 'wc-api', get_class( $this ), $confirm_url );
                $confirm_url = add_query_arg( 'order_id', $order_id, $confirm_url );
                $confirm_url = $redirect_url.'&confirmation=1';
                //$confirm_url = add_query_arg( 'confirmation', 1 );
                $name_billing=$order->get_billing_first_name().' '.$order->get_billing_last_name();
                $address_billing=$order->get_billing_address_1();
                $phone_billing=@$order->billing_phone;
                $email_billing=@$order->billing_email;
                $order = new WC_Order($order_id);
                $tax=$order->get_total_tax();
                $tax=round($tax,2);
                if((int)$tax>0){
                    $base_tax=$order->get_total()-$tax;
                }else{
                    $base_tax=$order->get_total();
                    $tax=0;
                }
                 $items = $woocommerce->cart->get_cart();
                foreach($items as $item => $value) {
                $_product =  wc_get_product( $value['data']->get_id());
                $price = get_post_meta($value['product_id'] , '_price', true);
                   if($value['line_subtotal_tax']>0){
                    $regulart_product_tax=$value['line_subtotal_tax'];
                }else{
                     $regulart_product_tax=0;
                }
                $amount1=$price*$value['quantity'];
                $total=floatval($amount1+$regulart_product_tax);
                $product_ = $_product->get_data();
                $productName = json_decode(json_encode((object)$product_), FALSE);
                }
               // var_dump($productName);
                $product_price = $productName->price;
                $product_id = $productName->id;
                $product_sku = $productName->sku;
                $arrayName3 = array();
                $table_name = $wpdb->prefix . "epayco_vendor";
                $sql = 'SELECT * FROM '.$table_name;
                $results = $wpdb->get_results($sql, OBJECT);
                $arrayName = array();

                
                foreach ($results as $key ) {
                    foreach ($order->get_items() as $item_key => $item ){
                    $product_id   = $item->get_product_id(); // the Product id
                    $vendor_id = get_post_field( 'post_author', $product_id );
                    $vendor = get_userdata( $vendor_id );
          
                    if($key->correo==$vendor->user_email){
                        $table_name_ = $wpdb->prefix . "usermeta";
                        $sql_ = 'SELECT meta_value FROM '.$table_name_ .' WHERE user_id = '. intval($vendor_id).' AND meta_key = "dokan_admin_percentage"';
                        $sql_2 = 'SELECT meta_value FROM '.$table_name_ .' WHERE user_id = '. intval($vendor_id).' AND meta_key = "dokan_admin_percentage_type"';
                        $results_ = $wpdb->get_results($sql_, OBJECT);
                        $results_2 = $wpdb->get_results($sql_2, OBJECT);
                        if($results_2[0]->meta_value == "flat"){
                            $p_split_type_ = '01';
                        }else{
                            $p_split_type_ = '02';
                        }
                        $other = array( 
                        "epayco_id"=>$key->epayco_id,
                        "epayco_p"=> $results_2[0]->meta_value,
                        "epayco_pr"=> $results_[0]->meta_value,
                        "epayco_pk"=> $key->epayco_pk,
                        "p_split_type" => $p_split_type_, 
                        "correo"=> $key->correo,
                        "vendor_email"=>$vendor->user_email,
                        "vendor_nicename"=>$vendor->user_nicename,
                        "vendor_userUrl"=>$vendor->user_url,
                        "vendor_productoId"=>$product_id
                    );
                       array_push($arrayName, $other );
                                                            }
                         }      
                }
           
                foreach($items as $item => $value) {
                    $_product =  wc_get_product( $value['data']->get_id());
                    $price = get_post_meta($value['product_id'] , '_price', true);
                    if($value['line_subtotal_tax']>0){
                        $regulart_product_tax=$value['line_subtotal_tax'];
                    }else{
                         $regulart_product_tax=0;
                    }
                    $aomunt1=$price*$value['quantity'];
                    $total=floatval($aomunt1+$regulart_product_tax);
                    $product_ = $_product->get_data();
                    $productName = json_decode(json_encode((object)$product_),FALSE);
                     foreach ($arrayName as $key) {
                        if( $value['product_id'] == $key['vendor_productoId'] ){
                            
                            if($key['p_split_type']=='01'){
                                $valor_ = (intval($key['epayco_pr'])*$value['quantity']);
                            }else{
                                $valor_ = ($aomunt1*intval($key['epayco_pr']))/100;  
                            }
                            $other = array( 
                        "product_id"=> $value['product_id'],
                            // "product_name"=> $productName->name, 
                            "product_price"=>floatval($price),
                            "product_regular_tax"=> floatval($regulart_product_tax),
                            "subtotal_price"=>floatval($aomunt1),
                            "total_price"=>floatval($total),
                            //  "quantyty"=>$value['quantity'],
                            "epayco_id"=>$key['epayco_id'],
                             "epayco_tipe"=>$key['epayco_p'],
                            "epayco_feed_per_unit"=>$key['epayco_pr'],
                            "epayco_feed"=>floatval($valor_),
                            "p_split_type" => $key['p_split_type'],
                            // "correo"=>$key['correo'],
                            // "vendor_nicename"=>$key['vendor_nicename'],
                            // "vendor_productoId"=> $key['vendor_productoId']
                        );
                            array_push($arrayName3, $other );
                        }
                     }
     
                    }
                    $valor_primary_receiver = 0;
                    foreach ($arrayName3 as $key) {
                        
                        $valor_primary_receiver +=$key['epayco_feed'];
                    }
                    $datasuscription=json_encode($arrayName3);
                    $p_signature_receivers ='';
                    $valor_primary_receiver_total = (floatval($order->get_total())-floatval($valor_primary_receiver));

                    $arrayName4 = array(
                        "p_split_merchant_receiver"=> trim($this->epayco_customerid),
                        "p_split_primary_receiver_fee"=>$valor_primary_receiver_total
                    );
                    $p_split_primary_receiver_fee = $valor_primary_receiver_total;
                //   var_dump( $p_split_primary_receiver_fee);
                //   die();
            

                foreach($arrayName3 as $receiver){
                $p_signature_receivers.= $receiver['epayco_id']. '^' .$receiver['epayco_feed'];
                }


                $p_signature_split = md5(
                     '01'.'^'
                    .trim($this->epayco_customerid).'^'
                    .trim($this->epayco_customerid).'^'
                    .$order->get_id().'^'
                    .$p_signature_receivers
                );

                $p_signature = md5(trim($this->epayco_customerid).'^'.trim($this->epayco_secretkey).'^'.$order->get_id().'^'.floatval($order->get_total()).'^'.$currency);

                // echo "p_signature  _ ".$p_signature."<br>";
                // echo "p_signature_split _ ".$p_signature_split."<br>";
                // die();
      
                //Busca si ya se restauro el stock

                if (!EpaycoOrder::ifExist($order_id)) {
                    //si no se restauro el stock restaurarlo inmediatamente
                    $this->restore_order_stock($order_id);
                    EpaycoOrder::create($order_id,1);
                }
            

               

                if ($this->epayco_lang !== "es") {

                    $msgEpaycoCheckout = '<span class="animated-points">Loading payment methods</span>
                               <br><small class="epayco-subtitle"> If they do not load automatically, click on the "Pay with ePayco" button</small>';

                    $epaycoButtonImage = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/btn7.png';

                }else{
                    $msgEpaycoCheckout = '<span class="animated-points">Cargando metodos de pago</span>
                    <br><small class="epayco-subtitle"> Si no se cargan automáticamente, de clic en el botón "Pagar con ePayco</small>';
                    $epaycoButtonImage = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/epayco/boton_de_cobro_epayco6.png';
                }

                 $conut=0;
                 $html_2 = "";
                foreach($arrayName3 as $receiver){
                    $html_ = "<input name=\"p_split_receivers[{$conut}][id]\" type=\"hidden\" value=\"{$receiver["epayco_id"]}\">
                    <input name=\"p_split_receivers[{$conut}][fee]\" type=\"hidden\" value=\"{$receiver["epayco_feed"]}\">"
                    ;
                    $html_2 = $html_2.$html_;
                    $conut +=1;
                };


                echo('
                    <style>
                        .epayco-title{
                            max-width: 900px;
                            display: block;
                            margin:auto;
                            color: #444;
                            font-weight: 700;
                            margin-bottom: 25px;
                        }
                        .loader-container{
                            position: relative;
                            padding: 20px;
                            color: #ff5700;
                        }
                        .epayco-subtitle{
                            font-size: 14px;
                        }
                        .epayco-button-render{
                            transition: all 500ms cubic-bezier(0.000, 0.445, 0.150, 1.025);
                            transform: scale(1.1);
                            box-shadow: 0 0 4px rgba(0,0,0,0);
                        }
                        .epayco-button-render:hover {
                            /*box-shadow: 0 0 4px rgba(0,0,0,.5);*
                            transform: scale(1.2);
                        }

                        .animated-points::after{
                            content: "";
                            animation-duration: 2s;
                            animation-fill-mode: forwards;
                            animation-iteration-count: infinite;
                            animation-name: animatedPoints;
                            animation-timing-function: linear;
                            position: absolute;
                        }
                        .animated-background {
                            animation-duration: 2s;
                            animation-fill-mode: forwards;
                            animation-iteration-count: infinite;
                            animation-name: placeHolderShimmer;
                            animation-timing-function: linear;
                            color: #f6f7f8;
                            background: linear-gradient(to right, #7b7b7b 8%, #999 18%, #7b7b7b 33%);
                            background-size: 800px 104px;
                            position: relative;
                            background-clip: text;
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                        }
                        .loading::before{
                            -webkit-background-clip: padding-box;
                            background-clip: padding-box;
                            box-sizing: border-box;
                            border-width: 2px;
                            border-color: currentColor currentColor currentColor transparent;
                            position: absolute;
                            margin: auto;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            content: " ";
                            display: inline-block;
                            background: center center no-repeat;
                            background-size: cover;
                            border-radius: 50%;
                            border-style: solid;
                            width: 30px;
                            height: 30px;
                            opacity: 1;
                            -webkit-animation: loaderAnimation 1s infinite linear,fadeIn 0.5s ease-in-out;
                            -moz-animation: loaderAnimation 1s infinite linear, fadeIn 0.5s ease-in-out;
                            animation: loaderAnimation 1s infinite linear, fadeIn 0.5s ease-in-out;
                        }
                        @keyframes animatedPoints{
                            33%{
                                content: "."
                            }

                            66%{
                                content: ".."
                            }

                            100%{
                                content: "..."
                            }
                        }

                        @keyframes placeHolderShimmer{
                            0%{
                                background-position: -800px 0
                            }
                            100%{
                                background-position: 800px 0
                            }
                        }
                        @keyframes loaderAnimation{
                            0%{
                                -webkit-transform:rotate(0);
                                transform:rotate(0);
                                animation-timing-function:cubic-bezier(.55,.055,.675,.19)
                            }

                            50%{
                                -webkit-transform:rotate(180deg);
                                transform:rotate(180deg);
                                animation-timing-function:cubic-bezier(.215,.61,.355,1)
                            }
                            100%{
                                -webkit-transform:rotate(360deg);
                                transform:rotate(360deg)
                            }
                        }
                    </style>
            ');
    
                echo sprintf('
                        <div class="loader-container">
                            <div class="loading"></div>
                        </div>
                        <p style="text-align: center;" class="epayco-title">
                           '.$msgEpaycoCheckout.'
                        </p>                        
                        <script type="text/javascript" src="https://checkout.epayco.co/checkout.js">   </script>
                        <center>
                       
                        <form
                        id="frm_botonePayco"
                        name="frm_botonePayco"
                        method="post"
                        action="https://secure.payco.co/splitpayments.php">
                        <input name="p_cust_id_cliente" type="hidden" value="%s">
                        <input name="p_key" type="hidden" value="%s">
                        <input name="p_id_invoice" type="hidden" value="%s">
                        <input name="p_description" type="hidden" value="%s">
                        <input name="p_currency_code" type="hidden" value="%s">
                        <input name="p_amount" id="p_amount" type="hidden" value="%s">
                        <input name="p_tax" id="p_tax" type="hidden" value="%s">
                        <input name="p_amount_base" id="p_amount_base" type="hidden" value="%s">
                        <input name="p_test_request" type="hidden" value="%s">
                        <input name="p_url_response" type="hidden" value="%s">
                        <input name="p_signature" type="hidden" id="signature" value="%s">
                        <input name="p_split_type" type="hidden" value="01">
                        <input name="p_split_merchant_receiver" type="hidden" value="%s">
                        <input name="p_split_primary_receiver" type="hidden" value="%s">
                        <input name="p_split_primary_receiver_fee" type="hidden" value="%s">
                        
                        '.$html_2.'
                        <input name="p_signature_split" type="hidden" value="%s">
                        <input type="image" id="imagen" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/btn1.png">
                    </form> 
                     
                        </center>
                ',trim($this->epayco_customerid),trim($this->epayco_secretkey),$order->get_id(),$descripcion,$currency,floatval($order->get_total()),floatval($tax),floatval($base_tax),$testMode, $redirect_url,$p_signature,trim($this->epayco_customerid),trim($this->epayco_customerid),$p_split_primary_receiver_fee,$p_signature_split);

                $messageload = __('Espere por favor..Cargando checkout.','payco-woocommerce');
                $js = "if(jQuery('button.epayco-button-render').length)    
                {
                jQuery('button.epayco-button-render').css('margin','auto');
                jQuery('button.epayco-button-render').css('display','block');
                }";

                if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')){
                    wc_enqueue_js($js);
                }else{
                    $woocommerce->add_inline_js($js);
                }
            }


            public function datareturnepayco_ajax()
            {
                die();
            }


            public function block($message)
            {
                return 'jQuery("body").block({
                        message: "' . esc_js($message) . '",
                        baseZ: 99999,
                        overlayCSS:
                        {
                            background: "#000",
                            opacity: "0.6",
                        },

                        css: {
                            padding:        "20px",
                            zindex:         "9999999",
                            textAlign:      "center",
                            color:          "#555",
                            border:         "1px solid #aaa",
                            backgroundColor:"#fff",
                            cursor:         "wait",
                            lineHeight:     "24px",
                        }
                    });';
            }

            public function authSignature($x_ref_payco,$x_transaction_id,$x_amount, $x_currency_code){
                    $signature = hash('sha256',
                                trim($this->epayco_customerid).'^'
                                .trim($this->epayco_secretkey).'^'
                                .$x_ref_payco.'^'
                                .$x_transaction_id.'^'
                                .$x_amount.'^'
                                .$x_currency_code
                            );
                return $signature;
            }

            function check_ePayco_response(){
                @ob_clean();
                if ( ! empty( $_REQUEST ) ) {
                    header( 'HTTP/1.1 200 OK' );
                    do_action( "ePayco_init", $_REQUEST );
                } else {
                    wp_die( __("ePayco Request Failure", 'epayco-woocommerce') );
                }
            }


            /**
             * @param $validationData
             */
            function ePayco_successful_request($validationData)
            {       
                    global $woocommerce;
                    $order_id="";
                    $ref_payco="";
                    $signature="";
                    $x_signature_p= wp_kses_post($_REQUEST['x_signature']);
                    $x_signature_e=esc_html($_REQUEST['x_signature']);
                 
                    if($x_signature_p || $x_signature_e ){
                       $order_id = trim(sanitize_text_field($_GET['order_id']));
                       $x_ref_payco = trim(sanitize_text_field($_REQUEST['x_ref_payco']));
                       $x_transaction_id = trim(sanitize_text_field($_REQUEST['x_transaction_id']));
                       $x_amount = trim(sanitize_text_field($_REQUEST['x_amount']));
                       $x_currency_code = trim(sanitize_text_field($_REQUEST['x_currency_code']));
                       $x_signature = trim(sanitize_text_field($_REQUEST['x_signature']));
                       $x_cod_transaction_state=(int)trim(sanitize_text_field($_REQUEST['x_cod_transaction_state']));
                        // $url = 'https://secure.epayco.co/validation/v1/reference/'.$ref_payco;
                        // $response = wp_remote_get(  $url );
                        // $body = wp_remote_retrieve_body( $response ); 
                        // $jsonData = @json_decode($body, true);
                        // $validationData = $jsonData['data'];
                        // $ref_payco = $validationData['x_ref_payco'];
                       //Validamos la firma
                        if ($order_id!="" && $x_ref_payco!="") {
                            $authSignature=$this->authSignature($x_ref_payco,$x_transaction_id,$x_amount, $x_currency_code);
                            $order = new WC_Order($order_id);
                        }
                       
                    }else{
                        $order_id = sanitize_text_field($_GET['order_id']);
                        $ref_payco = sanitize_text_field($_GET['ref_payco']);
                        if (!$ref_payco) {
                            $explode=explode('=',$order_id);
                            $ref_payco=$explode[1];
                            $explode2 = explode('?', $order_id );
                            $order_id=$explode2[0];
                        }
                        $url = 'https://secure.epayco.co/validation/v1/reference/'.$ref_payco;
                        $response = wp_remote_get(  $url );
                        $body = wp_remote_retrieve_body( $response ); 
                        $jsonData = @json_decode($body, true);
                        $validationData = $jsonData['data'];
                        $x_signature=trim($validationData['x_signature']);
                        $x_cod_transaction_state=(int)trim($validationData['x_cod_transaction_state']);
                        $x_ref_payco = trim($validationData['x_ref_payco']);
                        $x_transaction_id = trim($validationData['x_transaction_id']);
                        $x_amount = trim($validationData['x_amount']);
                        $x_currency_code = trim($validationData['x_currency_code']);
                        //Validamos la firma
                        if ($order_id!="" && $x_ref_payco!="") {
                        $authSignature=$this->authSignature($x_ref_payco,$x_transaction_id,$x_amount, $x_currency_code);
                        $order = new WC_Order($order_id);
                        }
                        
                }
                    if (!$x_ref_payco) {
                        $order = new WC_Order($order_id);
                        $message = 'Pago rechazado';
                        $messageClass = 'woocommerce-error';
                        $order->update_status('epayco-on-hold');
                        $order->add_order_note('Pago pendiente');                      
                        if($this->get_option('epayco_url_response_sub' ) == 0){    
                        $redirect_url = $order->get_checkout_order_received_url();
                        }else{
                        $woocommerce->cart->empty_cart();
                        $redirect_url = get_permalink($this->get_option('epayco_url_response_sub'));
                        }
                        $arguments=array();
                        // foreach ($validationData as $key => $value) {
                        // $arguments[$key]=$value;
                        // }
                        // unset($arguments["wc-api"]);
                        // $arguments['msg']=urlencode($message);
                        // $arguments['type']=$messageClass;
                        $redirect_url = add_query_arg($arguments , $redirect_url );
                        wp_redirect($redirect_url);
                        die();
                    }


                        $message = '';
                        $messageClass = '';
                        $current_state = $order->get_status();

                        if($authSignature == $x_signature){                
                        switch ($x_cod_transaction_state) {
                            case 1:{
                                //Busca si ya se descontó el stock
                                if (!EpaycoOrder::ifStockDiscount($order_id)) {
                                    //se descuenta el stock
                                if (EpaycoOrder::updateStockDiscount($order_id,1)) 
                                {
                                    $this->restore_order_stock($order_id,'decrease');
                                    }
                                }      
                                $message = 'Pago exitoso';
                                $messageClass = 'woocommerce-message';
                                $order->payment_complete($x_ref_payco);
                                $order->update_status($this->epayco_endorder_state);
                                $order->add_order_note('Pago exitoso');
                                echo "1";
                                }break;

                            case 2: {
                                if($current_state=="epayco-failed" || 
                                $current_state=="failed" || 
                                $current_state == "epayco-processing" || 
                                $current_state == "epayco-completed" || 
                                $current_state == "processing" || 
                                $current_state == "completed"){
                                }else{
                                $message = 'Pago rechazado' .$x_ref_payco;
                                $messageClass = 'woocommerce-error';
                                $order->update_status('epayco-failed');
                                $order->add_order_note('Pago fallido');
                                $this->restore_order_stock($order->id);
                                }
                                echo "2";
                            }break;
                            case 3:{
                            //Busca si ya se restauro el stock y si se configuro reducir el stock en transacciones pendientes  
                            if (!EpaycoOrder::ifStockDiscount($order_id) && $this->get_option('epayco_reduce_stock_pending') == 'yes') {
                                    //reducir el stock   
                            if (EpaycoOrder::updateStockDiscount($order_id,1)) {
                            $this->restore_order_stock($order_id,'decrease');
                                    }
                                }
                                $message = 'Pago pendiente de aprobación';
                                $messageClass = 'woocommerce-info';
                                $order->update_status('epayco-on-hold');
                                $order->add_order_note('Pago pendiente');
                                echo "3";
                            }break;
                            case 4:{
                                if($current_state == "epayco-processing" || 
                                $current_state == "epayco-completed" || 
                                $current_state == "processing" || 
                                $current_state == "completed"){
                                }else{
                                $message = 'Pago fallido' .$x_ref_payco;
                                $messageClass = 'woocommerce-error';
                                $order->update_status('epayco-failed');
                                $order->add_order_note('Pago fallido');
                                }
                                echo "4";
                            }break;
                            case 6:{
                                $message = 'Pago Reversada' .$x_ref_payco;
                                $messageClass = 'woocommerce-error';
                                $order->update_status('refunded');
                                $order->add_order_note('Pago Reversado');
                                echo "6";
                            }break;
                            case 11:{
                                $message = 'Pago Cancelado' .$x_ref_payco;
                                $messageClass = 'woocommerce-error';
                                $order->update_status('canceled');
                                $order->add_order_note('Pago Cancelado');
                                echo "11";
                            }break;
                            default:{
                                if(
                                    $current_state == "epayco-processing" || 
                                    $current_state == "epayco-completed" || 
                                    $current_state == "processing" || 
                                    $current_state == "completed"){

                                    }else{
                                    $message = 'Pago '.$_REQUEST['x_transaction_state'] . $x_ref_payco;
                                    $messageClass = 'woocommerce-error';
                                    $order->update_status('epayco-failed');
                                    $order->add_order_note($message);
                                   // $this->restore_order_stock($order->id);
                                    }
                                    echo "default";
                            }break;

                        }

                    //validar si la transaccion esta pendiente y pasa a rechazada y ya habia descontado el stock
                    if($current_state == 'on-hold' && ((int)$x_cod_transaction_state == 2 || (int)$x_cod_transaction_state == 4) && EpaycoOrder::ifStockDiscount($order_id)){
                        //si no se restauro el stock restaurarlo inmediatamente
                         $this->restore_order_stock($order_id);
                    };

                    }else {
                        $message = 'Firma no valida';
                        $messageClass = 'error';
                        // $order->update_status('failed');
                        // $order->add_order_note('Failed');
                        echo $message;
                        //$this->restore_order_stock($order_id);
                    }
                    if (isset($_REQUEST['confirmation'])) {
                        $redirect_url = get_permalink($this->get_option('epayco_url_confirmation'));
                        if ($this->get_option('epayco_url_confirmation' ) == 0) {
                            echo "ok";
                            die();
                        }
                    }else{
                        if ($this->get_option('epayco_url_response' ) == 0) {
                            $redirect_url = $order->get_checkout_order_received_url();
                        }else{
                            $woocommerce->cart->empty_cart();
                            $redirect_url = get_permalink($this->get_option('epayco_url_response'));
                        }
                    }

                    $arguments=array();

                    foreach ($validationData as $key => $value) {
                        $arguments[$key]=$value;
                    }

                    unset($arguments["wc-api"]);
                    $arguments['msg']=urlencode($message);
                    $arguments['type']=$messageClass;
                   // $redirect_url = add_query_arg($arguments , $redirect_url );
                    $response_data = $this->response_data == "yes" ? true : false;
                if ($response_data) {
                    $redirect_url = add_query_arg($arguments , $redirect_url );
                }
                    wp_redirect($redirect_url);
                    die();
                }


            /**
             * @param $order_id
             */
            public function restore_order_stock($order_id,$operation = 'increase')
            {
                //$order = new WC_Order($order_id);
                 $order = wc_get_order($order_id);
                if (!get_option('woocommerce_manage_stock') == 'yes' && !sizeof($order->get_items()) > 0) {
                    return;
                }

                foreach ($order->get_items() as $item) {
                    // Get an instance of corresponding the WC_Product object
                    $product = $item->get_product();
                    $qty = $item->get_quantity(); // Get the item quantity
                    wc_update_product_stock($product, $qty, $operation);
                }

            }


            public function string_sanitize($string, $force_lowercase = true, $anal = false) {

                $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<", ".", ">", "/", "?");
                $clean = trim(str_replace($strip, "", strip_tags($string)));
                $clean = preg_replace('/\s+/', "_", $clean);
                $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
                return $clean;
            }


            public function getTaxesOrder($order){
                $taxes=($order->get_taxes());
                $tax=0;
                foreach($taxes as $tax){
                    $itemtax=$tax['item_meta']['tax_amount'][0];
                }
                return $itemtax;
            }
        }


            function is_product_in_cart( $prodids ){
             $product_in_cart = false;
             foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
             $product = $cart_item['data'];
                 if ( in_array( $product->id, $prodids ) ) {
                      $product_in_cart = true;
             }
                        
             }
             return $product_in_cart;
            }
// Luego ya desactivamos la pasarela que queramos por ID de producto. Cambia los números de ID en el array $prodids
// function payment_gateway_disable_product( $available_gateways ) {
//  global $woocommerce;
//  $items = $woocommerce->cart->get_cart();
//  $suma=0;
//  foreach($items as $item => $value) {
//                 $_product =  wc_get_product( $value['data']->get_id());
//                 $price = get_post_meta($value['product_id'] , '_price', true);
//                    if($value['line_subtotal_tax']>0){
//                     $regulart_product_tax=$value['line_subtotal_tax'];
//                 }else{
//                      $regulart_product_tax=0;
//                 }
//                 $amount1=$price*$value['quantity'];
//                 $total=floatval($amount1+$regulart_product_tax);
//                 $product_ = $_product->get_data();
//                 $productName = json_decode(json_encode((object)$product_), FALSE);
//                 $suma += $total;
//                 }
//                 $product_price = $productName->price;
//                 $product_id = $productName->id;
//                 $epayco = new WC_ePayco();
//                 $monto = (int)$epayco->max_monto;
               
//                 if($suma>=$monto){
//                     $id_ = $product_id;
//                 }else{
//                     $id_ = null;
//                 }
              
//                  $prodids=array($id_);
//                  if ( isset( $available_gateways['epayco'] ) && is_product_in_cart( $prodids ) ) {
//                      unset(  $available_gateways['epayco'] );
//                  }
//                      return $available_gateways;
//                  }
//                 add_filter('woocommerce_available_payment_gateways','payment_gateway_disable_product' );


        /**
         * @param $methods
         * @return array
         */
        function woocommerce_epayco_add_gateway($methods)
        {
            $methods[] = 'WC_ePayco';
            return $methods;
        }
        add_filter('woocommerce_payment_gateways', 'woocommerce_epayco_add_gateway');

        function epayco_woocommerce_addon_settings_link( $links ) {
            array_push( $links, '<a href="admin.php?page=wc-settings&tab=checkout&section=epayco">' . __( 'Configuración' ) . '</a>' );
            return $links;
        }

        add_filter( "plugin_action_links_".plugin_basename( __FILE__ ),'epayco_woocommerce_addon_settings_link' );
        }


    //Actualización de versión
    global $epayco_db_version;
    $epayco_db_version = '1.0';
    //Verificar si la version de la base de datos esta actualizada 

    function epayco_update_db_check()
    {
        global $epayco_db_version;
        $installed_ver = get_option('epayco_db_version');
            EpaycoDokan::setup();
            EpaycoOrder::setup();
            
            update_option('epayco_db_version', $epayco_db_version);
        
    }


    add_action('plugins_loaded', 'epayco_update_db_check');

    function register_epayco_order_status() {
        register_post_status( 'wc-epayco-failed', array(
            'label'                     => 'ePayco Pago Fallido',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'ePayco Pago Fallido <span class="count">(%s)</span>', 'ePayco Pago Fallido <span class="count">(%s)</span>' )
        ));

        register_post_status( 'wc-epayco-canceled', array(
            'label'                     => 'ePayco Pago Cancelado',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'ePayco Pago Cancelado <span class="count">(%s)</span>', 'ePayco Pago Cancelado <span class="count">(%s)</span>' )
        ));

        register_post_status( 'wc-epayco-on-hold', array(
            'label'                     => 'ePayco Pago Pendiente',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'ePayco Pago Pendiente <span class="count">(%s)</span>', 'ePayco Pago Pendiente <span class="count">(%s)</span>' )
        ));

        register_post_status( 'wc-epayco-processing', array(
            'label'                     => 'ePayco Procesando Pago',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'ePayco Procesando Pago <span class="count">(%s)</span>', 'ePayco Procesando Pago <span class="count">(%s)</span>' )
        ));

         register_post_status( 'wc-processing', array(
            'label'                     => 'Procesando',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'Procesando<span class="count">(%s)</span>', 'Procesando<span class="count">(%s)</span>' )
        ));

        register_post_status( 'wc-epayco-completed', array(
            'label'                     => 'ePayco Pago Completado',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'ePayco Pago Completado <span class="count">(%s)</span>', 'ePayco Pago Completado <span class="count">(%s)</span>' )
        ));

        register_post_status( 'wc-completed', array(
            'label'                     => 'Completado',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'Completado<span class="count">(%s)</span>', 'Completado<span class="count">(%s)</span>' )
        ));
    }

    add_action( 'plugins_loaded', 'register_epayco_order_status' );

    function add_epayco_to_order_statuses( $order_statuses ) {
        $new_order_statuses = array();
        foreach ( $order_statuses as $key => $status ) {
            $new_order_statuses[ $key ] = $status;
            if ( 'wc-cancelled' === $key ) {
                $new_order_statuses['wc-epayco-cancelled'] = 'ePayco Pago Cancelado';
            }

            if ( 'wc-failed' === $key ) {
                $new_order_statuses['wc-epayco-failed'] = 'ePayco Pago Fallido';
            }

            if ( 'wc-on-hold' === $key ) {
                $new_order_statuses['wc-epayco-on-hold'] = 'ePayco Pago Pendiente';
            }

            if ( 'wc-processing' === $key ) {
               $new_order_statuses['wc-epayco-processing'] = 'ePayco Procesando Pago';
            }else {
                $new_order_statuses['wc-processing'] = 'Procesando';
            }

            if ( 'wc-completed' === $key ) {
                $new_order_statuses['wc-epayco-completed'] = 'ePayco Pago Completado';
            }else{
                $new_order_statuses['wc-completed'] = 'Completado';
            }
        }
        return $new_order_statuses;
    }

    add_filter( 'wc_order_statuses', 'add_epayco_to_order_statuses' );
    add_action('admin_head', 'styling_admin_order_list' );
    function styling_admin_order_list() {
        global $pagenow, $post;
        if( $pagenow != 'edit.php') return; // Exit
        if( get_post_type($post->ID) != 'shop_order' ) return; // Exit
        // HERE we set your custom status
        $order_status_failed = 'epayco-failed';
        $order_status_on_hold = 'epayco-on-hold';
        $order_status_processing = 'epayco-processing';
        $order_status_completed = 'epayco-completed';
        ?>

        <style>
            .order-status.status-<?php echo sanitize_title( $order_status_failed); ?> {
                background: #eba3a3;
                color: #761919;
            }
            .order-status.status-<?php echo sanitize_title( $order_status_on_hold); ?> {
                background: #f8dda7;
                color: #94660c;
            }
            .order-status.status-<?php echo sanitize_title( $order_status_processing ); ?> {
                background: #c8d7e1;
                color: #2e4453;
            }

            .order-status.status-<?php echo sanitize_title( $order_status_completed ); ?> {
                background: #d7f8a7;
                color: #0c942b;
            }
        </style>

        <?php
    }

}