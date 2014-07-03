<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class VenueManager {

	
	public function __construct() {
		;
	}
	
	public function getAll($orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000){
		
		$venueMapper = new Mappers\VenueMapper();
		
		return $venueMapper->getAll($orderBy, $orderType, $start, $limit);
		
	}
	
	public function getCount(){
		$mapper=new Mappers\VenueMapper();
		
		return $mapper->getCount();
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Venue or array $venue
	 * @return \Maven\Core\Message\Message
	 */
	function addVenue( $venue ) {
		
		$venueToUpdate = new Domain\Venue();
		
		if ( is_array( $venue ) )
			\Maven\Core\FillerHelper::fillObject($venueToUpdate, $venue);
		else
			$venueToUpdate = $venue;
		

		$venueMapper = new Mappers\VenueMapper();
		
		return $venueMapper->save( $venueToUpdate );
		
		
	}
	
	public function get( $id ){
		
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'ID is required' );
		
		$venueMapper = new Mappers\VenueMapper();
		
		$venue = $venueMapper->get( $id );
		
		if ( ! $venue )
			throw new \Maven\Exceptions\NotFoundException( 'Venue not found:'.$id );
		
		return $venue;
		
	}
	
	public function delete( $id ){
		
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'ID is required' );
		
		$venueMapper = new Mappers\VenueMapper();
		
		$eventMapper = new Mappers\EventMapper();
		
		// We need to remove the venue from all the events
		$venueMapper->removeVenue( $id );
		
		// We remove the term
		wp_delete_term($id, EventsConfig::venueTypeName );
		
		return $venueMapper->delete($id);
	}

}


