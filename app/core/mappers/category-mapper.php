<?php

namespace MavenEvents\Core\Mappers;

use \MavenEvents\Core\EventsConfig;

class CategoryMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::categoriesTableName );
	}

	public function getAll( $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$categories = array( );
		
		if ( ! $orderBy )
			$orderBy = 'id';
		
		$results = $this->getResults( $orderBy, $orderType, $start, $limit );

		foreach ( $results as $row ) {
			$category = new \MavenEvents\Core\Domain\Category();
			$this->fillObject( $category, $row );

			// If we fell that performance is an issue, this line can be improved, reading all the term data together with the venues.
			$term = get_term( $category->getId(), EventsConfig::categoryTypeName );

			if ( $term )
				$category->setSlug( $term->slug );

			$categories[ ] = $category;
		}

		return $categories;
	}
	
	public function getCount() {

		$query = "select	count(*)
					from {$this->tableName}";
		return $this->getVar( $query );
	}

	/**
	 * Return a Category object
	 * @param int $id
	 * @return \MavenEvents\Core\Domain\Category
	 */
	public function get( $id ) {

		if ( ! $id || ! is_numeric( $id ) )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$category = new \MavenEvents\Core\Domain\Category();

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();

		$this->fillObject( $category, $row );

		$term = get_term( $category->getId(), EventsConfig::categoryTypeName );

		if ( $term )
			$category->setSlug( $term->slug );

		return $category;
	}

	public function delete( $categoryId ) {

		$result = wp_delete_term( $categoryId, EventsConfig::categoryTypeName );

		if ( is_wp_error( $result ) )
			throw new \Maven\Exceptions\MapperException( $result->get_error_message() );

		//remove the presenter from the events
		$relationTable = EventsConfig::eventsCategoriesTableName;

		$query = "DELETE FROM {$relationTable} where category_id = %d";

		$query = $this->db->prepare( $query, $categoryId );

		$this->executeQuery( $query );

		parent::delete( $categoryId );

		return true;
	}

	public function deleteRelation( $categoryId, $eventId ) {

		$relationTable = EventsConfig::eventsCategoriesTableName;

		$query = "DELETE FROM {$relationTable} where event_id = %d and category_id = %d";

		$query = $this->db->prepare( $query, $eventId, $categoryId );

		return $this->executeQuery( $query );
		//parent::delete( $presenterId );
		//return true;
	}
	
	public function addRelation( $categoryId, $eventId ) {
		$relationTable = EventsConfig::eventsCategoriesTableName;

		//TODO: CHECK if relation exist
		$query = "SELECT id FROM {$relationTable} where event_id = %d and category_id = %d";

		$query = $this->db->prepare( $query, $eventId, $categoryId );

		$row = $this->executeQuery( $query );

		//var_dump($row);
		if ( ! $row ) {

			$query = "INSERT INTO {$relationTable}(event_id , category_id) VALUES (%d , %d)";

			$query = $this->db->prepare( $query, $eventId, $categoryId );

			return $this->executeQuery( $query );
		} else {
			return $row;
		}
	}
	
	/** Create or update the category to the database
	 * 
	 * @param \MavenEvents\Core\Domain\Category $category
	 * @return \MavenEvents\Core\Domain\Category
	 */
	public function save( \MavenEvents\Core\Domain\Category $category ) {

		$category->sanitize();
		
		$data = array(
		    'name' => $category->getName(),
		    'description' => $category->getDescription(),
		    'term_id' => $category->getTermId(),
		    'term_taxonomy_id' => $category->getTermTaxonomyId()
		);

		$format = array(
		    '%s', //name
		    '%s', //description
		    '%d', //term_id
		    '%d' //term_taxonomy_id		    
		);

		if ( ! $category->getId() ) {
			try {

				$result = wp_insert_term( $category->getName(), EventsConfig::categoryTypeName );

				if ( is_wp_error( $result ) )
					throw new \Maven\Exceptions\MapperException( $result->get_error_message() );

				$data[ 'term_id' ] = $result[ 'term_id' ];
				$data[ 'term_taxonomy_id' ] = $result[ 'term_taxonomy_id' ];
				$data[ 'id' ] = $result[ 'term_id' ];

				$categoryId = $this->insert( $data, $format );
			} catch ( \Exception $ex ) {

				return \Maven\Core\Message\MessageManager::createErrorMessage( $ex->getMessage() );
			}

			$category->setId( $categoryId );
		} else {
			$this->updateById( $category->getId(), $data, $format );
		}

		return $category;
	}

	public function getEventCategories( $eventId, $orderBy = "display_name" ) {

		$instances = array( );
		$relTable = EventsConfig::eventsCategoriesTableName;
		$query = "select categories.* from {$this->tableName} categories 
			inner join {$relTable} rel on categories.id=rel.category_id
			where rel.event_id=%d";
		$query = $this->db->prepare( $query, $eventId );

		//$results = $this->getResultsBy( 'event_id', $eventId, $orderBy );
		$results = $this->db->get_results( $query );

		foreach ( $results as $row ) {
			$instance = new \MavenEvents\Core\Domain\Category();
			$this->fillObject( $instance, $row );

			$instances[ ] = $instance;
		}

		return $instances;
	}
	
	public function addCategories( $categories, \MavenEvents\Core\Domain\Event $event ) {

		if ( is_null( $categories ) ) {
			$categories = array( );
		}

		if ( ! $event->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Event Id is required' );

		/* First: remove missing categories */
		$existingCategories = $this->getEventCategories( $event->getId() );

		foreach ( $existingCategories as $exCategory ) {

			//search the category in the incoming array
			$existingId = $exCategory->getId();

			$found = array_filter( $categories, function($item) use ($existingId) {
					if ( $item->getId() == $existingId)
						return true;
					
					return false;
					//return array_key_exists( 'id', $item ) && $item[ 'id' ] == $existingId;
				} );
				
			if ( ! $found ) {
				//The category is not in the array, delete the relation
				$this->deleteRelation( $existingId, $event->getId() );
			}
		}

		/* Second: Update/Insert new categories */
		foreach ( $categories as $category ) {

//			$category = new \MavenEvents\Core\Domain\Category();
//
//			$this->fillObject( $category, $categoryRow );

			$this->save( $category );

			//add the relation
			$this->addRelation( $category->getId(), $event->getId() );
		}

		return true;
	}
	
	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}