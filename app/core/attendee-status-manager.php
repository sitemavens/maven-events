<?php


namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class AttendeeStatusManager {
	
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	public static function getReceivedStatus() {
		return self::getStatus( 'received' );
	}

	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	public static function getErrorStatus() {
		return self::getStatus( 'error' );
	}
	
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	public static function getApprovedStatus() {
		return self::getStatus( 'approved' );
	}
	
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	public static function getUnknownStatus() {
		return self::getStatus( 'unknown' );
	}
 
	/**
	 * 
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 */
	private static function getStatus( $id ) {
		
		$manager = new self;
		
		return $manager->get( $id );
	}
	
	
	
	
	private $statusMapper;
	public function __construct( ) {
		
		$this->statusMapper = new Mappers\AttendeeStatusMapper();
	}
	
	
	/**
	 * It will complete the status with the name and image url
	 * @param \MavenEvents\Core\Domain\AttendeeStatus $status
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function completeInfo( \MavenEvents\Core\Domain\AttendeeStatus $status ) {
		
		if ( ! $status )
			throw new \Maven\Exceptions\MissingParameterException( "Status is required" );
		
		$statusAux = $this->get( $status->getId() );
		
		$status->setName( $statusAux->getName() );
		$status->setImageUrl( $statusAux->getImageUrl() );
		
		return $status;
		
	}
	
	/**
	 * 
	 * @param string $id
	 * @return \MavenEvents\Core\Domain\AttendeeStatus
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $id ){
		
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( "Status id is required" );

		$statusMapper = new \MavenEvents\Core\Mappers\AttendeeStatusMapper();

		return $statusMapper->get( $id );
		
	}
	
	public function getAll(){
		
		$statusMapper = new \MavenEvents\Core\Mappers\AttendeeStatusMapper();

		return $statusMapper->getAll( );
		
	}
	 
 
	 
}

