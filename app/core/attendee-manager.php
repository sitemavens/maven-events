<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AttendeeManager {

	private $mapper;

	public function __construct() {
		$this->mapper = new Mappers\AttendeeMapper();
	}

	public function get( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee Id is required' );


		return $this->mapper->get( $id );
	}

	public function getByRegistration( $id ) {
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee Id is required' );

		$attendee = $this->mapper->getByRegistration( $id );

		//Add event data
		if ( $attendee->getEventId() ) {
			$eventManager = new EventManager();

			$event = $eventManager->get( $attendee->getEventId() );

			$attendee->setEvent( $event );
		}
		//Add order data
		if ( $attendee->getOrderId() ) {
			$orderManager = new OrderManager();

			$order = $orderManager->get( $attendee->getOrderId() );

			$attendee->setOrder( $order );
		}

		return $attendee;
	}

	public function getByEmail( $email ) {

		if ( ! $email )
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee email is required' );

		return $this->mapper->getByEmail( $email );
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Attendee or array $attendee
	 * @return \Maven\Core\Message\Message
	 */
	public function addAttendee( $attendee ) {

		$attendeeToUpdate = null;

		if ( is_array( $attendee ) ) {
			$attendeeToUpdate = new Domain\Attendee();
			\Maven\Core\FillerHelper::fillObject( $attendeeToUpdate, $attendee );
		}
		else
			$attendeeToUpdate = $attendee;

		$attendeeMapper = new Mappers\AttendeeMapper();

		$forUpdate = (( int ) $attendeeToUpdate->getId()) > 0;

		$attendeeToUpdate = $attendeeMapper->save( $attendeeToUpdate );

		Actions::updateAttendee( $attendeeToUpdate, $forUpdate );

		return $attendeeToUpdate;
	}

	public function addAttendees( $attendees, \MavenEvents\Core\Domain\Event $event ) {

		return $this->mapper->addAttendees( $attendees, $event );
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Event | int $event
	 * @return \MavenEvents\Core\Event
	 * @throws \Maven\Exceptions\RequiredException
	 */
	public function getEventAttendees( $event ) {

		$eventId = false;

		if ( $event && $event instanceof \MavenEvents\Core\Domain\Event )
			$eventId = $event->getId();
		else
			$eventId = $event;

		if ( ! $eventId )
			throw new \Maven\Exceptions\RequiredException( 'Event is required' );

		$attendees = $this->mapper->getEventAttendees( $eventId );

		$attendeeStatusManager = new AttendeeStatusManager();

		// Complete the status with the name and image url information
		foreach ( $attendees as $attendee ) {

			if ( $attendee->getStatus()->getId() )
				$attendee->setStatus( $attendeeStatusManager->completeInfo( $attendee->getStatus() ) );
		}


		return $attendees;
	}

	public function getEventRegistrationDetails( \MavenEvents\Core\Domain\Event $event, \MavenEvents\Core\Domain\Attendee $attendee ) {

		return $this->mapper->getEventRegistrationDetails( $event, $attendee );
	}

	public function updateRegistrationAttendeeStatus( \MavenEvents\Core\Domain\AttendeeRegisteredEvent $registeredEvent ) {

		return $this->mapper->updateRegistrationAttendeeStatus( $registeredEvent );
	}

	public function getAll() {


		return $this->mapper->getAll();
	}

	/**
	 * Check if an attendee is already registered
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @return boolean
	 */
	public function isAlreadyRegistered( \MavenEvents\Core\Domain\Attendee $attendee, \MavenEvents\Core\Domain\Event $event ) {

		$this->mapper = new Mappers\AttendeeMapper();

		if ( ! $attendee->getId() && ! $attendee->getEmail() )
			throw new \Maven\Exceptions\RequiredException( 'Attendee id or email is required' );

		if ( ! $attendee->getId() && $attendee->getEmail() ) {

			$attendeeAux = $this->mapper->getByEmail( $attendee->getEmail() );

			if ( ! $attendeeAux )
				return false;

			$attendee->setId( $attendeeAux->getId() );
		}

		$registeredEvents = $this->mapper->getRegisteredEvents( $attendee );

		foreach ( $registeredEvents as $registeredEvent ) {

			if ( $event->getId() === $registeredEvent->getId() )
				return true;
		}

		return false;
	}

	public function delete( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );

		$attendeeMapper = new Mappers\AttendeeMapper();

		return $attendeeMapper->remove( $id );
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\AttendeeFilter  $filter
	 * @return \MavenEvents\Core\Domain\Attendee[]
	 */
	public function getAttendees( \MavenEvents\Core\Domain\AttendeeFilter $filter, $orderBy = 'id', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$mapper = new Mappers\AttendeeMapper();

		return $mapper->getAttendees( $filter, $orderBy, $orderType, $start, $limit );

		//if ( $filter->getNumber() )
		//	return $this->getEventsByNumber( $filter->getNumber(), $filter->getPluginKey() );
	}

	public function getAttendeesCount( \MavenEvents\Core\Domain\AttendeeFilter $filter ) {

		$mapper = new Mappers\AttendeeMapper();

		return $mapper->getAttendeesCount( $filter );

		//if ( $filter->getNumber() )
		//	return $this->getEventsByNumber( $filter->getNumber(), $filter->getPluginKey() );
	}

}

