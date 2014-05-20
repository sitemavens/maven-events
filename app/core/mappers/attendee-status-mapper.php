<?php

namespace MavenEvents\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AttendeeStatusMapper extends \Maven\Core\Db\Mapper {

	private $status = null;
	
	private $attendeeStatusTable = "";

	public function __construct() {

		parent::__construct( $this->attendeeStatusTable );
		
		$items = array( 'approved'=>'Approved', 'error' => 'Error', 'received'=>'Received', 'unknown'=>'Unknown' );

		$registry = \MavenEvents\Settings\EventsRegistry::instance();
		
		foreach ( $items as $key => $value ) {

			$instance = new \MavenEvents\Core\Domain\AttendeeStatus();
			$instance->setid( $key );
			$instance->setName( $value );
			$instance->setImageUrl( $registry->getAttendeeStatusImagesUrl().$key.".png");
			
			$this->status[ $key ] = $instance;
		}
	}

	public function getAll() {

		return $this->status;
	}

	
	/**
	 * 
	 * @param string $id
	 * @return \MavenEvents\Core\Domain\AttendeeStatus | Boolean
	 */
	public function get( $id ) {

		if ( isset( $this->status[ $id ] ) )
			return clone $this->status[ $id ];

		return false;
	}
	  

}