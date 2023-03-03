<?php

namespace CB_PDP_schedule\Inc\Core;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://pgcsoaring.com
 * @since      1.0.0
 *
 * @author     Philadelphia Glider Council -- Dave Johnson
 **/
class Activator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$min_php = '7.4.0';
		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'This plugin requires a minmum PHP Version of ' . $min_php );
		}
		if( !class_exists( 'Cloud_Base_Admin' ) ) {
       		deactivate_plugins( plugin_basename( __FILE__ ) );
        	wp_die( __( 'Please install and Activate Cloud Base.', 'cb-pdp_schedule' ), 'Plugin dependency check', array( 'back_link' => true ) );
    	}
    	create_cb_scheduling_database();
    	set_default_cb_schedule_configuration();
	}
}
 
function create_cb_scheduling_database(){
   	global $wpdb;
   	$charset_collate = $wpdb->get_charset_collate();
   	$db_version = 0.36;
   	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
   	if (get_option("cloud_base_schedule_db_version") != $db_version){ 
      $table_name = $wpdb->prefix . "cloud_base_calendar";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	calendar_date date DEFAULT NULL,
      	session smallint(6),
      	scheduling BOOLEAN,
      	tow_scheduling BOOLEAN,
      	instructor_scheduling BOOLEAN,
      	manager_scheduling BOOLEAN,
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	
      
     $table_name = $wpdb->prefix . "cloud_base_vacation";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	cfig_id int(10) UNSIGNED NOT NULL,
      	vacation_date int(10),
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	
      
      $table_name = $wpdb->prefix . "cloud_base_instruction";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	member_id int(10) UNSIGNED NOT NULL,
      	member_weight int(5),
      	enter_date date,
      	request_date int,
      	cfig1_id int(10),
      	cfig_confirmed boolean,
      	cfig2_id int(10),
      	assigned_cfig_id int(10),
      	scheduling_assistance boolean,
      	request_notes tinytext,
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	            
      
      $table_name = $wpdb->prefix . "cloud_base_instruction_type";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	request_type tinytext,
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	            

      $table_name = $wpdb->prefix . "cloud_base_field_duty";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	calendar_id int(10) UNSIGNED NOT NULL,
      	trade int(5),
      	member_id int,
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	            
      
      $table_name = $wpdb->prefix . "cloud_base_trades";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	trade tinytext,
      	role varchar(40),
      	authority varchar(30),
      	overrideauthority varchar(30),
      	sessionmax int,
      	yearmin int,
      	PRIMARY KEY  (id)
      );" . $charset_collate  . ";";
      dbDelta($sql);	      

	 // prepopulate with default trade types 
	$sql =  $wpdb->prepare("INSERT INTO {$table_name} (id, trade, authority, overrideauthority, sessionmax, yearmin ) 
	VALUES (%d, %s, %s, %s, %d, %d ) ON DUPLICATE KEY UPDATE id=id", '1', 'Tow Pilot', 'edit_gc_tow', 'chief_tow','0', '0');	
	$wpdb->query($sql);		
	$sql =  $wpdb->prepare("INSERT INTO {$table_name} (id, trade, authority, overrideauthority, sessionmax, yearmin ) 
	VALUES (%d, %s, %s, %s, %d, %d ) ON DUPLICATE KEY UPDATE id=id ", '2', 'Instructor', 'chief_flight', 'chief_flight','0', '0');	
	$wpdb->query($sql);		
	$sql =  $wpdb->prepare("INSERT INTO {$table_name} (id, trade, authority, overrideauthority, sessionmax, yearmin ) 
	VALUES (%d, %s, %s, %s, %d, %d ) ON DUPLICATE KEY UPDATE id=id", '3', 'Field Manager', 'read', 'edit_gc_operations','1', '3');	
	$wpdb->query($sql);		
	$sql =  $wpdb->prepare("INSERT INTO {$table_name} (id, trade, authority, overrideauthority, sessionmax, yearmin ) 
	VALUES (%d, %s, %s, %s, %d, %d ) ON DUPLICATE KEY UPDATE id=id", '4', 'Assistant Manager', 'read', 'edit_gc_operations','1', '3');	
	$wpdb->query($sql);		

      
	//  Set the version of the Database
	update_option("cloud_base_schedule_db_version", $db_version);
	}
}
function set_default_cb_schedule_configuration(){

//	if ( get_option('cloudbase_tp_weekly') == false ){	
		$cb_tp_weekly = array( '1', '0', '0', '1', '0', '0', '1' );
		$cb_ins_weekly = array( '1','0', '0', '1', '0', '0', '1' );
		$cb_fm_weekly = array( '1', '0', '0', '1', '0', '0', '1' );
		$cb_weekly = array( $cb_tp_weekly, $cb_ins_weekly, $cb_fm_weekly );
		update_option('cloudbase_tp_weekly', $cb_weekly, false );		
		update_option('cloudbase_enabled_sessions', array('0','0','0') );								    
//	}
	
}
