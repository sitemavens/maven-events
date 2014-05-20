<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class OrderManager extends \Maven\Core\OrderManager {

	private $registry;

	public function __construct() {

		$this->registry = \MavenEvents\Settings\EventsRegistry::instance();
		parent::__construct( $this->registry );
	}

	public function getAllOrders( \Maven\Core\Domain\OrderStatus $status = NULL ) {
		$filter = new \Maven\Core\Domain\OrderFilter();

		$filter->setPluginKey( $this->registry->getPluginKey() );

		if ( ! is_null( $status ) ) {
			$filter->setStatusID( $status->getId() );
		}
		//return $this->manager->getByPlugin( $this->registry->getPluginKey() );
		return $this->getOrders( $filter );
	}

	public function getCompletedOrders() {
		return $this->getAllOrders( OrderStatusManager::getCompletedStatus() );
	}

	public function getOrders( \Maven\Core\Domain\OrderFilter $filter, $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		$filter->setPluginKey( $this->registry->getPluginKey() );

		return parent::getOrders( $filter, $orderBy, $orderType, $start, $limit );
	}

	public function getOrdersCount( \Maven\Core\Domain\OrderFilter $filter ) {
		
		$filter->setPluginKey( $this->registry->getPluginKey() );
		
		return parent::getOrdersCount( $filter );
	}

	public function delete( $orderId ) {
		//TODO: remove attendes from event, when deleting the order

		parent::delete( $orderId );
	}

}