<?php

/**
 * Clase en donde se guardan las transacciones
 */
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');

class EpaycoDokan{
	public $id;
	public $id_payco;
	public $order_id;
	public $order_stock_restore;
	public $order_stock_discount;
	public $order_status;

	public static function createVendor($epayco_id, $epayco_p, $email, $epayco_pr)
	{
	
		global $wpdb;
		$table_name = $wpdb->prefix . "epayco_vendor";
	  	$result = $wpdb->insert( $table_name, 
		    array( 
		      'epayco_id' => strval($epayco_id), 
		      'epayco_p' => strval($epayco_p),
		      'correo' => $email, 
		      'epayco_pr' => $epayco_pr,
		      'epayco_pk'=> 'epayco_pk'
		    )
	  	);
	  	   if($result)
				{
					echo 1;
			   		die();
					return true;  
				}else{
					echo 0;
			   		die();
					return false; 
				}
	}

	public static function updateVendor($epayco_id, $epayco_p, $email, $epayco_pr)
	{

		global $wpdb;
		$table_name = $wpdb->prefix . "epayco_vendor";
	  	$result = $wpdb->update( $table_name,
		   array(
			    'epayco_p' => strval($epayco_p),
				'correo' => $email, 
				'epayco_pr' => $epayco_pr,
				'epayco_pk'=> 'epayco_pk'
			), 
		   array('epayco_id'=>strval($epayco_id)) );
		   if($result)
		   {
			   echo 1;
			   die();
			   return true;  
		   }else{
				echo 0;
				die();
			   return false; 
		   }

	}

	public static function deleteVendor($epayco_id)
	{
		global $wpdb;
	
		$table_name = $wpdb->prefix . "epayco_vendor";
		$sql = 'DELETE FROM '.$table_name.' WHERE epayco_id ='.$epayco_id;
		$results = $wpdb->get_results($sql, OBJECT);
		if($result)
		{
			echo 0;
			die();
			return true;  
		}else{
			 echo 1;
			 die();
			return false; 
		}
		// $sql = array(
		// 	'DELETE FROM '.$table_name.'WHERE epayco_id ='.$epayco_id
		// );
		// foreach ($sql as $query) {
		// 	if (Db::getInstance()->execute($query) == false) {
		// 		echo 0;
		// 		die();
		// 		return false;
		// 	}else{
		// 		echo 1;
		// 		die();
		// 		return true;
		// 	}
		// }

	}


	public static function ifExistVendor($orderId)
	{
		global $wpdb;
    	$table_name = $wpdb->prefix . "epayco_vendor";
		$sql = 'SELECT * FROM '.$table_name.' WHERE epayco_id ='.$orderId;
		 $results = $wpdb->get_results($sql, OBJECT);
		if (count($results) > 0)
			return true;
		return false;
	}

	/**
	 * Crear la tabla en la base de datos.
	 * @return true or false
	 */
	public static function setup()
	{
		
	global $wpdb;
	$table_name = $wpdb->prefix . "epayco_vendor";
	    $charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE IF NOT  EXISTS $table_name (
		    id INT NOT NULL AUTO_INCREMENT,
		    epayco_id VARCHAR(20) NULL,
		    epayco_p VARCHAR(200) NULL,
		    correo VARCHAR(200)  NULL,
		    epayco_pr VARCHAR(200)  NULL,
		    epayco_pk VARCHAR(200)  NULL,
		    PRIMARY KEY (id)
	  	) $charset_collate;";

	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	}



	/**
	 * Borra la tabla en la base de datos.
	 * @return true or false
	 */
	public static function remove(){
		$sql = array(
				'DROP TABLE IF EXISTS '._DB_PREFIX_.'epayco_vendor'
		);
		foreach ($sql as $query) {
		    if (Db::getInstance()->execute($query) == false) {
		        return false;
		    }
		}
	}
}