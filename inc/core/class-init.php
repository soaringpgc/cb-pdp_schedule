<?php

namespace CB_PDP_schedule\Inc\Core;
use CB_PDP_schedule as NS;
use CB_PDP_schedule\Inc\Admin as Admin;
use CB_PDP_schedule\Inc\Frontend as Frontend;
use CB_PDP_schedule\Inc\Rest as Rest;

/**
 * The core plugin class.
 * Defines internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * @link       http://pgcsoaring.com
 * @since      1.0.0
 *
 * @author     Philadelphia Glider Council -- Dave Johnson
 */
class Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_base_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_basename;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The text domain of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $plugin_text_domain;

	/**
	 * Initialize and define the core functionality of the plugin.
	 */
	public function __construct() {

		$this->plugin_name = NS\PLUGIN_NAME;
		$this->version = NS\PLUGIN_VERSION;
		$this->plugin_basename = NS\PLUGIN_BASENAME;
		$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		$this->load_dependencies();

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		$this->define_rest_hooks();

	}

	/**
	 * Loads the following required dependencies for this plugin.
	 *
	 * - Loader - Orchestrates the hooks of the plugin.
	 * - Internationalization_I18n - Defines internationalization functionality.
	 * - Admin - Defines all hooks for the admin area.
	 * - Frontend - Defines all hooks for the public side of the site.
	 *
	 * @access    private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access    private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_I18n( $this->plugin_text_domain );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	public function define_rest_hooks() {

		$plugin_rest = new Rest\Calendar( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');

 		$plugin_rest = new Rest\Vacation( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');
 
 		$plugin_rest = new Rest\Trades( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');
 
 		$plugin_rest = new Rest\Field_Duty( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');

		$plugin_rest = new Rest\Instruction_type( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');
 
		$plugin_rest = new Rest\Instruction( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/*
		 * Additional Hooks go here
		 *
		 * e.g.
		 *
		 * //admin menu pages
		 * $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
		 *
		 *  //plugin action links
		 * $this->loader->add_filter( 'plugin_action_links_' . $this->plugin_basename, $plugin_admin, 'add_additional_action_link' );
		 *
		 */
		 $this->loader->add_filter( 'cb_admin_add_config', $plugin_admin, 'add_admin_tab_calendar' );
		 $this->loader->add_action( 'admin_post_schedule_setup', $plugin_admin, 'cb_schedule_setup_response');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_public_hooks() {

 		$plugin_public = new Frontend\Frontend( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );
 		
 		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
 		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );	
					
// scheduling actions
		$this->loader->add_action( 'admin_post_cb_pdp_training_request', $plugin_public, 'cb_pdp_training_request' );
		$this->loader->add_action( 'admin_post_nopriv_cb_pdp_training_request', $plugin_public, 'cb_pdp_no_login' );

		$this->loader->add_action( 'admin_post_pdp_cfig_schedule', $plugin_public, 'pdp_cfig_schedule' );
		$this->loader->add_action( 'admin_post_nopriv_pdp_cfig_schedule', $plugin_public, 'pdp_no_login' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the text domain of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The text domain of the plugin.
	 */
     
      public function get_plugin_text_domain() {
     		return $this->plugin_text_domain;
      }
}
