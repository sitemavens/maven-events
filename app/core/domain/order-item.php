<?php

namespace MavenEvents\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class OrderItem extends \Maven\Core\Domain\OrderItem {
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\Event
	 */
	private $event;
	
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\Attendee[] 
	 */
	private $attendees;
	
	
	/**
	 * @serialized
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Event $event
	 */
	public function setEvent( \MavenEvents\Core\Domain\Event $event ) {
		$this->event = $event;
	}
	
	/**
	 * Get the attendees
	 * @return \MavenEvents\Core\Domain\Attendee[] 
	 */
	public function getAttendees() {

		if ( $this->attendees ) {
			return $this->attendees;
		}

		return array( );
	}
	
	/**
	 * Add an Attendee
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 */
	public function addAttendee( \MavenEvents\Core\Domain\Attendee $attendee ) {

		$this->attendees[ $attendee->getId() ] = $attendee;
	}

	public function hasAttendees() {
		return $this->attendees && count( $this->attendees ) > 0;
	}

	public function removeAttendee( \MavenEvents\Core\Domain\Attendee $attendeeToRemove ) {

		foreach ( $this->attendees as $attendee ) {

			if ( $attendee->getId() === $attendeeToRemove->getId() ) {

				unset( $this->attendees[ $attendee->getId() ] );
			}
		}
	}
	
	
}
