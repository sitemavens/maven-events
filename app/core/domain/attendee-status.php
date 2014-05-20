<?php

namespace MavenEvents\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class AttendeeStatus extends \Maven\Core\DomainObject{
	
	
	private $name;
	
	private $imageUrl;
	
	private $timestamp;
	
	private $description;
	
	public function __construct( $id = false ) {
		parent::__construct( $id );
		
		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'description' => \Maven\Core\SanitizationRule::Text,
			'timestamp' =>  \Maven\Core\SanitizationRule::DateTime
		);

		$this->setSanitizationRules( $rules );
		
	}
	
	public function getImageUrl() {
		return $this->imageUrl;
	}

	public function setImageUrl( $imageUrl ) {
		$this->imageUrl = $imageUrl;
	}

		
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
	
	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	

}