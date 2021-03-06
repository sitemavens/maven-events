<?php

namespace MavenEvents\Admin\Wp;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VenueController extends \MavenEvents\Admin\EventsAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function init() {


		if ( $this->getRequest()->isDoingAutoSave() ) {
			return;
		}

		$this->getHookManager()->addAction( 'current_screen', array( $this, 'currentScreen' ) );

		//post_edit_form_tag
		$this->getHookManager()->addAction( 'add_meta_boxes_' . \MavenEvents\Core\EventsConfig::venueTypeName, array( $this, 'addEventsMetaBox' ) );
		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'addScripts' ), 10, 1 );
		//add_action('edit_form_advanced', array( $this, 'editFormBottom' ), 10, 1 );
		//add_action('add_meta_boxes',array( $this, 'editFormTop' ),10,2);

		$this->getHookManager()->addAction( 'save_post_' . \MavenEvents\Core\EventsConfig::venueTypeName, array( $this, 'save' ), 10, 2 );
		$this->getHookManager()->addAction( 'insert_post_' . \MavenEvents\Core\EventsConfig::venueTypeName, array( $this, 'insert' ), 10, 2 );
		$this->getHookManager()->addAction( 'delete_' . \MavenEvents\Core\EventsConfig::venueTypeName, array( $this, 'delete' ), 10, 3 );
	}

	public function currentScreen( $screen ) {

		if ( $screen->post_type === \MavenEvents\Core\EventsConfig::venueTypeName ) {
			$this->getHookManager()->addAction( 'admin_xml_ns', array( $this, 'adminXml' ) );
		}
	}

	function adminXml() {
		echo 'ng-app="mavenEventsApp"';
	}

	function addScripts( $hook ) {

		global $post;
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			if ( 'mvne_venue' === $post->post_type ) {

				$registry = \MavenEvents\Settings\EventsRegistry::instance();

				if ( $registry->isDevEnv() ) {
					wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
					wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'googlemap', '//maps.googleapis.com/maps/api/js?sensor=false', 'googlemap', $registry->getPluginVersion() );
					wp_enqueue_script( 'lodash-underscore', $registry->getBowerComponentUrl() . "lodash/dist/lodash.underscore.js", 'lodash', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-google-maps', $registry->getBowerComponentUrl() . "angular-google-maps/dist/angular-google-maps.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'mavenEventsApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'admin/venues/controllers/venue.js', $registry->getScriptsUrl() . "admin/venues/controllers/venue.js", 'mavenEventsApp', $registry->getPluginVersion() );
					wp_enqueue_script( 'admin/venues/services/geocoder.js', $registry->getScriptsUrl() . "admin/venues/services/geocoder.js", 'mavenEventsApp', $registry->getPluginVersion() );
					wp_enqueue_script( 'admin/global/directives/show-errors.js', $registry->getScriptsUrl() . "admin/global/directives/show-errors.js", 'mavenEventsApp', $registry->getPluginVersion() );


					wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
					wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

					wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
					
				} else {
					wp_enqueue_script( 'googlemap', '//maps.googleapis.com/maps/api/js?sensor=false', $registry->getPluginVersion() );
					wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", array('googlemap'), $registry->getPluginVersion() );
					wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
				}
			}
		}
	}

	// Add the Events Meta Boxes
	function addEventsMetaBox() {
		add_meta_box( 'wpt_events_location', 'General', array( $this, 'showEvents' ), \MavenEvents\Core\EventsConfig::venueTypeName, 'normal', 'default' );
	}

	// The Event Location Metabox
	function showEvents() {

		global $post;

		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\VenueController: showVenue: ' . $post->ID );
		$venueManager = new \MavenEvents\Core\VenueManager();
		$venue = $venueManager->get( $post->ID );
		$countries = \Maven\Core\CountriesApi::getAllCountries();
		$this->addJSONData( 'venue', $venue );
		$this->addJSONData( "cachedCountries", $countries );
		echo $this->getOutput()->getWpAdminView( "venue" );
	}

	/**
	 * Update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save( $postId, $post ) {
		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\VenueController: save: ' . $postId );

		$this->saveVenue( $post );
	}

	private function saveVenue( $post ) {

		$venue = new \MavenEvents\Core\Domain\Venue();

		$mvn = $this->getRequest()->getProperty( 'mvn' );

		//Check if we have something in the post, because it can be the quick edit mode


		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\VenueController: saveVenue: ' . $post->ID );

		$venue->load( $mvn[ 'venue' ] );

		$venue->setId( $post->ID );
		$venue->setName( $post->post_title );
		$venue->setDescription( $post->post_content );
		$venueManager = new \MavenEvents\Core\VenueManager();
		$venueManager->addVenue( $venue );
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert( $postId, $post ) {

		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\VenueController: insert' );

		$this->saveVenue( $post );
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete( $termId, $taxonomyId, $deletedTerm ) {
		
	}

	public function showForm() {
		
	}

	public function showList() {
		
	}

}
