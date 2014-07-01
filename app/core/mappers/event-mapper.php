<?php

namespace MavenEvents\Core\Mappers;

use \MavenEvents\Core\EventsConfig;
use \MavenEvents\Core\Domain\EventFilterType;

class EventMapper extends \Maven\Core\Db\WordpressMapper {

	private $eventsVenuesTable = 'mvne_events_venues';
	//private $eventsPricesTable = 'mvne_events_prices';
	private $eventsAttendeesTable = 'mvne_events_attendees';

	//private $eventsCategoriesTable = 'mvne_events_categories';

	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::eventTableName );
	}

	/**
	 * Set the primary attendee. He is the person who buys the tickets
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @param \MavenEvents\Core\Domain\Order $order
	 */
	public function setPrimaryAttendee( \MavenEvents\Core\Domain\Attendee $attendee, \MavenEvents\Core\Domain\Event $event, \Maven\Core\Domain\Order $order ) {

		$query = $this->prepare( "UPDATE {$this->eventsAttendeesTable} SET primary_attendee_id = %d WHERE event_id = %d AND order_id = %d AND attendee_id <> %d ", $attendee->getId(), $event->getId(), $order->getId(), $attendee->getId() );

		return $this->executeQuery( $query );
	}

	public function getAll( $orderBy = "name" ) {

		$events = array( );
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {
			$event = new \MavenEvents\Core\Domain\Event();
			$this->fillObject( $event, $row );
			$events[ ] = $event;
		}

		return $events;
	}

	public function closeEvent( $eventId ) {

		$query = $this->prepare( "UPDATE {$this->tableName} SET closed = 1 where id = %d ", $eventId );

		return $this->executeQuery( $query );
	}

	public function openEvent( $eventId ) {

		$query = $this->prepare( "UPDATE {$this->tableName} SET closed = 0 where id = %d ", $eventId );

		return $this->executeQuery( $query );
	}

	public function getAttendeesCount( $eventId ) {

		$query = $this->prepare( "SELECT COUNT('e') as count FROM {$this->eventsAttendeesTable} where event_id = %d ", $eventId );

		return $this->getVar( $query );
	}

	public function removeVenue( $id ) {

		$query = $this->prepare( "update {$this->tableName} set venue_id = null where venue_id=%d", $id );

		$this->executeQuery( $query );
	}

	public function getEventsByType( $type, $orderBy = 'event_start_date', $orderType = 'desc', $start = 0, $limit = 100 ) {

		$where = $this->getWhereByType( $type );

		if ( $where )
			$where = ' and ' . $where;

		if ( ! $orderBy )
			$orderBy = 'event_start_date';

		$query = "select 
						{$this->tableName}.`id`,
						{$this->tableName}.`name`,
						`description`,
						`price`,
						`registration_start_date`,
						`registration_end_date`,
						`registration_start_time`,
						`registration_end_time`,
						`event_start_date`,
						`event_end_date`,
						`event_start_time`,
						`event_end_time`,
						`venue_id`,
						attendee_limit,
						summary,
						featured_image,
						gallery_images,
						seats_enabled,
						{$this->tableName}.maillist
					from {$this->tableName} 
					where  1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		$query = $this->prepare( $query, $start, $limit );

		$eventsRows = $this->getQuery( $query );

		$events = array( );
		foreach ( $eventsRows as $eventRow ) {

			$event = new \MavenEvents\Core\Domain\Event();
			$this->fillObject( $event, $eventRow );


			$postEvent = get_post( $eventRow->id );

			//if ( ! $postEvent )
			//	throw new \Maven\Exceptions\NotFoundException( 'PostEvent not found!' );

			if ( $postEvent )
				$event->setUrl( $postEvent->post_name );

			$events[ ] = $event;
		}

		return $events;
	}

	public function getEventsCount( $type ) {

		$where = $this->getWhereByType( $type );

		if ( $where )
			$where = ' and ' . $where;

		$query = "select count(*)
					from {$this->tableName} 
					where  1=1 
					{$where}";

		return $this->getVar( $query );
	}

	private function getWhereByType( $type ) {

		$where = "";
		$today = new \Maven\Core\MavenDateTime();
		$to = $today->mySqlFormatDate();

		switch ( $type ) {
			case EventFilterType::last7days:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P7D' );
				$from = $fromDate->mySqlFormatDate();
				$where = "event_start_date >= '{$from}' and event_start_date <= '{$to}'";
				break;

			case EventFilterType::last14days:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P14D' );
				$from = $fromDate->mySqlFormatDate();
				$where = "event_start_date >= '{$from}' and event_start_date <= '{$to}'";
				break;

			case EventFilterType::lastMonth:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1M' );
				$from = $fromDate->mySqlFormatDate();
				$where = "event_start_date >= '{$from}' and event_start_date <= '{$to}'";
				break;

			case EventFilterType::lastweek:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1W' );
				$from = $fromDate->mySqlFormatDate();

				$where = "event_start_date >= '{$from}' and event_start_date <= '{$to}'";
				break;

			case EventFilterType::lastyear:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1Y' );
				$from = $fromDate->mySqlFormatDate();

				$where = "event_start_date >= '{$from}' and event_start_date <= '{$to}'";
				break;

			case EventFilterType::comingNext:
				$where = "event_start_date >= '{$to}' ";
				break;

			case EventFilterType::comingWeek:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1W' );
				$from = $fromDate->mySqlFormatDate();
				$where = "event_start_date >= '{$to}' and event_start_date <= '{$from}'";
				break;

			case EventFilterType::comingMonth:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1M' );
				$from = $fromDate->mySqlFormatDate();
				$where = "event_start_date >= '{$to}' and event_start_date <= '{$from}'";
				break;
			case EventFilterType::all:
				$where = "";
				break;
		}

		return $where;
	}

	/**
	 * Return a event object
	 * @param int $id/array
	 * @param bool $readWpPost
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function get( $id, $readWpPost = true ) {

		$event = new \MavenEvents\Core\Domain\Event();

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}


		$row = $this->getRowById( $id );

		if ( ! $row ){
			return $event;
		}

		if ( $readWpPost ) {
			$postEvent = get_post( $id );

			if ( $postEvent ) {
				$event->setUrl( $postEvent->post_name );
			}
		}

		$this->fillObject( $event, $row );

		return $event;
	}

	public function remove( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		//Delete related eventprices
		$eventPriceMapper = new EventPricesMapper();
		$eventPrices = $eventPriceMapper->getEventPrices( $id );
		foreach ( $eventPrices as $eventPrice ) {
			if ( $eventPrice->getId() )
				$eventPriceMapper->delete( $eventPrice->getId() );
		}

		//delete event
		$this->delete( $id );

		//delete post
		wp_delete_post( $id );
	}

	/** Create or update the donation to the database
	 * 
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function save( \MavenEvents\Core\Domain\Event $event ) {

		$event->sanitize();

		$eventData = array(
			'id' => $event->getId(),
		    'name' => $event->getName(),
		    'description' => $event->getDescription(),
			'price' => $event->getPrice(),
		    'registration_start_date' => $event->getRegistrationStartDate(),
		    'registration_end_date' => $event->getRegistrationEndDate(),
		    'registration_end_time' => $event->getRegistrationEndTime(),
		    'registration_start_time' => $event->getRegistrationStartTime(),
		    'event_start_date' => $event->getEventStartDate(),
		    'event_end_date' => $event->getEventEndDate(),
		    'event_start_time' => $event->getEventStartTime(),
		    'event_end_time' => $event->getEventEndTime(),
		    'featured_image' => $event->getFeaturedImage(),
		    'venue_id' => $event->getVenueId(),
		    'allow_group_registration' => $event->isAllowGroupRegistration() ? 1 : 0,
		    'max_group_registrants' => $event->getMaxGroupRegistrants(),
		    'maillist' => $event->getMaillist(),
		    'attendee_limit' => $event->getAttendeeLimit(),
		    'gallery_images' => $event->getGalleryImagesForDB(),
		    'posts_content' => $event->getPostsContentForDB(),
		    'summary' => $event->getSummary(),
		    'closed' => $event->isClosed() ? 1 : 0,
			'seats_enabled' => $event->isSeatsEnabled() ? 1 : 0,
			'available_seats' => $event->getAvailableSeats()
		);

		$format = array(
			'%d', //id
		    '%s', //name
		    '%s', //description
			'%f', //price
		    '%s', //registration_start_date
		    '%s', //registration_end_date
		    '%s', //registration_end_time
		    '%s', //registration_start_time
		    '%s', //event_start_date
		    '%s', //event_end_date,
		    '%s', //event_start_time
		    '%s', //event_end_time
		    '%s', //featured_image
		    '%d', //venue_id
		    '%d', //allow_group_registration
		    '%d', //max_group_registrants
		    '%s', // maillist
		    '%d', // attendee_limit
		    '%s', // gallery_images
		    '%s', // posts_content
		    '%s', // summary
		    '%d',  // closed
			'%d',  // seats_enabled
			'%d'  // available_seats
		);
		
		$columns = '';
		$values  = '';
		$updateValues = '';
		$i =0;
		
		foreach( $eventData as $key=>$value ){
			$columns =  $columns ?  $columns.", ".$key : $key;
			$values = $values ? $values.", ".$format[$i] : $format[$i];
			$updateValues = $updateValues ? $updateValues.", "."{$key}=values({$key})" : "{$key}=values({$key})";
			$i++;
		}
		
		$query = $this->prepare( "INSERT INTO {$this->tableName} ({$columns}) VALUES ($values)
					ON DUPLICATE KEY UPDATE {$updateValues};",  array_values($eventData));
		//die($query);
		$this->executeQuery($query);
		
		return $event;
	}

	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}