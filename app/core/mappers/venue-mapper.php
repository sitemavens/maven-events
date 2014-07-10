<?php

namespace MavenEvents\Core\Mappers;

use \MavenEvents\Core\EventsConfig;

class VenueMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::venuesTableName );
	}

	public function getAll( $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$venues = array();
		$isPublished = false;
		if ( ! $orderBy )
			$orderBy = 'id';

		$results = $this->getResults( $orderBy, $orderType, $start, $limit );

		foreach ( $results as $row ) {
			$venue = new \MavenEvents\Core\Domain\Venue();
			$this->fillObject( $venue, $row );

			// If we fell that performance is an issue, this line can be improved, reading all the term data together with the venues.
//			$term = get_term( $venue->getId(), EventsConfig::venueTypeName );
//
//			if ( $term )
//				$venue->setSlug( $term->slug );
			//TODO: This way of check for published post is highly inneficient. Must find another way.
			if ( get_post_status( $row->id ) == 'publish' ) {
				$venues[] = $venue;
			}
		}

		return $venues;
	}

	public function getCount() {

		$query = "select	count(*)
					from {$this->tableName}";
		return $this->getVar( $query );
	}

	/**
	 * Return a Venue object
	 * @param int $id
	 * @return \MavenEvents\Core\Domain\Venue
	 */
	public function get( $id, $readWpPost = true ) {

		if ( ! $id || ! is_numeric( $id ) )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$venue = new \MavenEvents\Core\Domain\Venue();

		$row = $this->getRowById( $id );

		if ( ! $row )
			return $venue;

		$this->fillObject( $venue, $row );

//		$term = get_term( $venue->getId(), EventsConfig::venueTypeName );
//
//		if ( $term )
//			$venue->setSlug( $term->slug );

		if ( $readWpPost ) {
			$postEvent = get_post( $id );
			if ( $postEvent ) {
				$venue->setSlug( $postEvent->post_name );
			}
		}

		return $venue;
	}

	public function delete( $venueId ) {

		$result = wp_delete_term( $venueId, EventsConfig::venueTypeName );

		if ( is_wp_error( $result ) ) {
			throw new \Maven\Exceptions\MapperException( $result->get_error_message() );
		}

		parent::deleteRow( $venueId );

		return true;
	}

	/** Create or update the venue to the database
	 * 
	 * @param \MavenEvents\Core\Domain\Venue $venue
	 * @return \MavenEvents\Core\Domain\Venue
	 */
	public function save( \MavenEvents\Core\Domain\Venue $venue ) {

		$venue->sanitize();

		$venueData = array(
			'id' => $venue->getId(),
			'name' => $venue->getName(),
			'address' => $venue->getAddress(),
			'address2' => $venue->getAddress2(),
			'city' => $venue->getCity(),
			'state' => $venue->getState(),
			'zip' => $venue->getZip(),
			'country' => $venue->getCountry(),
			'description' => $venue->getDescription(),
			'contact' => $venue->getContact(),
			'phone' => $venue->getPhone(),
			'twitter' => $venue->getTwitter(),
			'website' => $venue->getWebsite(),
			'featured_image' => $venue->getFeaturedImage(),
			'gallery_images' => $venue->getGalleryImagesForDB(),
			'term_id' => $venue->getTermId(),
			'term_taxonomy_id' => $venue->getTermTaxonomyId(),
			'seating_chart' => $venue->getSeatingChart(),
		);
		$format = array(
			'%d', //id
			'%s', //name
			'%s', //address
			'%s', //address2
			'%s', //city
			'%s', //state
			'%s', //zip
			'%s', //country
			'%s', //description
			'%s', //contact
			'%s', //phone
			'%s', //twitter
			'%s', //website
			'%s', //featured_image
			'%s', //gallery_images
			'%d', //term_id
			'%d', //term_taxonomy_id
			'%s'
		);
		$columns = '';
		$values = '';
		$updateValues = '';
		$i = 0;

		foreach ( $venueData as $key => $value ) {
			$columns = $columns ? $columns . ", " . $key : $key;
			$values = $values ? $values . ", " . $format[ $i ] : $format[ $i ];
			$updateValues = $updateValues ? $updateValues . ", " . "{$key}=values({$key})" : "{$key}=values({$key})";
			$i ++;
		}

		$query = $this->prepare( "INSERT INTO {$this->tableName} ({$columns}) VALUES ($values)
					ON DUPLICATE KEY UPDATE {$updateValues};", array_values( $venueData ) );

		//die($query);
		$this->executeQuery( $query );

		return $venue;
	}

	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}
