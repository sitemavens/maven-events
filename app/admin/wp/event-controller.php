<?php

namespace MavenEvents\Admin\Wp;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class EventController extends \MavenEvents\Admin\EventsAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function init() {


		if ( $this->getRequest()->isDoingAutoSave() ) {
			return;
		}

		$this->getHookManager()->addAction( 'current_screen', array( $this, 'currentScreen' ) );

		//post_edit_form_tag
		$this->getHookManager()->addAction( 'add_meta_boxes_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'addEventsMetaBox' ) );
		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'addScripts' ), 10, 1 );
		//add_action('edit_form_advanced', array( $this, 'editFormBottom' ), 10, 1 );
		//add_action('add_meta_boxes',array( $this, 'editFormTop' ),10,2);

		$this->getHookManager()->addAction( 'save_post_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'save' ), 10, 2 );
		$this->getHookManager()->addAction( 'insert_post_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'insert' ), 10, 2 );
		$this->getHookManager()->addAction( 'delete_' . \MavenEvents\Core\EventsConfig::eventTypeName, array( $this, 'delete' ), 10, 3 );
	}

	public function currentScreen( $screen ) {

		if ( $screen->post_type === \MavenEvents\Core\EventsConfig::eventTypeName ) {
			$this->getHookManager()->addAction( 'admin_xml_ns', array( $this, 'adminXml' ) );
		}
	}

	function adminXml() {
		echo 'ng-app="mavenEventsApp"';
	}

	function addScripts( $hook ) {

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


				wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
				wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

				wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
			}
		}
	}

	// Add the Events Meta Boxes
	function addEventsMetaBox() {
		add_meta_box( 'wpt_events_location', 'Event Information', array( $this, 'showEvents' ), \MavenEvents\Core\EventsConfig::eventTypeName, 'normal', 'default' );
	}

	// The Event Location Metabox
	function showEvents() {

		global $post;

		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\EventController: showEvents: ' . $post->ID );

		$eventManager = new \MavenEvents\Core\EventManager();
		$event = $eventManager->get( $post->ID );

		$combinations = array();
		if ( $event->isEmpty() ) {
			$event->setId( $post->ID );
		} else {
			$combinations = $this->getCombinations( $event->getId() );
		}

		$this->addJSONData( 'event', $event );

		$pricesOperators = \Maven\Core\Domain\VariationOptionPriceOperator::getOperators();

		$this->addJSONData( 'priceOperators', $pricesOperators );

		$this->addJSONData( 'defaultPriceOperator', \Maven\Core\Domain\VariationOptionPriceOperator::NoChange );

		$this->addJSONData( 'combinations', $combinations );

		echo $this->getOutput()->getWpAdminView( "event" );
	}

	/**
	 * Update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save( $postId, $post ) {

		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\EventController: save: ' . $postId );

		$this->saveEvent( $post );
	}

	private function getCombinations( $thingId ) {
		$variationGroupManager = new \MavenEvents\Core\VariationGroupManager();
		$variationOptionManager = new \Maven\Core\VariationOptionManager();
		$groups = $variationGroupManager->getVariationGroups( $thingId );

		foreach ( $groups as $group ) {
			$combination = array();
			$combination[ 'id' ] = $group->getId();
			$combination[ 'groupKey' ] = $group->getGroupKey();
			$combination[ 'price' ] = $group->getPrice();
			$combination[ 'priceOperator' ] = $group->getPriceOperator();

			$options = array();
			$keys = explode( '-', $group->getGroupKey() );
			foreach ( $keys as $key ) {
				$option = $variationOptionManager->get( $key );

				$options[] = array(
				    'id' => $option->getId(),
				    'name' => $option->getName(),
				    'variationId' => $option->getVariationId(),
				    'variation' => ''
				);
			}
			$combination[ 'options' ] = $options;

			$combinations[ $group->getGroupKey() ] = $combination;
		}
		return $combinations;
	}

	private function saveEvent( $post ) {

		$event = new \MavenEvents\Core\Domain\Event();

		$mvn = $this->getRequest()->getProperty( 'mvn' );

		//Check if we have something in the post, because it can be the quick edit mode
		if ( $mvn ) {

			\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\EventController: saveEvent: ' . $post->ID );

			$event->load( $mvn[ 'event' ] );

			$event->setId( $post->ID );
			$event->setName( $post->post_title );
			$event->setDescription( $post->post_content );

			$eventManager = new \MavenEvents\Core\EventManager();
			$eventManager->addEvent( $event );

			$variationManager = new \MavenEvents\Core\VariationManager();
			$variations = $variationManager->saveMultiple( $event->getVariations(), $event->getId() );

			$combinations = $mvn[ 'event' ][ 'combinations' ];
			if ( $combinations ) {
				$variationGroupManager = new \MavenEvents\Core\VariationGroupManager();

				foreach ( $combinations as $combination ) {
					foreach ( $combination->options as $option ) {
						
					}
				}
			}

			//$event->setVariations($variations);
		}
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert( $postId, $post ) {

		\Maven\Loggers\Logger::log()->message( '\MavenEvents\Admin\Wp\EventController: insert' );

		$this->saveEvent( $post );
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
