<?php

namespace MavenEvents\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderMapper extends \Maven\Core\Mappers\OrderMapper {

	public function delete( $orderId ) {
		//Remove attendess from events
		$order=$this->get($orderId);
		
		foreach ($order->getItems() as $item){
			$event=$item->getEvent();
			
			if($event){
				foreach ($item->getAttendess() as $attendee){
					//remove attendees from event
				}
					
			}
			
		}

		//delete the order
		return parent::delete( $orderId );
	}
}
