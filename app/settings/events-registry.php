<?php

namespace MavenEvents\Settings;

use \Maven\Settings\Option;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class EventsRegistry extends \Maven\Settings\WordpressRegistry {

	/**
	 * 
	 * @var StatsRegistry 
	 */
	private static $instance;

	protected function __construct () {

		parent::__construct();
	}

	/**
	 *
	 * @return \MavenEvents\Settings\EventsRegistry
	 */
	static function instance () {
		if ( !isset( self::$instance ) ) {

			$adminEmail = get_bloginfo( 'admin_email' );


			$defaultOptions = array(
				new Option(
						"emailNotificationsTo", "Send email notifications to", $adminEmail, ''
				),
				new Option(
						"actions", "Actions", array(), ''
				),
				new Option(
						"eventsSlugPrefix", 'Events Url Prefix', ''
				),
				new Option(
						"eventsSlug", 'Events Url Prefix', 'event'
				)
			);


			self::$instance = new self( );
			self::$instance->setOptions( $defaultOptions );
		}

		return self::$instance;
	}

	function getEmailNotificationsTo () {

		return $this->getValue( 'emailNotificationsTo' );
	}

	public function getActions () {

		return $this->getValue( 'actions' );
	}

	public function isActionEnabled ( $actionName ) {
		$actions = $this->getActions();

		return ( isset( $actions[ $actionName ] ) );
	}

	public function getEventsSlugPrefix () {
		return $this->getValue( 'eventsSlugPrefix' );
	}

	public function getEventsSlug () {
		return $this->getValue( 'eventsSlug' );
	}

	public function getAttendeeStatusImagesUrl () {
		return $this->getImagesUrl() . "attendee-status/";
	}

	public function getBowerComponentUrl () {
		return $this->getPluginUrl() . "bower_components/";
	}

	public function getScriptsUrl () {
		return $this->getPluginUrl() . "scripts/";
	}
	
	public function getStylesUrl(){
		return $this->getPluginUrl() . "styles/";
	}

}
