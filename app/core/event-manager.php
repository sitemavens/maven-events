<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use MavenEvents\Core\Domain\Event;
use MavenEvents\Core\Mappers\VenueMapper,
    MavenEvents\Core\Mappers\EventMapper;

class EventManager implements iSearchManager {

	private $eventMapper = null;

	public function __construct() {
		$this->eventMapper = new EventMapper();
	}

	/**
	 * Set the primary attendee. He is the person who buys the tickets
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @param \MavenEvents\Core\Domain\Order $order
	 */
	public function setPrimaryAttendee( \MavenEvents\Core\Domain\Attendee $attendee, \MavenEvents\Core\Domain\Event $event, \Maven\Core\Domain\Order $order ) {

		$this->eventMapper->setPrimaryAttendee( $attendee, $event, $order );
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Event $donation
	 * @return \Maven\Core\Message\Message
	 */
	function addEvent( \MavenEvents\Core\Domain\Event $event ) {

		$venueMapper = new VenueMapper();
		$attendeeManager = new AttendeeManager();
		$eventPricesMapper = new Mappers\EventPricesMapper();
		$presentersMapper = new Mappers\PresenterMapper();
		$categoriesMapper = new Mappers\CategoryMapper();

		if ( $event->hasVenue() ) {
			$venue = $venueMapper->get( $event->getVenue()->getId() );
			if ( !$venue ) {
				throw new \Maven\Exceptions\NotFoundException( 'The venue doesn\'t exist: ' . $event->getVenue()->getId() );
			}

			$event->setVenue( $venue );
		}

		$update = false;
		if ( $event->getId() ) {
			$update = true;
		}

		//We save the event, we need the id for the related data
		$savedEvent = $this->eventMapper->save( $event );

		if ( $event->hasAttendees() ) {

			//TODO: Como hacemos esto!? 
//			if ( count( $event->getAttendees() ) > $event->getMaxGroupRegistrants() )
//				return \Maven\Core\Message\MessageManager::createErrorMessage ( 'The number of tickets ' );

			$attendees = $event->getAttendees();

			$mailList = \Maven\MailLists\MailListFactory::getMailList();


			// Register attendees to maillist
			foreach ( $attendees as $attendee ) {

				$attendee->setMaillist( $event->getMaillist() );

				if ( $attendeeManager->isAlreadyRegistered( $attendee, $event ) )
					continue;

				// We need to add registered event to the attendee
				$registeredEvent = $attendee->newRegisteredEvent();
				$registeredEvent->setAmountPaid( $attendee->getAmountPaid() );
				$registeredEvent->setNumberOfTickets( $attendee->getNumberOfTickets() );
				$registeredEvent->setEvent( $event );
			}

			$attendeeManager->addAttendees( $attendees, $savedEvent );

			//Once we have added the attendees and ensure they are save, we added to the maillist
			foreach ( $attendees as $attendee )
				if ( $attendee->getMaillist() )
					$mailList->subscribe( $attendee, false );
		}

		//if ( $event->hasPrices() ) {
		$eventPricesMapper->addEventPrices( $event->getPrices(), $savedEvent );
		//}
		//if ( $event->hasPresenters() ) {
		$presentersMapper->addPresenters( $event->getPresenters(), $savedEvent );
		//}

		$categoriesMapper->addCategories( $event->getCategories(), $savedEvent );

		// If variations isn't enabled we have to delete everything. Just in case the product had variations before.
		if ( ! $event->isVariationsEnabled() ) {
			$variationsManager = new VariationManager();
			$variationsManager->deleteThingVariations( $event->getId() );
		}
		
		if ( $update )
			Actions::UpdateEvent( $event );
		else
			Actions::AddEvent( $event );

		return $savedEvent;
	}

	public function cloneEvent( $eventId ) {
		//Get the original event
		$event = $this->get( $eventId );

		//remove the Id
		$event->setId( null );

		//Prepend "Copy of" on title
		$event->setName( "Copy of " . $event->getName() );

		//close event
		$event->setClosed( true );

		//remove the attendees
		if ( $event->hasAttendees() )
			$event->setAttendees( array( ) );

		//remove the ids from the prices
		if ( $event->hasPrices() ) {
			foreach ( $event->getPrices() as $price ) {
				$price->setId( null );
			}
		}

		$originalPosts = $event->getPostsContent();
		$postRelation = array( );
		foreach ( $originalPosts as $post ) {
			//Check if the id has already be processes, in case that the post_id are repeated
			if ( ! array_key_exists( $post[ 'id' ], $postRelation ) ) {
				$newPostId = \Maven\Core\Utils::duplicatePost( $post[ 'id' ] );
				$postRelation[ $post[ 'id' ] ] = $newPostId;
				$event->removePostContent( $post[ 'id' ] );
				$event->addPostContent( $newPostId );
			} else {
				$event->removePostContent( $post[ 'id' ] );
			}
		}

		//save the new event
		$this->addEvent( $event );

		//Associate the event id to the created posts
		foreach ( $event->getPostsContent() as $post ) {
			//Asociate the new event with the post
			update_post_meta( $post[ 'id' ], EventsContent::eventColumnName, $event->getId() );

			//Fix the parent relation between the new posts
			if ( $post[ 'parent' ] ) {
				\Maven\Core\Utils::updatePostParent( $post[ 'id' ], $postRelation[ $post[ 'parent' ] ] );
			}
		}

		return $event;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Event $event
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 * @return type
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addAttendee( \MavenEvents\Core\Domain\Event $event, \MavenEvents\Core\Domain\Attendee $attendee ) {

		if ( ! $event->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Event id is required' );

		if ( ! $attendee->getEmail() )
			throw new \Maven\Exceptions\MissingParameterException( 'Attendee email is required' );

		$attendeeManager = new AttendeeManager();

//		if ( $attendeeManager->isAlreadyRegistered( $attendee, $event ) )
//			return \Maven\Core\Message\MessageManager::createErrorMessage ( 'The attendee is already registered' );
//				

		$count = -10;

		//TODO: Check if is there enough room
		if ( $event->getAttendeeLimit() > 0 ) {
			$count = $this->getAttendeesCount( $event->getId() );

			if ( $count >= $event->getAttendeeLimit() )
				return \Maven\Core\Message\MessageManager::createErrorMessage( 'There is no more room for this event' );
		}

		//Set the event maillist to the attendee
		$attendee->setMaillist( $event->getMaillist() );

		// We need to add registered event to the attendee
		$registeredEvent = $attendee->newRegisteredEvent();
		$registeredEvent->setAmountPaid( $attendee->getAmountPaid() );
		$registeredEvent->setNumberOfTickets( $attendee->getNumberOfTickets() );
		$registeredEvent->setEvent( $event );
		$registeredEvent->setStatus( $attendee->getStatus() );
		$registeredEvent->setOrderId( $attendee->getOrderId() );

		$attendeeManager->addAttendee( $attendee, $event );

		// If it is the last space, we have to close the event
		if ( $count + 1 == $event->getAttendeeLimit() )
			$this->closeEvent( $event->getId() );

		$event->addAttendee( $attendee );

		//TODO: Send emails

		return \Maven\Core\Message\MessageManager::createRegularMessage( 'Attendee Added' );
	}

	private function closeEvent( $eventId ) {

		$this->eventMapper->closeEvent( $eventId );
	}

	/**
	 * Get an event
	 * @param int $eventId
	 * @return \MavenEvents\Core\Domain\Event
	 */
	public function get( $eventId ) {

		if ( intval( $eventId ) === 0 ) {
			throw new \Maven\Exceptions\MissingParameterException( "Event id is required" );
		}

		$event = $this->eventMapper->get( $eventId );

		$this->loadEventInformation( $event );

		return $event;
	}

	/**
	 * 
	 * @param string $slug
	 * @return \MavenEvents\Core\Domain\Event
	 * @throws \Maven\Exceptions\NotFoundException
	 */
	public function getEventBySlug( $slug ) {

		$post = get_page_by_path( $slug, OBJECT, EventsConfig::eventTypeName );

		if ( !$post ) {
			throw new \Maven\Exceptions\NotFoundException( 'Post not found: ' . $slug );
		}

		return $this->getEventFromPost( $post );
	}

	public function getEventFromPost( $wpEventPost ) {

		if ( !is_object( $wpEventPost ) || !isset( $wpEventPost->ID ) ) {
			throw new \Maven\Exceptions\MissingParameterException( "Event post is required" );
		}

		$event = $this->eventMapper->get( $wpEventPost->ID, false );

		if ( !$event ) {
			throw new \Maven\Exceptions\NotFoundException( 'Event not found' );
		}

		$event->setUrl( $wpEventPost->post_name );

		$this->loadEventInformation( $event );

		return $event;
	}

	private function loadEventInformation( Event $event ) {

		// By default events are closed
		$event->setClosed(false);
		
		$venueMapper = new VenueMapper();
		//$attendeeManager = new AttendeeManager();
		$eventPricesMapper = new Mappers\EventPricesMapper();
		$presentersMapper = new Mappers\PresenterMapper();
		$categoriesMapper = new Mappers\CategoryMapper();

		if ( $event->getVenueId() )
			$event->setVenue( $venueMapper->get( $event->getVenueId() ) );

		//Removed Attendees list on the event load, it should be loaded async.
		//$event->setAttendees( $attendeeManager->getEventAttendees( $event->getId() ) );

		$event->setPrices( $eventPricesMapper->getEventPrices( $event->getId() ) );
		$event->setPresenters( $presentersMapper->getEventPresenters( $event->getId() ) );
		$event->setCategories( $categoriesMapper->getEventCategories( $event->getId() ) );

		//Check if we have to set the event as closed or not. 
		$today = strtotime( \Maven\Core\MavenDateTime::getWPCurrentDateTime() );

		$eventRegistrationEndDate = strtotime( $event->getRegistrationEndDate() );
		$eventRegistrationEndTime = strtotime( $event->getRegistrationEndTime() );
		$currentTime = time();

		if ( $eventRegistrationEndDate < $today ) {
			$event->setClosed( true );
		} elseif ( $eventRegistrationEndDate == $today && $eventRegistrationEndTime > $currentTime ) {
			$event->setClosed( true );
//			var_dump( date('G:i:s', $currentTime ) );
//			var_dump( date('G:i:s',$eventRegistrationEndTime )) ;
		}
		
		$variationsManager = new VariationManager();
		
		if ($event->getId() && $event->isVariationsEnabled() ) {
			$event->setVariations( $variationsManager->getVariations( $event->getId() ) );
		}

		return $event;
	}

	/**
	 * 
	 * @param string $type
	 * @param string $orderBy
	 * @param string $orderType
	 * @param int $start
	 * @param int $limit
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public function getEventsByType( $type, $orderBy = 'event_start_date', $orderType = 'desc', $start = 0, $limit = 100 ) {

		$events = $this->eventMapper->getEventsByType( $type, $orderBy, $orderType, $start, $limit );

		foreach ( $events as $event ) {
			$event = $this->loadEventInformation( $event );
		}

		return $events;
	}

	/**
	 * Get all events
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public function getAll() {

		$filter = new \MavenEvents\Core\Domain\EventFilter();
		$filter->setType( Domain\EventFilterType::all );

		return $this->getEvents( $filter );
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\EventFilter $filter
	 * @return \MavenEvents\Core\Domain\Event[]
	 */
	public function getEvents( \MavenEvents\Core\Domain\EventFilter $filter, $orderBy = 'name', $orderType = 'asc', $start = 0, $limit = 1000 ) {

		if ( $filter->getType() )
			return $this->getEventsByType( $filter->getType(), $orderBy, $orderType, $start, $limit );
	}

	public function getEventsCount( \MavenEvents\Core\Domain\EventFilter $filter ) {
		return $this->eventMapper->getEventsCount( $filter->getType() );
	}

	public function getAttendeesCount( $eventId ) {

		if ( ! $eventId && ! ( int ) $eventId )
			throw new \Maven\Exceptions\MissingParameterException( 'Event id is required' );

		return $this->eventMapper->getAttendeesCount( $eventId );
	}

	/**
	 * Generate the Schema.org html needed for an event
	 * @param int $eventId/OBJECT 
	 * @param bool $echo
	 * @return string
	 */
	public function schemaOrgEvent( $event, $echo = true ) {

		if ( ! $event )
			throw new \Maven\Exceptions\MissingParameterException( 'Event is required' );

		if ( is_numeric( $event ) )
			$event = $this->get( $event );
		elseif ( ! is_object( $event ) )
			throw new \Maven\Exceptions\MissingParameterException( 'Event is required' );

		$schemaEvent = new \Maven\Seo\Schemas\Event();

		$schemaEvent->setName( $event->getName() );
		$schemaEvent->getLocation()->setName( $event->getVenue()->getName() );
		$schemaEvent->getLocation()->setUrl( $event->getUrl() );
		$schemaEvent->setUrl( $event->getUrl() );
		$schemaEvent->setEndDate( '2016-04-21T20:00' );

		$attendes = $event->getAttendees();
		foreach ( $attendes as $attende ) {

			$schemaAttende = new \Maven\Seo\Schemas\Person();
			$schemaAttende->setName( $attende->getFirstName() . " " . $attende->getLastName() );
			$schemaAttende->setEmail( $attende->getEmail() );

			$schemaEvent->addAttendee( $schemaAttende );
		}

		$schemaEvent->getOffers()->getPriceSpecification()->setPrice( 231 );

		if ( $echo )
			\Maven\Seo\SchemaOrg::eventHtml( $schemaEvent );
		else
			return \Maven\Seo\SchemaOrg::eventHtml( $schemaEvent, false );
	}

	public function delete( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );

		return $this->eventMapper->remove( $id );
	}

	/**
	 * Set event attendees as approved
	 * @param \MavenEvents\Core\Domain\Event $event
	 */
	public function approveAttendees( \MavenEvents\Core\Domain\Event $event ) {

		$attendees = $event->getNewAttendees();
		$attendeeManager = new AttendeeManager();

		foreach ( $attendees as $attendee ) {

			$registeredEvent = $attendeeManager->getEventRegistrationDetails( $event, $attendee );

			if ( $registeredEvent ) {

				$registeredEvent->setStatus( AttendeeStatusManager::getApprovedStatus() );

				$attendeeManager->updateRegistrationAttendeeStatus( $registeredEvent );
			}
		}
	}

}

