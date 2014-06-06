<?php

namespace MavenEvents\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Main {

	public static function init() {


		$eventController = new \MavenEvents\Admin\Wp\EventController();
		$eventController->init();

		//$eventListController = new Wp\EventListController();
		//$eventListController->init();
	}

	public static function registerStyles() {

		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();

		if ( $registry->isDevEnv() ) {
			wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
			wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

			wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
		} else {
			wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
		}
	}

	public static function registerScripts() {

		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();

		if ( $registry->isDevEnv() ) {
			
			wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'services/forms.js', $registry->getScriptsUrl() . "services/forms.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/main.js', $registry->getScriptsUrl() . "controllers/main.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/settings.js', $registry->getScriptsUrl() . "controllers/settings.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/main-nav.js', $registry->getScriptsUrl() . "controllers/main-nav.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/form-selector.js', $registry->getScriptsUrl() . "controllers/form-selector.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/date-selector.js', $registry->getScriptsUrl() . "controllers/date-selector.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'directives/focus.js', $registry->getScriptsUrl() . "directives/focus.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'directives/loading.js', $registry->getScriptsUrl() . "directives/loading.js", 'mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'directives/dynamic-name.js', $registry->getScriptsUrl() . "directives/dynamic-name.js", 'mainApp', $registry->getPluginVersion() );
		} else {
			wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
		}

		wp_localize_script( 'mainApp', 'GFSeoMk', array(
		    'adminUrl' => admin_url(),
		    'ajaxUrl' => admin_url( 'admin-ajax.php' )
		    
		) );
	}

	public static function registerMenu( $menus ) {

		$menus[] = array( "name" => "gf_ggseomarketing", "label" => "Marketing addon", "callback" => array( __CLASS__, 'showApp' ), "permission" => 'manage_options' );

		return $menus;


		//add_menu_page( 'Marketing Addon', 'GF SEO Marketing Addon', 'manage_options', 'ggseomarketing', array( __CLASS__, 'showApp' ) );
	}

	public static function showApp() {
		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();


		$fullPath = $registry->getPluginDir() . "index.html";
		$content = \GFSeoMarketingAddOn\Core\Loader::getFileContent( $fullPath );

		echo $content;
	}

}
