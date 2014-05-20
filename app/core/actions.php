<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Actions {

	/**
	 * @label Add or update an Attendee
	 * @action updateAttendee
	 * @description It will fire when an attendee is added
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 */
	public static function updateAttendee( \MavenEvents\Core\Domain\Attendee $attendee, $update = false ) {

		$action = $update ? 'Update Attendee' : 'Add attendee';
		$event = new \Maven\Tracking\Event();
		$event->setAction( $action );
		$event->setCategory( 'Attendees' );
		$event->setLabel( $action );
		$event->setValue( $attendee->getId() );
		$event->addProperty( 'First Name', $attendee->getFirstName() );
		$event->addProperty( 'Last Name', $attendee->getLastName() );
		$event->addProperty( 'Email', $attendee->getEmail() );

		\Maven\Tracking\Tracker::addEvent( $event );

		//do_action('action:mavenEvents/attendee/add', $attendee);
	}

	/**
	 * @label Add Order
	 * @action addOrder
	 * @description It will fire when an order is added
	 * @param \MavenEvents\Core\Domain\Order $order
	 */
//	public static function addOrder( \MavenEvents\Core\Domain\Order $order ){
//		
//		$event = new \Maven\Tracking\Event();
//		$event->setAction( 'Add Order');
//		$event->setCategory('Orders');
//		$event->setLabel('Add Order');
//		$event->setValue( $order->getId() );
//		
//		\Maven\Tracking\Tracker::addEvent( $event );
//		
//		do_action('action:mavenEvents/order/add', $order);
//	}

	/**
	 * @label Add Presenter
	 * @action AddPresenter
	 * @description It will fire when a presenter is added
	 * @param \MavenEvents\Core\Domain\Presenter $presenter
	 */
	public static function AddPresenter( \MavenEvents\Core\Domain\Presenter $presenter ) {

		$event = new \Maven\Tracking\Event();
		$event->setAction( 'Add Presenter' );
		$event->setCategory( 'Presenters' );
		$event->setLabel( 'Add Presenter' );
		$event->setValue( $presenter->getId() );

		\Maven\Tracking\Tracker::addEvent( $event );

		do_action( 'action:mavenEvents/presenter/add', $presenter );
		
		/* Post in facebook */
		$post = new \Maven\SocialNetworks\Post();
		$post->setName($presenter->getDisplayName());
		$post->setCaption($presenter->getDisplayName());
		$post->setMessage('Added new presenter!');
		$post->setDescription($presenter->getDescription());
		$post->setLink($presenter->getWebsite());
		//$post->setPicture($presenter->getFeaturedImageUrl());
		
		\Maven\SocialNetworks\SocialNetwork::post( $post );
	}

	/**
	 * @label Add Event
	 * @action AddEvent
	 * @description It will fire when an event is added
	 * @param \MavenEvents\Core\Domain\Event $mavenEvent
	 */
	public static function AddEvent( \MavenEvents\Core\Domain\Event $mavenEvent ) {

		$event = new \Maven\Tracking\Event();
		$event->setAction( 'Add Event' );
		$event->setCategory( 'Events' );
		$event->setLabel( 'Added Event' . $mavenEvent->getName() );
		$event->setValue( $mavenEvent->getId() );
		$event->addProperty( 'Name:', $mavenEvent->getName() );
		$event->addProperty( 'Max Group Registrants:', $mavenEvent->getMaxGroupRegistrants() );

		\Maven\Tracking\Tracker::addEvent( $event );

		do_action( 'action:mavenEvents/event/add', $mavenEvent );
		
		/* create event */
		$facebookEvent= new \Maven\SocialNetworks\Event();
		$facebookEvent->setName($mavenEvent->getName());
		$startDate=$mavenEvent->getEventStartDate();
		$startTime=$mavenEvent->getEventStartTime();
		$facebookEvent->setStartTime("{$startDate}T{$startTime}-0300");
		$endDate=$mavenEvent->getEventEndDate();
		$endTime=$mavenEvent->getEventEndTime();
		$facebookEvent->setEndTime("{$endDate}T{$endTime}-0300");
		$facebookEvent->setDescription($mavenEvent->getDescription());
		$facebookEvent->setLocation($mavenEvent->getVenue()->getName());
		$facebookEvent->setPicture($mavenEvent->getFeaturedImageUrl());
		$facebookEvent->setTicketUri($mavenEvent->getFullUrl());
		
		\Maven\SocialNetworks\SocialNetwork::event( $facebookEvent );
	}

	/**
	 * @label Modify Event
	 * @action ModifyEvent
	 * @description It will fire when an event is modified
	 * @param \MavenEvents\Core\Domain\Presenter $presenter
	 */
	public static function UpdateEvent( \MavenEvents\Core\Domain\Event $mavenEvent ) {

		$event = new \Maven\Tracking\Event();
		$event->setAction( 'Update Event' );
		$event->setCategory( 'Events' );
		$event->setLabel( 'Updated Event: ' . $mavenEvent->getName() );
		$event->setValue( $mavenEvent->getId() );
		$event->addProperty( 'Name:', $mavenEvent->getName() );
		$event->addProperty( 'Max Group Registrants:', $mavenEvent->getMaxGroupRegistrants() );

		\Maven\Tracking\Tracker::addEvent( $event );

		do_action( 'action:mavenEvents/event/update', $mavenEvent );

		
		
		
	}

}