<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationManager extends \Maven\Core\VariationManager {

	private $registry;

	public function __construct() {

		$this->registry = \MavenEvents\Settings\EventsRegistry::instance();
		parent::__construct( $this->registry );
	}

	/**
	 * @param type $eventId
	 * @return type
	 */
	public function getVariations( $eventId ) {
		return parent::getThingVariations( $eventId );
	}

	public function save( \Maven\Core\Domain\Variation $variation ) {

		if ( ! $variation->getPluginKey() ) {
			$variation->setPluginKey( $this->registry->getPluginKey() );
		}

		return parent::save( $variation );
	}

}
