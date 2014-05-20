<?php

namespace MavenEvents\Core\Domain;

class EventFilter {

	private $type = \MavenEvents\Core\Domain\EventFilterType::comingNext;
	private $useWpMethods = false;
	private $text;
	private $venueId;
	private $venueCity;
	private $venueState;
	private $venueCountry;
	
	//Event properties
	private $description;
	private $name;
	private $registrationStartDate;
	private $registrationEndDate;
	private $registrationStartTime;
	private $registrationEndTime;
	private $eventStartDate;
	private $eventEndDate;
	private $eventStartTime;
	private $eventEndTime;
	
	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getRegistrationStartDate() {
		return $this->registrationStartDate;
	}

	public function setRegistrationStartDate( $registrationStartDate ) {
		$this->registrationStartDate = $registrationStartDate;
	}

	public function getRegistrationEndDate() {
		return $this->registrationEndDate;
	}

	public function setRegistrationEndDate( $registrationEndDate ) {
		$this->registrationEndDate = $registrationEndDate;
	}

	public function getRegistrationStartTime() {
		return $this->registrationStartTime;
	}

	public function setRegistrationStartTime( $registrationStartTime ) {
		$this->registrationStartTime = $registrationStartTime;
	}

	public function getRegistrationEndTime() {
		return $this->registrationEndTime;
	}

	public function setRegistrationEndTime( $registrationEndTime ) {
		$this->registrationEndTime = $registrationEndTime;
	}

	public function getEventStartDate() {
		return $this->eventStartDate;
	}

	public function setEventStartDate( $eventStartDate ) {
		
		$this->eventStartDate = $this->protectField( $eventStartDate );
	}
	
	private function protectField( $field ){
		
		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field )) ;
		
		return $field;
	}

	public function getEventEndDate() {
		return $this->eventEndDate;
	}

	public function setEventEndDate( $eventEndDate ) {
		$this->eventEndDate = $this->protectField( $eventEndDate );
	}

	public function getEventStartTime() {
		return $this->eventStartTime;
	}

	public function setEventStartTime( $eventStartTime ) {
		$this->eventStartTime = $eventStartTime;
	}

	public function getEventEndTime() {
		return $this->eventEndTime;
	}

	public function setEventEndTime( $eventEndTime ) {
		$this->eventEndTime = $eventEndTime;
	}

	
	public function __construct() {
		;
	}

	public function useWpMethods() {
		return $this->useWpMethods;
	}

	public function setUseWpMethods( $useWpMethods ) {
		$this->useWpMethods = $useWpMethods;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function getText() {
		return $this->text;
	}

	public function setText( $text ) {
		$this->text = $text;
	}

	public function getVenueId() {
		return $this->venueId;
	}

	public function setVenueId( $venueId ) {
		$this->venueId = (int)$venueId;
	}
	
	public function getVenueCity() {
		return $this->venueCity;
	}

	public function setVenueCity( $venueCity ) {
		$this->venueCity = $venueCity;
	}

	public function getVenueState() {
		return $this->venueState;
	}

	public function setVenueState( $venueState ) {
		$this->venueState = $venueState;
	}

	public function getVenueCountry() {
		return $this->venueCountry;
	}

	public function setVenueCountry( $venueCountry ) {
		$this->venueCountry = $venueCountry;
	}

}

