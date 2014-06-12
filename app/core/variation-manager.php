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

	/**
	 * 
	 * @param int $thingId
	 * @param \Maven\Core\Domain\Variation[] $variations
	 * @param array() $combinations
	 * @param string $tempIdentifier
	 */
	public function saveAll( $thingId, $variations, $combinations, $tempIdentifier = '*' ) {
		$pluginKey = $this->registry->getPluginKey();

		$variationMapper = new \Maven\Core\Mappers\VariationMapper( $this->registry );
		$variationOptionMapper = new \Maven\Core\Mappers\VariationOptionMapper();

		$variationMap = array();
		$optionMap = array();
		$activeVariations = array();
		$activeOptions = array();

		//save options and variations
		foreach ( $variations as $variation ) {
			$clientId = 0;
			//if id has the client identifier, saveit for later, and mark the variation as new
			if ( strpos( $variation->getId(), $tempIdentifier ) !== false ) {
				$clientId = $variation->getId();
				$variation->setId( false );
			}

			$variation->setPluginKey( $pluginKey );
			$variation->setThingId( $thingId );

			$variation = $variationMapper->save( $variation );

			$activeVariations[] = $variation->getId();
			//if we have a client id, set the mapper for later
			if ( $clientId ) {
				$variationMap[ $clientId ] = $variation->getId();
			}

			foreach ( $variation->getOptions() as $option ) {
				$clientId = 0;
				//if id has the client identifier, saveit for later, and mark the variation as new
				if ( strpos( $option->getId(), $tempIdentifier ) !== false ) {
					$clientId = $option->getId();
					$option->setId( false );
				}

				$option->setVariationId( $variation->getId() );

				$option = $variationOptionMapper->save( $option );

				$activeOptions[] = $option->getId();
				//if we have a client id, set the mapper for later
				if ( $clientId ) {
					$optionMap[ $clientId ] = $option->getId();
				}
			}
		}
		//delete missing variations
		$variationMapper->deleteMissingVariations( $thingId, $pluginKey, $activeVariations );
		//delete missing options
		$variationOptionMapper->deleteMissingOptions( $thingId, $pluginKey, $activeOptions );

		//save combinations (variationGroups)
		$variationGroupManager = new VariationGroupManager();

		$activeGroupKeys = array();

		foreach ( $combinations as $combination ) {
			//first replace temp ids on groupkey with database id
			$groupKey = explode( '-', $combination[ 'groupKey' ] );
			$fixedGroupKey = array();
			foreach ( $groupKey as $key ) {
				if ( strpos( $key, $tempIdentifier ) !== false ) {
					$fixedGroupKey[] = $optionMap[ $key ];
				} else {
					$fixedGroupKey[] = $key;
				}
			}

			//Store the ids in order
			sort( $fixedGroupKey );

			$variationGroup = new \Maven\Core\Domain\VariationGroup();

			$variationGroup->setId( $combination[ 'id' ] );
			$variationGroup->setGroupKey( implode( '-', $fixedGroupKey ) );
			$variationGroup->setPrice( $combination[ 'price' ] );
			$variationGroup->setPriceOperator( $combination[ 'priceOperator' ] );
			$variationGroup->setQuantity( $combination[ 'quantity' ] );
			//TODO: add the other fields

			$variationGroup->setThingId( $thingId );

			$variationGroupManager->save( $variationGroup );

			$activeGroupKeys[] = implode( '-', $fixedGroupKey );
		}


		//delete missing groups
		$variationGroupManager->deleteMissingGroupKeys( $thingId, $activeGroupKeys );
	}

}
