<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class CategoryManager {

	public function __construct() {
		;
	}

	public function getAll($orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000) {

		$mapper = new Mappers\CategoryMapper();

		return $mapper->getAll($orderBy, $orderType, $start, $limit);
	}
	
	public function getCount(){
		$mapper=new Mappers\CategoryMapper();
		
		return $mapper->getCount();
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Category or array $category
	 * @return \Maven\Core\Message\Message
	 */
	function addCategory( $category ) {

		$categoryToUpdate = new Domain\Category();

		if ( is_array( $category ) )
			\Maven\Core\FillerHelper::fillObject( $categoryToUpdate, $category );
		else
			$categoryToUpdate = $category;

		$mapper = new Mappers\CategoryMapper();

		return $mapper->save( $categoryToUpdate );
	}

	public function get( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'ID is required' );

		$mapper = new Mappers\CategoryMapper();

		$category = $mapper->get( $id );

		if ( ! $category )
			throw new \Maven\Exceptions\NotFoundException( 'Category not found:' . $id );

		return $category;
	}

	public function delete( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'ID is required' );

		$categoryMapper = new Mappers\CategoryMapper();

		/*$eventMapper = new Mappers\EventMapper();

		// We need to remove the venue from all the events
		$eventMapper->removeVenue( $id );*/

		// We remove the term
		wp_delete_term( $id, EventsConfig::categoryTypeName );

		return $categoryMapper->delete( $id );
	}

}

