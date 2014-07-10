<?php

namespace MavenEvents\Core\Mappers;

use \MavenEvents\Core\EventsConfig;

class AttendeeMapper extends \Maven\Core\Mappers\ProfileMapper {

	private $attendeesTable;
	private $eventsTable;
	private $eventsAttendeesTable;
	private $profilesTable;

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::attendeesTableName );

		$this->attendeesTable = EventsConfig::attendeesTableName;
		$this->eventsAttendeesTable = EventsConfig::eventsAttendeesTableName;
		$this->eventsTable = EventsConfig::eventTableName;
		$this->profilesTable = \Maven\Core\Mappers\ProfileMapper::getTableName();
	}

	public function getAll( $orderBy = "id" ) {
		$instances = array( );
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {

			$instance = new \MavenEvents\Core\Domain\Attendee();

			$this->fillObject( $instance, $row );

			$this->loadProfile( $instance );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	/**
	 * 
	 * @param int $eventId
	 * @return \MavenEvents\Core\Domain\Attendee[]
	 */
	public function getEventAttendees( $eventId ) {

		$instances = array( );

		$query = "SELECT {$this->attendeesTable}.*, number_of_tickets, amount_paid, checked_in, order_id, status_id, status_description
					FROM {$this->attendeesTable}
					INNER JOIN {$this->eventsAttendeesTable} on {$this->attendeesTable}.id = {$this->eventsAttendeesTable}.attendee_id
					WHERE event_id = %d";

		$results = $this->getQuery( $this->prepare( $query, $eventId ) );

		foreach ( $results as $row ) {

			$instance = new \MavenEvents\Core\Domain\Attendee();

			$this->fillObject( $instance, $row );

			$status = new \MavenEvents\Core\Domain\AttendeeStatus();
			$status->setDescription( $row->status_description );
			$status->setId( $row->status_id );

			$instance->setStatus( $status );

			$this->loadProfile( $instance );

			$instances[ ] = $instance;
		}
		return $instances;
	}

	public function getAttendees( \MavenEvents\Core\Domain\AttendeeFilter $filter, $orderBy = 'id', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$registry = \MavenEvents\Settings\EventsRegistry::instance();

		$where = '';
		$values = array( );
		//first value is plugin key

		if ( ! $filter->getAll() ) {

			$event = $filter->getEvent();

			if ( $event ) {
				$values[ ] = $event;
				$where.=" AND event_id = %s";
			}

			$status = $filter->getStatus();
			if ( $status ) {
				$values[ ] = $status;
				$where.=" AND status_id = %s";
			}
			
			$email=$filter->getEmail();
			if($email){
				$values[ ] = "%{$email}%";
				$where.=" AND {$this->profilesTable}.email like %s";
			}
		}

		if ( ! $orderBy ) {
			$orderBy = 'id';
		} else if ( $orderBy == 'email' ) {
			$orderBy = "{$this->profilesTable}.email";
		} else if ( $orderBy == 'name' ) {
			$orderBy = "last_name, first_name";
		}

		$query = "SELECT {$this->attendeesTable}.*,{$this->eventsAttendeesTable}.id as reg_id, number_of_tickets, amount_paid, checked_in, order_id, status_id, status_description
					FROM {$this->attendeesTable}
					INNER JOIN {$this->eventsAttendeesTable} on {$this->attendeesTable}.id = {$this->eventsAttendeesTable}.attendee_id
					INNER JOIN {$this->profilesTable} on {$this->profilesTable}.id	= {$this->attendeesTable}.profile_id
					where 1=1 {$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		//other values
		/* $values[ ] = $orderBy;
		  $values[ ] = $orderType; */
		$values[ ] = $start;
		$values[ ] = $limit;

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$instances = array( );
		foreach ( $results as $row ) {

			$instance = new \MavenEvents\Core\Domain\Attendee();

			$this->fillObject( $instance, $row );

			$status = new \MavenEvents\Core\Domain\AttendeeStatus();
			$status->setDescription( $row->status_description );
			$status->setId( $row->status_id );
			$status->setImageUrl( $registry->getAttendeeStatusImagesUrl() . $row->status_id . '.png' );

			$instance->setStatus( $status );

			$this->loadProfile( $instance );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getAttendeesCount( \MavenEvents\Core\Domain\AttendeeFilter $filter ) {

		$where = '';
		$values = array( );

		if ( ! $filter->getAll() ) {

			$event = $filter->getEvent();

			if ( $event ) {
				$values[ ] = $event;
				$where.=" AND event_id = %s";
			}

			$status = $filter->getStatus();
			if ( $status ) {
				$values[ ] = $status;
				$where.=" AND status_id = %s";
			}
            
            $email=$filter->getEmail();
			if($email){
				$values[ ] = "%{$email}%";
				$where.=" AND {$this->profilesTable}.email like %s";
			}
		}

		$query = "select	count(*)
					FROM {$this->attendeesTable}
					INNER JOIN {$this->eventsAttendeesTable} on {$this->attendeesTable}.id = {$this->eventsAttendeesTable}.attendee_id
					INNER JOIN {$this->profilesTable} on {$this->profilesTable}.id	= {$this->attendeesTable}.profile_id
					where 1=1 {$where}";


		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}

	public function updateRegistrationAttendeeStatus( \MavenEvents\Core\Domain\AttendeeRegisteredEvent $registeredEvent ) {

		$registeredEvent->sanitize();

		$query = "UPDATE {$this->eventsAttendeesTable} SET status_id = %s, status_description = %s where attendee_id = %d and event_id = %d ";

		$this->executeQuery( $this->prepare( $query, $registeredEvent->getStatus()->getId(), $registeredEvent->getStatus()->getDescription(), $registeredEvent->getAttendee()->getId() ? $registeredEvent->getAttendee()->getId() : $registeredEvent->getAttendeeId(), $registeredEvent->getEvent()->getId() ? $registeredEvent->getEvent()->getId() : $registeredEvent->getEventId()
			)
		);

		return $registeredEvent;
	}

	public function getEventRegistrationDetails( \MavenEvents\Core\Domain\Event $event, \MavenEvents\Core\Domain\Attendee $attendee ) {

		$event->sanitize();
		$attendee->sanitize();

		$query = "SELECT * FROM {$this->eventsAttendeesTable} where attendee_id = %d and event_id = %d ";

		$row = $this->getQueryRow( $this->prepare( $query, $attendee->getId(), $event->getId() ) );


		if ( $row ) {

			$instance = new \MavenEvents\Core\Domain\AttendeeRegisteredEvent();

			$instance->getStatus()->setId( $row->status_id );
			$instance->getStatus()->setDescription( $row->status_description );

			$instance->setId( $row->id );
			$instance->setNumberOfTickets( $row->number_of_tickets );
			$instance->setAmountPaid( $row->amount_paid );
			$instance->setOrderId( $row->order_id );
			$instance->setPrimaryAttendeeId( $row->primary_attendee_id );

			$instance->getEvent()->setId( $row->event_id );
			$instance->setEventId( $row->event_id );

			$instance->getAttendee()->setId( $row->attendee_id );
			$instance->setAttendeeId( $row->attendee_id );


			return $instance;
		}

		return false;
	}

	/**
	 * Get registered events
	 * @param \MavenEvents\Core\Domain\Attendee $attende
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public function getRegisteredEvents( \MavenEvents\Core\Domain\Attendee $attendee ) {

		$instances = array( );

		$query = "SELECT {$this->eventsTable}.* 
					FROM {$this->eventsTable}
					INNER JOIN {$this->eventsAttendeesTable} on {$this->eventsTable}.id = {$this->eventsAttendeesTable}.event_id";

		if ( $attendee->getId() ) {
			$query .= " WHERE attendee_id = %d";
			$query = $this->prepare( $query, $attendee->getId() );
		} else if ( $attendee->getEmail() ) {
			$query .= " 
						INNER JOIN {$this->attendeesTable} on {$this->attendeesTable}.id = {$this->eventsAttendeesTable}.attendee_id
						INNER JOIN {$this->profileTableName} on {$this->profileTableName}.id = {$this->attendeesTable}.profile_id
						WHERE {$this->profileTableName}.email = %s";

			$query = $this->prepare( $query, $attendee->getEmail() );
		}
		else
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee id or email is required ' );

		$results = $this->getQuery( $query );

		foreach ( $results as $row ) {

			$instance = new \MavenEvents\Core\Domain\Event();
			$this->fillObject( $instance, $row );

			$instances[ ] = $instance;
		}


		return $instances;
	}

	/**
	 * Return an Attendee object
	 * @param int $id
	 * @return \MavenEvents\Core\Domain\Attendee
	 */
	public function get( $id ) {

		$attendee = new \MavenEvents\Core\Domain\Attendee();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException( 'Not Found' );


		$this->fillObject( $attendee, $row );

		$this->loadProfile( $attendee );

		return $attendee;
	}

	public function getByRegistration( $id ) {

		$registry = \MavenEvents\Settings\EventsRegistry::instance();

		$attendee = new \MavenEvents\Core\Domain\Attendee();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$query = "SELECT {$this->attendeesTable}.*,{$this->eventsAttendeesTable}.id as reg_id, number_of_tickets, amount_paid, checked_in, event_id ,order_id, status_id, status_description
					FROM {$this->attendeesTable}
					INNER JOIN {$this->eventsAttendeesTable} on {$this->attendeesTable}.id = {$this->eventsAttendeesTable}.attendee_id
					INNER JOIN {$this->profilesTable} on {$this->profilesTable}.id	= {$this->attendeesTable}.profile_id
					where {$this->eventsAttendeesTable}.id = %d";


		$row = $this->getQueryRow( $this->prepare( $query, $id ) );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException( 'Not Found' );


		$this->fillObject( $attendee, $row );

		$status = new \MavenEvents\Core\Domain\AttendeeStatus();
		$status->setDescription( $row->status_description );
		$status->setId( $row->status_id );
		$status->setImageUrl( $registry->getAttendeeStatusImagesUrl() . $row->status_id . '.png' );

		$attendee->setStatus( $status );

		$this->loadProfile( $attendee );

		return $attendee;
	}

	public function getByEmail( $email ) {

		if ( ! $email )
			throw new \Maven\Exceptions\MissingParameterException( 'Email: is required' );

		$query = "SELECT {$this->tableName}.id FROM {$this->tableName} " . $this->getProfileJoin( $this->tableName, "profile_id" );
		$query.= " WHERE email = %s";
		$query = $this->prepare( $query, $email );

		$row = $this->getVar( $query );

		if ( ! $row )
			return false;


		return $this->get( $row );
	}

	/** Create or update the donation to the database
	 * 
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function save( \MavenEvents\Core\Domain\Attendee $attendee ) {

		$attendee->sanitize();

		$data = array(
		    'profile_id' => $attendee->getProfileId()
		);

		$format = array(
		    '%d' //profile_id
		);

		if ( ! $attendee->getId() ) {

			$this->saveProfile( $attendee );

			if ( ! $attendee->getProfileId() || (( int ) $attendee->getProfileId()) <= 0 )
				throw new \Maven\Exceptions\RequiredException( 'Profile ID is required.' );

			$data[ 'profile_id' ] = $attendee->getProfileId();

			$id = $this->insert( $data, $format );

			$attendee->setId( $id );

			if ( $attendee->hasRegisteredEvents() )
				$this->registerEvents( $attendee->getId(), $attendee->getRegisteredEvents() );
		} else {

			if ( ! $attendee->getProfileId() || (( int ) $attendee->getProfileId()) <= 0 )
				throw new \Maven\Exceptions\RequiredException( 'Updating: Profile ID is required.' );

			$this->saveProfile( $attendee );

			$this->updateById( $attendee->getId(), $data, $format );
		}

		return $attendee;
	}

	/**
	 * 
	 * @param int $attendeeId
	 * @param \MavenEvents\Core\Domain\AttendeeRegisteredEvent[] $registeredEvents
	 */
	public function registerEvents( $attendeeId, $registeredEvents ) {

		foreach ( $registeredEvents as $registeredEvent ) {

			$registeredEvent->sanitize();

			$data = array(
			    'attendee_id' => $attendeeId,
			    'event_id' => $registeredEvent->getEvent()->getID(),
			    'number_of_tickets' => $registeredEvent->getNumberOfTickets(),
			    'amount_paid' => $registeredEvent->getAmountPaid(),
			    'checked_in' => $registeredEvent->isCheckedIn(),
			    'checked_in_datetime' => $registeredEvent->getCheckedInDateTime(),
			    'order_id' => $registeredEvent->getOrderId(),
			    'status_id' => $registeredEvent->getStatus()->getId(),
			    'status_description' => $registeredEvent->getStatus()->getDescription()
			);

			$format = array(
			    '%d', //attendee_id
			    '%d', //event_id
			    '%d', //number_of_tickets
			    '%d', //amount_paid
			    '%d', //checked_in
			    '%s', //checked_in_datetime
			    '%d', //order_id
			    '%s', //status_id
			    '%s', //status_description
			);

			if ( ! $registeredEvent->getId() ) {
				$id = $this->insert( $data, $format, EventsConfig::eventsAttendeesTableName );

				$registeredEvent->setId( $id );
			}
			else
				$this->updateById( $registeredEvent->getId(), $data, $format, EventsConfig::eventsAttendeesTableName );
		}
	}

	public function deleteRelation( $attendeeId, $eventId ) {

		$relationTable = EventsConfig::eventsAttendeesTableName;

		$query = "DELETE FROM {$relationTable} where event_id = %d and attendee_id = %d";

		$query = $this->db->prepare( $query, $eventId, $attendeeId );

		return $this->executeQuery( $query );
	}

	public function addRelation( \MavenEvents\Core\Domain\Attendee $attende, \MavenEvents\Core\Domain\Event $event ) {

		$relationTable = EventsConfig::eventsAttendeesTableName;

		//TODO: CHECK if relation exist
		$query = "SELECT id FROM {$relationTable} where event_id = %d and attendee_id = %d";

		$query = $this->db->prepare( $query, $event->getId(), $attende->getId() );

		$row = $this->executeQuery( $query );

		if ( ! $row ) {

			$query = "INSERT INTO {$relationTable}(event_id , attendee_id, number_of_tickets) VALUES (%d , %d, 1)";

			$query = $this->db->prepare( $query, $event->getId(), $attende->getId() );

			return $this->executeQuery( $query );
		} else {
			return $row;
		}
	}

	public function addAttendees( $attendees, \MavenEvents\Core\Domain\Event $event ) {

		if ( is_null( $attendees ) ) {
			$attendees = array( );
		}

		if ( ! $event->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Event Id is required' );

		/* First: remove missing presenters */
		$existingAttendees = $this->getEventAttendees( $event->getId() );

		foreach ( $existingAttendees as $exAttendee ) {

			//search the presenter in the incoming array
			$existingId = $exAttendee->getId();

			$found = array_filter( $attendees, function($item) use ($existingId) {

					if ( $item->getId() == $existingId )
						return true;

					return false;
				} );
			if ( ! $found ) {
				//The presenter is not in the array, delete the relation
				$this->deleteRelation( $existingId, $event->getId() );
			}
		}

		/* Second: Update/Insert new presenters */
		foreach ( $attendees as $attendee ) {

			//TODO: Do we really need the full profile here? 
			$this->loadProfile( $attendee );

			$this->save( $attendee );

			//add the relation
			$this->addRelation( $attendee->getId(), $event->getId() );
		}
		return true;
	}

	public function updateAttendee( \MavenEvents\Core\Domain\Attendee $attendee ) {

		if ( ! $attendee->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee Id is required' );


		return $this->save( $attendee );
	}

	public function remove( $id ) {
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		//remove from events
		$query = "delete from {$this->eventsAttendeesTable} where attendee_id = %d";

		$query = $this->prepare( $query, $id );

		$this->executeQuery( $query );

		//delete attende
		$this->deleteRow( $id, "%d", $this->attendeesTable );
	}

}