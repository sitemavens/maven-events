<?php

namespace MavenEvents\Core\Domain;

class EventPrices extends \Maven\Core\DomainObject {

	private $eventId;
	private $price;
	private $name;
	private $exclusiveForMembers = FALSE;

	/**
	 *
	 * @var \MavenEvents\Core\Domain\Event 
	 */
	private $event = null;

	public function getEvent() {
		return $this->event;
	}

	public function setEvent( \MavenEvents\Core\Domain\Event $event ) {
		$this->event = $event;
	}

	public function __construct( $id = false ) {

		parent::__construct( $id );

		// We need to initialice the instances
		$this->event = new \MavenEvents\Core\Domain\Event();

		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'eventId' => \Maven\Core\SanitizationRule::Integer,
		    'price' => \Maven\Core\SanitizationRule::Price,
		);

		$this->setSanitizationRules( $rules );
	}

	public function getEventId() {
		return $this->eventId;
	}

	public function setEventId( $eventId ) {
		$this->eventId = $eventId;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function isExclusiveForMembers() {
		return $this->exclusiveForMembers;
	}

	public function setExclusiveForMembers( $exclusiveForMembers ) {
		if ( $exclusiveForMembers === 'false' || $exclusiveForMembers === false ) {
			$this->exclusiveForMembers = FALSE;
		} else {
			$this->exclusiveForMembers = $exclusiveForMembers;
		}
	}

}
