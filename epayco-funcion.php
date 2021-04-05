<?php


function settings_fieldse($option_group) {

     ?>

<div class="wrap">

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
                          
                         
                                ?>
                                </table>
                                <center>
                                <div id="ruta" name="ruta" hidden="true">
                                    <?php   echo plugin_dir_url(__FILE__) .'lib/EpaycoUpdate.php';?>
                                </div>
                                <label>customer id</label>
                                <select name="attribute_taxonomy" class="attribute_taxonomy" id="mySelect2">
                                <?php
                                    global $wpdb;
                                    $table_name = $wpdb->prefix . "epayco_vendor";
                                    $sql = 'SELECT * FROM '.$table_name;
                                    $results = $wpdb->get_results($sql, OBJECT);
                                    // Array of defined attribute taxonomies.
                                    $attribute_taxonomies =  $results;
                                    if ( ! empty( $attribute_taxonomies ) ) {
                                        foreach ( $attribute_taxonomies as $tax ) {
                                            // var_dump($tax);
                                            $attribute_taxonomy_name = $tax->epayco_id;
                                            $label       =  $tax->epayco_id;
                                            echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
                                        }
                                    }
                                    ?>
                                
                                </select>
                               
             
                              
                                <select name="attribute_taxonomy" class="attribute_taxonomy" id="mySelect1" hidden="true">
                                <option value="fijo" selected>fijo</option>
                                <option value="porcentaje">porcentaje</option>
                                </select>
                                
                                <input type="text" name="valor" id="valor" hidden="true" value="1">
                                <label>correo</label>
                                <input type="text" name="correo" id="correo">
                                <button type="button" class="button add_attribute" onclick="myFunction()"><?php esc_html_e( 'Edit', 'woocommerce' ); ?></button>
                                <div class="" id="user-switching-installer-notice" style="padding: 3px 10px; position: relative; display: flex; align-items: center;" name="chec_">
                                    <p style="flex: 2;visibility: hidden;" name="guardado">Editado!</p>

                                </div>
                                </center> 
                                    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
                                    <script type="text/javascript">
                                        function myFunction() {
                                            //debugger
                                            var cust_id = document.getElementById("mySelect2").value;
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
                                                    //debugger
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
        </div>
<?php 
}
?>
