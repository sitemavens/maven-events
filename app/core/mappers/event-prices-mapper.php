<?php

namespace MavenEvents\Core\Mappers;

class EventPricesMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::eventPricesTableName );
	}

	public function getAll( $orderBy = "name" ) {
		$instances = array( );
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {
			$instance = new \MavenEvents\Core\Domain\EventPrices();
			$this->fillObject( $instance, $row );
			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getEventPrices( $eventId, $orderBy = "name" ) {

		$instances = array( );
		$results = $this->getResultsBy( 'event_id', $eventId, $orderBy );

		foreach ( $results as $row ) {
			$instance = new \MavenEvents\Core\Domain\EventPrices();
			$this->fillObject( $instance, $row );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	/**
	 * Return an EventPrice object
	 * @param int $id
	 * @return \MavenEvents\Core\Domain\EventPrices
	 */
	public function get( $id ) {

		$eventPrice = new \MavenEvents\Core\Domain\EventPrices();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();


		$this->fillObject( $eventPrice, $row );

		return $eventPrice;
	}

	/** Create or update the donation to the database
	 * 
	 * @param \MavenEvents\Core\Domain\EventPrices $eventPrice
	 * @return \MavenEvents\Core\Domain\EventPrices
	 */
	public function save( \MavenEvents\Core\Domain\EventPrices $eventPrice ) {
		
		$eventPrice->sanitize();
		
		if ( ! $eventPrice->getEventId() )
			throw new \Maven\Exceptions\RequiredException('Event ID is required');
		
		$data = array(
		    'name' => $eventPrice->getName(),
		    'price' => $eventPrice->getPrice(),
		    'event_id' => $eventPrice->getEventId(),
		    'exclusive_for_members' => $eventPrice->isExclusiveForMembers() ? 1 : 0
		);

		$format = array(
		    '%s', //name
		    '%d', //price
		    '%d', //venue_id
		    '%d'  //exlusive for members
		);

		if ( ! $eventPrice->getId() ) {
			try {
				$id = $this->insert( $data, $format );
			} catch ( \Exception $ex ) {
				return \Maven\Core\Message\MessageManager::createErrorMessage( $ex->getMessage() );
			}

			$eventPrice->setId( $id );
		} else {

			$this->updateById( $eventPrice->getId(), $data, $format );
		}

		return $eventPrice;
	}

	public function addEventPrices( $eventPrices, \MavenEvents\Core\Domain\Event $event ) {

		if ( is_null( $eventPrices ) ) {
			$eventPrices = array( );
		}

		if ( ! $event->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Event Id is required' );

		/* First: remove missing prices */
		$existingPrices = $this->getEventPrices( $event->getId() );
		
		foreach ( $existingPrices as $exPrice ) {
			//search the price in the incoming array
			$existingId = $exPrice->getId();
			$found = array_filter( $eventPrices, function($item) use ($existingId) {
				
					if ( $item->getId() == $existingId)
						return true;
					
					return false;
					
				} );
				
			if ( ! $found ) {
				//The eventPrice is not in the array, delete it
				$this->delete( $exPrice->getId() );
			}
		}

		/* Second: Update/Insert new prices */
		foreach ( $eventPrices as $price ) {

//			$price = new \MavenEvents\Core\Domain\EventPrices();
//			$this->fillObject( $price, $eventPrice );

			$price->setEventId( $event->getId() );

			$event->addPrice( $price );

			$this->save( $price );
		}

		return true;
	}

	private function filter( $item, $value ) {
		return $item[ 'id' ] == $value;
	}

}