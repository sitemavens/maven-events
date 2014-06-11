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

	/**
	 * Delete variation goups "NOT" in the group keys array
	 * 
	 * @param int $thingId
	 * @param array $groupKeys
	 * @return int
	 */
	public function deleteMissingGroupKeys( $thingId, $groupKeys = array() ) {
		$pluginKey = $this->registry->getPluginKey();
		//get groupkeys in incoming array
		$variationGroupMapper = new \Maven\Core\Mappers\VariationGroupMapper();

		return $variationGroupMapper->deleteMissingGroupKeys( $thingId, $pluginKey, $groupKeys );
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
