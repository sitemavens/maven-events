<?php

namespace MavenEvents\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class AttendeeFilter {

	private $event;
	private $status;
	private $email;
	private $all = false;

	private function protectField( $field ) {

		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field ) );

		return $field;
	}

	public function __construct() {
		;
	}

	public function getEvent() {
		return $this->event;
	}

	public function setEvent( $event ) {
		$this->event = $event;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function setStatus( $status ) {
		$this->status = $status;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
	}
		
	public function getAll() {
		return $this->all;
	}

	public function setAll( $all ) {
		$this->all = $all;
	}
}

