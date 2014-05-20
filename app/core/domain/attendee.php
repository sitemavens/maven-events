<?php

namespace MavenEvents\Core\Domain;

class Attendee extends \Maven\Core\Domain\Profile {

	/** These properties won't be saved into the db **/
	private $amountPaid;
	private $numberOfTickets;
	private $checkedIn;
	private $checkedInDateTime;
	private $regId;
	private $eventId;
	
	/**
	 *
	 * @var type \MavenEvents\Core\Domain\Event
	 */
	private $event;
	
	/**
	 *
	 * @var type \MavenEvents\Core\Domain\Order
	 */
	private $order;
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\AttendeeStatus 
	 */
	private $status;
	private $orderId;
	private $primaryAttendeeId;
	
	/**
	 * This property let you set if an attendee is new or not. To be able to make a difference from the existings attendees. 
	 * It is usful speciall in the checkout process. Since the "Event" is loaded with all the attendees, and there is no way 
	 * to know if is a new attendee or not.
	 * 
	 * @var boolean 
	 */
	private $new = false;
		
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeRegisteredEvent[]
	 */
	private $registeredEvents;
	
	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$this->registeredEvents = array();
		$this->status = new \MavenEvents\Core\Domain\AttendeeStatus();
		
		
		$rules = array(
			'amountPaid'		=> \Maven\Core\SanitizationRule::Float,
			'numberOfTickets'	=> \Maven\Core\SanitizationRule::Integer,
			'checkedIn'		=> \Maven\Core\SanitizationRule::Boolean,
			'checkedInDateTime'	=> \Maven\Core\SanitizationRule::DateTime,
			'orderId'		=> \Maven\Core\SanitizationRule::Integer,
			'primaryAttendeeId'	=> \Maven\Core\SanitizationRule::Integer
		);
		
		$this->setSanitizationRules( $rules );
		
	}
	
	public function getAmountPaid() {
		return $this->amountPaid;
	}

	public function setAmountPaid( $amountPaid ) {
		$this->amountPaid = $amountPaid;
	}

	public function getNumberOfTickets() {
		return $this->numberOfTickets;
	}

	public function setNumberOfTickets( $numberOfTickets ) {
		$this->numberOfTickets = $numberOfTickets;
	}

	public function isCheckedIn() {
		return $this->checkedIn;
	}

	public function setCheckedIn( $checkedIn ) {
		$this->checkedIn = $checkedIn;
	}

	public function getCheckedInDateTime() {
		return $this->checkedInDateTime;
	}

	public function setCheckedInDateTime( $checkedInDateTime ) {
		$this->checkedInDateTime = $checkedInDateTime;
	}

	public function getRegId() {
		return $this->regId;
	}

	public function setRegId( $regId ) {
		$this->regId = $regId;
	}

	public function getEventId() {
		return $this->eventId;
	}

	public function setEventId( $eventId ) {
		$this->eventId = $eventId;
	}

	public function getEvent() {
		return $this->event;
	}

	public function setEvent( $event ) {
		$this->event = $event;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder( $order ) {
		$this->order = $order;
	}

		
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeRegisteredEvent[]
	 */
	public function getRegisteredEvents() {
		return $this->registeredEvents;
	}

	public function setRegisteredEvents(  $registeredEvents ) {
		
		$this->registeredEvents = $registeredEvents;
		
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\AttendeeRegisteredEvent $registeredEvent
	 */
	public function addRegisteredEvent( \MavenEvents\Core\Domain\AttendeeRegisteredEvent $registeredEvent ){
		
		$this->registeredEvents[] = $registeredEvent;
		
	}
	
	/**
	 * @return \MavenEvents\Core\Domain\AttendeeRegisteredEvent
	 */
	public function newRegisteredEvent( ){
		
		$registeredEvent = new \MavenEvents\Core\Domain\AttendeeRegisteredEvent();
		
		
		$this->registeredEvents[] = $registeredEvent;
		
		return $registeredEvent;
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function hasRegisteredEvents(){
		
		return $this->registeredEvents && count( $this->registeredEvents )> 0;
	}

	
	public function sanitize(){
		
		parent::sanitize();
		
		if ( $this->hasRegisteredEvents() )
			foreach ( $this->registeredEvents as $registerEvent )
				$registerEvent->sanitize();
		
		$this->status->sanitize();
		
	}
	
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\AttendeeStatus $status
	 */
	public function setStatus( \MavenEvents\Core\Domain\AttendeeStatus $status ) {
		$this->status = $status;
	}

	public function getOrderId() {
		return $this->orderId;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	public function getPrimaryAttendeeId() {
		return $this->primaryAttendeeId;
	}

	public function setPrimaryAttendeeId( $primaryAttendeeId ) {
		$this->primaryAttendeeId = $primaryAttendeeId;
	}

	/**
	 * This property let you set if an attendee is new or not. To be able to make a difference from the existings attendees. 
	 * It is usful speciall in the checkout process. Since the "Event" is loaded with all the attendees, and there is no way 
	 * to know if is a new attendee or not.
	 * 
	 * @return boolean
	 */
	public function isNew() {
		return $this->new;
	}

	/**
	 * 
	 * @param boolean $new
	 */
	public function setNew( $new ) {
		$this->new = $new;
	}
	
}
