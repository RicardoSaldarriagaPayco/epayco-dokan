<?php
function settings_fieldsese($option_group) {
?>

<div class="wrap">
        <h1><?php //esc_html(get_admin_page_title()); ?></h1>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

          

            

        <form action="admin.php?page=payco" method="post" id="formulario">
          <img src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png" class="img-responsive" alt="Responsive image">
        <br>
       <h1><strong> Configuracion de ePayco</strong></h1>
       <br>

<div class="container-fluid" style="color: #31708f; background-color: #d9edf7; border-color: #bce8f1;padding: 10px;border-radius: 5px;">
          <div class="row">
              <div class="col-md-10">
<b>
Este modulo Puedes listar de los vendedores asociados a ePayco quienes resibiran la comision de cada venta de acuerdo a la configuración en Dokan. 
</b>

               </div>
           </div>
       </div>

<br>

<div class="panel-body"style="padding: 15px 0;background: #fff;margin-top: 15px;border-radius: 5px;border: 1px solid #dcdcdc;border-top: 1px solid #dcdcdc; ">
  <table class="table table-striped">
  <thead>
      <tr>
        <th colspan="8">id Customer</th>
        <!-- <th colspan="4">Tipo</th>
        <th colspan="4">valor</th> -->
        <th colspan="8">Email</th>
      </tr>
    </thead>
    <tbody>
       <?php
        global $wpdb;
        $table = $wpdb->prefix.'epayco_vendor';
        $result = $wpdb->get_results ( "SELECT * FROM $table" );
        foreach ( $result as $print )  {
  ?>
   <tr>
    <td colspan="8"><?= $print->epayco_id ?></td>
    <!-- <td colspan="4"><?= $print->epayco_p ?></td>
    <td colspan="4"><?= $print->epayco_pr ?></td> -->
    <td colspan="8"><?= $print->correo ?></td>
  </tr>  
  <?php

} 
               
    ?>
    </tbody>
  </table>
       </div>

<?php
}
?>

<!-- <input type="submit" name="guardar" value="guardar cambios " class="btn btn-primary" id="guardar">
</form>
 -->
