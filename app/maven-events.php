<?php

/*
  Plugin Name: Maven Events
  Plugin URI:
  Description: Maven Events™
  Author: SiteMavens.com
  Version: 0.1
  Author URI:
 */

namespace MavenEvents;

use Maven\Core\Loader;
 

//If the validation was already loaded
if ( ! class_exists( 'MavenValidation' ) )
	require_once plugin_dir_path( __FILE__ ) . 'maven-validation.php';

// Check if Maven is activate, if not, just return.
if ( \MavenValidation::isMavenMissing() )
	return;



//Added some classes here, because there are issues with ReflectionClass on Settings controller , 'core/actions','core/domain/event-prices'
Loader::load( plugin_dir_path( __FILE__ ), array( 'settings/events-registry' ) );


// Instanciate the registry and set all the plugins attributes
$registry = Settings\EventsRegistry::instance();

$registry->setPluginDirectoryName( "maven-events" );
$registry->setPluginDir( plugin_dir_path( __FILE__ ) );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/maven-events/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginName( 'Maven Events' );
$registry->setPluginShortName( 'me' );
$registry->setPluginVersion( "0.1" );
$registry->setRequest( new \Maven\Core\Request() );

$registry->init();

/**
 * We need to register the namespace of the plugin. It will be used for autoload function to add the required files. 
 */
Loader::registerType( "MavenEvents", $registry->getPluginDir() );

Loader::load( $registry->getPluginDir(), 'core/installer.php' );

/**
 * 
 * Instantiate the installer 
 *
 * * */
$installer = new \MavenEvents\Core\Installer();
register_activation_hook( __FILE__, array( &$installer, 'install' ) );
register_deactivation_hook( __FILE__, array( &$installer, 'uninstall' ) );

/**
 *  Create the Director and the plugin
 */
$director = \Maven\Core\Director::getInstance();

$director->createPluginElements( $registry );

Front\EventsFrontEnd::registerFrontEndHooks();


// We need to initialize the custom post types
Core\EventsConfig::init();


//\MavenEvents\Api\Xmlrpc::init();

$hookManager = $director->getHookManager( $registry );
$hookManager->addFilter( 'maven\core\intelligenceReport:data', array( 'MavenEvents\\Core\\IntelligenceReport', 'generateData' ), 10, 2 );

// Load admin scripts, if we are in the admin 
if ( is_admin() ) {

	Admin\Main::init();
}


