<?php

namespace MavenEvents\Core\Domain;

class AttendeeRegisteredEvent extends \Maven\Core\DomainObject {

	private $amountPaid;
	private $numberOfTickets;
	private $checkedIn;
	private $checkedInDateTime;
	private $attendeeId;
	private $eventId;
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\Attendee 
	 */
	private $attendee;
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\AttendeeStatus 
	 */
	private $status;
	private $orderId;
	private $primaryAttendeeId;
	
	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$rules = array(
			
			'amountPaid'		=> \Maven\Core\SanitizationRule::Float,
			'numberOfTickets'	=> \Maven\Core\SanitizationRule::Integer,
			'checkedIn'		=> \Maven\Core\SanitizationRule::Boolean,
			'checkedInDateTime'	=> \Maven\Core\SanitizationRule::DateTime, 
			'orderId'		=> \Maven\Core\SanitizationRule::Integer,
			'primaryAttendeeId'	=> \Maven\Core\SanitizationRule::Integer,
			'attendeeId'		=> \Maven\Core\SanitizationRule::Integer
			
		);
		
		$this->status = new \MavenEvents\Core\Domain\AttendeeStatus();
		$this->attendee = new \MavenEvents\Core\Domain\Attendee();
		$this->event = new \MavenEvents\Core\Domain\Event();
		
		$this->setSanitizationRules( $rules );
	}
	
	
	/**
	 *
	 * @var \MavenEvents\Core\Domain\Event 
	 */
	private $event;

	public function getEvent() {
		return $this->event;
	}

	public function setEvent( \MavenEvents\Core\Domain\Event $event ) {
		$this->event = $event;
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
	public function setStatus(  \MavenEvents\Core\Domain\AttendeeStatus $status ) {
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
	
	
	public function sanitize() {
		parent::sanitize();
		
		if ( $this->getStatus() )
			$this->getStatus()->sanitize();
		
		if ( $this->getAttendee())
			$this->getAttendee()->sanitize();
	}
	
	public function getAttendeeId() {
		return $this->attendeeId;
	}

	public function setAttendeeId( $attendeeId ) {
		$this->attendeeId = $attendeeId;
	}

	/**
	 * 
	 * @return \MavenEvents\Core\Domain\Attendee
	 */
	public function getAttendee() {
		return $this->attendee;
	}

	public function setAttendee( \MavenEvents\Core\Domain\Attendee $attendee ) {
		$this->attendee = $attendee;
	}


	public function getEventId() {
		return $this->eventId;
	}

	public function setEventId( $eventId ) {
		$this->eventId = $eventId;
	}


}
