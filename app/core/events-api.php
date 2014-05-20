<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class EventsApi {

	/**
	 * 
	 * @param \MavenEvents\Core\EventFilter $filter
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public static function getEvents( \MavenEvents\Core\Domain\EventFilter $filter ) {

		$manager = null;
		
		if ( !$filter->useWpMethods() ) 
			$manager = new EventManager();
		else
			$manager = new WpSearchManager();
		
		return $manager->getEvents( $filter );
			
	}
	
	
	
	/**
	 * 
	 * @param int/object $event
	 */
	public static function getEvent( $event ){
		
		if ( !$event ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Event is required. It can be an id or a wp post' );
		}


		$manager = new \MavenEvents\Core\EventManager();
		
		if ( is_object( $event ) && isset( $event->ID ) ) {
			return $manager->getEventFromPost( $event );
		} else if ( is_numeric( $event ) ) {
			return $manager->get( $event );
		} else {
			return $manager->getEventBySlug( $event );
		}

		throw new \Maven\Exceptions\MavenException('Invalid event');
	}

	/**
	 * Create a new filter
	 * @return \MavenEvents\Core\Domain\EventFilter
	 */
	public static function newFilter() {
		return new \MavenEvents\Core\Domain\EventFilter();
	}
	
	public static function getVenues(){
		
		$manager = new \MavenEvents\Core\VenueManager();
		
		return $manager->getAll();
	}
	
	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public static function updateEvent( \MavenEvents\Core\Domain\Event $event ){
		
		$manager = new \MavenEvents\Core\EventManager();
		
		return $manager->addEvent( $event );
		
	}
	
	
	/**
	 * Add attendee to the event
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @return 
	 */
	public static function addAttendee( \MavenEvents\Core\Domain\Event $event, \MavenEvents\Core\Domain\Attendee $attendee ){
		
		$manager = new \MavenEvents\Core\EventManager();
		
		return $manager->addAttendee( $event, $attendee );
		
	}
	
	public static function getEventContent( $id ){
		$manager = new \MavenEvents\Core\EventContentManager();
		
		if ( is_numeric( $id ) )
			return $manager->get ( $id );
		
		return $manager->getEventContentBySlug( $id );
	}
	
	/**
	 * Set the primary attendee. He is the person who buys the tickets
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @param \MavenEvents\Core\Domain\Order $order
	 */
	public static function setPrimaryAttendee( \MavenEvents\Core\Domain\Attendee $attendee, \MavenEvents\Core\Domain\Event $event, \Maven\Core\Domain\Order $order ){
		
		$manager = new \MavenEvents\Core\EventManager();
		$manager->setPrimaryAttendee( $attendee, $event, $order );
		
	}
	
	/**
	 * Set event attendees as approved
	 * @param \MavenEvents\Core\Domain\Event $event
	 */
	public static function approveAttendees( \MavenEvents\Core\Domain\Event $event ){
		
		$manager = new \MavenEvents\Core\EventManager();
		$manager->approveAttendees( $event );
		
		
	}

}