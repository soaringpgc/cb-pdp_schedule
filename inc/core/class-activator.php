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

		$min_php = '5.6.0';
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
	}
}
 
function create_cb_scheduling_database(){
   	global $wpdb;
   	$charset_collate = $wpdb->get_charset_collate();
   	$db_version = 0.2;
   	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
   	if (get_option("cloud_base_schedule_db_version") != $db_version){ 
      $table_name = $wpdb->prefix . "cloud_base_calendar";
      // create basic calendar
      $sql = "CREATE TABLE ". $table_name . " (
      	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      	calendar_date date DEFAULT NULL,
      	session smallint(6),
      	scheduling BOOLEAN,
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
      
	//  Set the version of the Database
	update_option("cloud_base_schedule_db_version", $db_version);
	}
}
