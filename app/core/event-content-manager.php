<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


use MavenEvents\Core\Domain\Event;
use MavenEvents\Core\Mappers\AttendeeMapper,
    MavenEvents\Core\Mappers\VenueMapper,
    MavenEvents\Core\Mappers\EventMapper;

class EventContentManager   {


	public function __construct() {
	}
  

	/**
	 * Get an event
	 * @param int $eventId
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function get( $contentId ) {

		$content = get_post( $contentId );

		return $content;
	}
	
	public function getEventContentBySlug( $slug ) {
		//TODO: CAMBIALE ESO DEL TAAAAAB!!! :D
		$post = get_page_by_path( $slug, OBJECT, EventsConfig::eventContentTypeName);
		
		return $post;
	}
  

	public function delete( $id ) {

		
	}

}

