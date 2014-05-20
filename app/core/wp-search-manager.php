<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


use MavenEvents\Core\Domain\Event;
use MavenEvents\Core\Mappers\AttendeeMapper,
    MavenEvents\Core\Mappers\VenueMapper,
    MavenEvents\Core\Mappers\EventMapper;

class WpSearchManager implements iSearchManager{

	public function __construct() {
		;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\EventFilter $filter
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public function getEvents( \MavenEvents\Core\Domain\EventFilter $filter ){
		
		$wpSearchMapper = new Mappers\WpSearchMapper;
		$eventManager = new EventManager();
		
		$events = array( );

		$wpEvents = $wpSearchMapper->getEvents( $filter );
				
		if ( $wpEvents ) {
			foreach ( $wpEvents as $wpEvent ) {
				$events[] = $eventManager->getEventFromPost( $wpEvent );
			}
		}

		return $events;
		
	}
	 


}

