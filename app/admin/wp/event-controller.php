<?php

namespace MavenEvents\Admin\Wp;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class EventController extends \MavenEvents\Admin\EventsAdminController {

	public function __construct () {
		parent::__construct();
	}

	public static function init () {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$me = new self();
		add_action( 'add_meta_boxes', array( $me, 'addEvents' ) );
		add_action( 'admin_enqueue_scripts', array( $me, 'addScripts' ), 10, 1 );
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
	function addEvents () {
		add_meta_box( 'wpt_events_location', 'Event Information', array( $this, 'showEvents' ), \MavenEvents\Core\EventsConfig::eventTypeName, 'normal', 'default' );
	}

	// The Event Location Metabox
	function showEvents () {

		global $post;

		$event = new \MavenEvents\Core\Domain\Event();
		$event->setRegistrationStartDate('05/21/2015');
		$event->setRegistrationEndDate('05/21/2015');
		 
		$this->addJSONData( 'event', $event);
		
		echo $this->getOutput()->getWpAdminView( "event" );
	}

	/**
	 * update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save ( $postId, $post ) {
		
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
