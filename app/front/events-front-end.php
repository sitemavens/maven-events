<?php

namespace MavenEvents\Front;

class EventsFrontEnd {

	const InputKey = 'mvnEvents';

	public function __construct () {
		
	}

	public function addItem ( \Maven\Front\Thing $thing ) {

		/*** ****************************************
		 * 		Insert a new item to the CART
		 * ***************************************** */
		$event = \MavenEvents\Core\EventsApi::getEvent( $thing->getId() );

		$item = new \MavenEvents\Core\Domain\OrderItem( $thing->getPluginKey() );

		// Verify that the product has stock
		if ( $event->isSeatsEnabled() && !$event->hasVariations() && $event->getAvailableSeats() <= 0 ) {
			$item->setStatus( \Maven\Core\Message\MessageManager::createErrorMessage( 'Sorry, but we don\'t have available seats for the event' ) );
			return $item;
		}


		$variationName = "";
		\Maven\Loggers\Logger::log()->message( '\MavenEvents\EventsFrontEnd\addItem: Thing has variations: ' . count( $thing->getVariations() ) );

		if ( $thing->hasVariations() ) {

			$thingVariations = $thing->getVariations();

			//Check if the product has variations 
			if ( $event->hasVariations() ) {

				$variations = $event->getVariations();
				$variationIds = array();

				foreach ( $variations as $variation ) {

					\Maven\Loggers\Logger::log()->message( '\MavenEvents\EventsFrontEnd\addItem: Variation: ' . $variation->getId() );

					if ( isset( $thingVariations[ $variation->getId() ] ) && $thingVariations[ $variation->getId() ] ) {

						$variationName .= " " . $variation->getName() . ": " . $variation->getOption( $thingVariations[ $variation->getId() ]->getOptionId() )->getName();
						//$variationName = $variation->getName().":".$variationName;
						$variationIds[] = $thingVariations[ $variation->getId() ]->getOptionId();
					}
				}

				//TODO: Esto no esta del todo bien, deberia ser medio transparente la formacion de la clave de las variaciones.
				asort( $variationIds );

				$variationIds = implode( '-', $variationIds );

				\Maven\Loggers\Logger::log()->message( '\MavenEvents\EventsFrontEnd\addItem: Event price: ' . $event->getPrice() );

				// Create the cart
				$item = new \MavenEvents\Core\Domain\OrderItem( $thing->getPluginKey() );
				$item->setEvent( $event );
				$item->setPrice( \MavenEvents\Core\EventsApi::calculatePrice( $event, $variationIds ) );
				$item->setQuantity( $thing->getQuantity() );
				$item->setName( $event->getName() . $variationName );
				$item->setThingVariationId( $variationIds );
				$item->setThingId( $event->getId() );

				\Maven\Loggers\Logger::log()->message( 'MavenEvents/ShopFrontEnd/addItem: Variation price: ' . $item->getPrice() );

				//$this->addAttributes( $thing, $item );

				\Maven\Loggers\Logger::log()->message( 'MavenEvents/ShopFrontEnd/addItem: Price + Variations + Attribute: ' . $item->getPrice() );

				return $item;
			}
		} else {

			// Create the cart
			$item->setPrice( $event->getPrice()  );
			$item->setQuantity( $thing->getQuantity() );
			$item->setName( $event->getName() . $variationName );
			$item->setThingId( $event->getId() );

			//$item->addAttribute( $thing, $item );

			return $item;
		}
	}

	public static function registerFrontEndHooks () {
		$frontEnd = new EventsFrontEnd();

		$pluginKey = \MavenEvents\Settings\EventsRegistry::instance()->getPluginKey();
		add_filter( "maven/cart/addItem/{$pluginKey}", array( $frontEnd, 'addItem' ) );
	}

}
