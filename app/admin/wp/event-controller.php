<?php

namespace MavenEvents\Admin\Wp;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class EventController extends \MavenEvents\Admin\EventsAdminController {

	public function __construct () {
		parent::__construct();
	}

	public function init () {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		//post_edit_form_tag
		add_action( 'add_meta_boxes_'.\MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'addEventsMetaBox' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'addScripts' ), 10, 1 );
		//add_action('edit_form_advanced', array( $this, 'editFormBottom' ), 10, 1 );
		//add_action('add_meta_boxes',array( $this, 'editFormTop' ),10,2);
		add_action('admin_xml_ns',array( $this, 'adminXml' ),10,2);
		$this->getHookManager()->addAction( 'save_post_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'save' ), 10, 2 );
		$this->getHookManager()->addAction( 'insert_post_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'insert' ), 10, 2 );
		$this->getHookManager()->addAction( 'delete_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'delete' ), 10, 3 );
	}

	function adminXml(){
		echo 'ng-app="mavenEventsApp"';
	}
	function addScripts ( $hook ) {

		global $post;

		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			if ( 'mvn_event' === $post->post_type ) {

				$registry = \MavenEvents\Settings\EventsRegistry::instance();

				wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
				wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );

				wp_enqueue_script( 'mavenEventsApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/controllers/event.js', $registry->getScriptsUrl() . "admin/controllers/event.js", 'mavenEventsApp', $registry->getPluginVersion() );
			}
		}
	}
	

	// Add the Events Meta Boxes
	function addEventsMetaBox () {
		add_meta_box( 'wpt_events_location', 'Event Information', array( $this, 'showEvents' ), \MavenEvents\Core\EventsConfig::eventTypeName, 'normal', 'default' );
	}

	// The Event Location Metabox
	function showEvents () {

		global $post;

		$eventManager = new \MavenEvents\Core\EventManager();
		$event = $eventManager->get( $post->ID );
		
		if ( $event->isEmpty() ){
			$event->setId( $post->ID );
		}

		$this->addJSONData( 'event', $event );

		echo $this->getOutput()->getWpAdminView( "event" );
	}

	/**
	 * Update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save ( $postId, $post ) {
 
		$event = new \MavenEvents\Core\Domain\Event();

		$mvn = $this->getRequest()->getProperty( 'mvn' );

		$event->load( $mvn[ 'event' ] );
 
		$event->setName( $post->post_title );
		$event->setDescription( $post->post_content );

		$eventManager = new \MavenEvents\Core\EventManager();
		$eventManager->addEvent( $event );
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert ( $postId, $post ) {
		
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete ( $termId, $taxonomyId, $deletedTerm ) {
		
	}

	public function showForm () {
		
	}

	public function showList () {
		
	}

}
