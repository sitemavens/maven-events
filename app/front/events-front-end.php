<?php

namespace MavenEvents\Front;

class EventsFrontEnd {

	const InputKey = 'mvnEvents';

	public function __construct () {
		
	}

	public function addItem ( \Maven\Front\Thing $thing ) {

		/* ******************************************
		 * 		Insert a new item to the CART
		 * ***************************************** */
		$event = \MavenEvents\Core\EventsApi::getEvent( $thing->getId() );

		$variationName = "";
		if ( $thing->hasVariations() ) {

			$thingVariations = $thing->getVariations();

			//Check if the product has variations 
			if ( $event->hasVariations() ) {

				$variations = $event->getVariations();
				$items = array();

				/* @var $variations type */
				foreach ( $variations as $variation ) {

					/* @var $variation type */
					if ( isset( $thingVariations[ $variation->getId() ] ) && $thingVariations[ $variation->getId() ] ) {

						$variationName .= " " . $variation->getOption( $thingVariations[ $variation->getId() ] )->getName();
						$variationId = $thingVariations[ $variation->getId() ]->getOptionId();

						// Create the cart
						$item = new \MavenEvents\Core\Domain\OrderItem( $thing->getPluginKey() );
						$item->setEvent( $event );
						$item->setPrice( $thing->getPrice() );
						$item->setQuantity( $thing->getQuantity() );
						$item->setName( $event->getName() . $variationName );
						$item->setThingId( $event->getId() );
						$item->setVariationId( $variationId );

						$items[] = $item;
						
					}
				}
				 
				return $items;
				
			}
		}
		 else {
				
				// Create the cart
				$item = new \MavenEvents\Core\Domain\OrderItem( $thing->getPluginKey() );
				$item->setEvent( $event );
				$item->setPrice( $thing->getPrice() );
				$item->setQuantity( $thing->getQuantity() );
				$item->setName( $event->getName() );
				$item->setThingId( $event->getId() );
				return $item;
				
			}
	}

	public static function registerFrontEndHooks () {
		$frontEnd = new EventsFrontEnd();

		$pluginKey = \MavenEvents\Settings\EventsRegistry::instance()->getPluginKey();
		add_filter( "maven/cart/addItem/{$pluginKey}", array( $frontEnd, 'addItem' ) );
	}

}
