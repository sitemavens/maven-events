<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationGroupManager extends \Maven\Core\VariationGroupManager {

	private $registry;

	public function __construct() {

		$this->registry = \MavenEvents\Settings\EventsRegistry::instance();

		parent::__construct( $this->registry );
	}

	public function save( \Maven\Core\Domain\VariationGroup $variationGroup ) {

		if ( ! $variationGroup->getPluginKey() ) {
			$variationGroup->setPluginKey( $this->registry->getPluginKey() );
		}


		parent::save( $variationGroup );
	}

	public function deleteGroups( $thingId ) {

		$pluginKey = $this->registry->getPluginKey();

		$variationGroupMapper = new \Maven\Core\Mappers\VariationGroupMapper();

		$variationGroupMapper->deleteByThingId( $thingId, $pluginKey );
	}

}
