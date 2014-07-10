<?php

namespace MavenEvents\Core\Mappers;

use \MavenEvents\Core\EventsConfig;

class PresenterMapper extends \Maven\Core\Mappers\ProfileMapper {

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::presentersTableName );
	}

	public function getAll( $orderBy = "display_name", $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$presenters = array( );
		if ( !$orderBy ) {
			$orderBy = 'id';
		}

		$results = $this->getResults( $orderBy, $orderType, $start, $limit );

		foreach ( $results as $row ) {

			$presenter = new \MavenEvents\Core\Domain\Presenter();

			$this->fillObject( $presenter, $row );

			// If we fell that performance is an issue, this line can be improved, reading all the term data together with the venues.
			$term = get_term( $presenter->getId(), EventsConfig::presenterTypeName );

			if ( $term )
				$presenter->setSlug( $term->slug );

			$this->loadProfile( $presenter );

			$presenters[ ] = $presenter;
		}

		return $presenters;
	}

//	public function getCount() {
//
//		$query = "select	count(*)
//					from {$this->tableName}";
//		return $this->getVar( $query );
//	}

	/**
	 * Return a Venue object
	 * @param int $id
	 * @return \MavenEvents\Core\Domain\Venue
	 */
	public function get( $id ) {

		$presenter = new \MavenEvents\Core\Domain\Presenter();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();

		$this->fillObject( $presenter, $row );

		$term = get_term( $presenter->getId(), EventsConfig::presenterTypeName );

		if ( $term )
			$presenter->setSlug( $term->slug );

		$this->loadProfile( $presenter );

		return $presenter;
	}

	public function delete( $presenterId ) {

		$result = wp_delete_term( $presenterId, EventsConfig::presenterTypeName );

		if ( is_wp_error( $result ) )
			throw new \Maven\Exceptions\MapperException( $result->get_error_message() );

		//remove the presenter from the events
		$relationTable = EventsConfig::eventsPresentersTableName;

		$query = "DELETE FROM {$relationTable} where presenter_id = %d";

		$query = $this->db->prepare( $query, $presenterId );

		$this->executeQuery( $query );

		parent::deleteRow( $presenterId );

		return true;
	}

	public function deleteRelation( $presenterId, $eventId ) {

		$relationTable = EventsConfig::eventsPresentersTableName;

		$query = "DELETE FROM {$relationTable} where event_id = %d and presenter_id = %d";

		$query = $this->db->prepare( $query, $eventId, $presenterId );

		return $this->executeQuery( $query );
		//parent::delete( $presenterId );
		//return true;
	}

	public function addRelation( $presenterId, $eventId ) {
		$relationTable = EventsConfig::eventsPresentersTableName;

		//TODO: CHECK if relation exist
		$query = "SELECT id FROM {$relationTable} where event_id = %d and presenter_id = %d";

		$query = $this->db->prepare( $query, $eventId, $presenterId );

		$row = $this->executeQuery( $query );

		//var_dump($row);
		if ( ! $row ) {

			$query = "INSERT INTO {$relationTable}(event_id , presenter_id) VALUES (%d , %d)";

			$query = $this->db->prepare( $query, $eventId, $presenterId );

			return $this->executeQuery( $query );
		} else {
			return $row;
		}
	}

	/** Create or update the venue to the database
	 * 
	 * @param \MavenEvents\Core\Domain\Venue $venue
	 * @return \MavenEvents\Core\Domain\Venue
	 */
	public function savePresenter( \MavenEvents\Core\Domain\Presenter $presenter ) {

		$presenter->sanitize();


		$presenterData = array(
		    'display_name' => $presenter->getDisplayName(),
		    'profile_id' => $presenter->getProfileId()
		);

		$format = array(
		    '%s', //display_name
		    '%d' //profile_id
		);

		if ( ! $presenter->getId() ) {


			$this->saveProfile( $presenter );

			if ( ! $presenter->getProfileId() || (( int ) $presenter->getProfileId()) <= 0 )
				throw new \Maven\Exceptions\RequiredException( 'Profile ID is required.' );

			$result = wp_insert_term( $presenter->getDisplayName(), EventsConfig::presenterTypeName );

			if ( is_wp_error( $result ) )
				throw new \Maven\Exceptions\MapperException( $result->get_error_message() );

			$presenterData[ 'term_taxonomy_id' ] = $result[ 'term_taxonomy_id' ];
			$presenterData[ 'id' ] = $result[ 'term_id' ];
			$presenterData[ 'profile_id' ] = $presenter->getProfileId();

			$presenterId = $this->insert( $presenterData, $format );


			$presenter->setId( $presenterId );
		} else {

			$this->saveProfile( $presenter );

			$this->updateById( $presenter->getId(), $presenterData, $format );
		}

		return $presenter;
	}

	public function getEventPresenters( $eventId, $orderBy = "display_name" ) {

		$instances = array( );
		$relTable = EventsConfig::eventsPresentersTableName;
		$query = "select presenters.* from {$this->tableName} presenters 
			inner join {$relTable} rel on presenters.id=rel.presenter_id
			where rel.event_id=%d";
		$query = $this->db->prepare( $query, $eventId );

		//$results = $this->getResultsBy( 'event_id', $eventId, $orderBy );
		$results = $this->db->get_results( $query );

		foreach ( $results as $row ) {
			$instance = new \MavenEvents\Core\Domain\Presenter();
			$this->fillObject( $instance, $row );

			$this->loadProfile( $instance );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function addPresenters( $presenters, \MavenEvents\Core\Domain\Event $event ) {

		if ( is_null( $presenters ) ) {
			$presenters = array( );
		}

		if ( ! $event->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Event Id is required' );

		/* First: remove missing presenters */
		$existingPresenters = $this->getEventPresenters( $event->getId() );

		foreach ( $existingPresenters as $exPresenter ) {

			//search the presenter in the incoming array
			$existingId = $exPresenter->getId();

			$found = array_filter( $presenters, function($item) use ($existingId) {

					if ( $item->getId() == $existingId )
						return true;

					return false;
				} );
			if ( ! $found ) {
				//The presenter is not in the array, delete the relation
				$this->deleteRelation( $existingId, $event->getId() );
			}
		}

		/* Second: Update/Insert new presenters */
		foreach ( $presenters as $presenter ) {

			//TODO: Do we really need the full profile here? 
			$this->loadProfile( $presenter );

			$this->save( $presenter );

			//add the relation
			$this->addRelation( $presenter->getId(), $event->getId() );
		}

		return true;
	}

}