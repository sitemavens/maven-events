<?php

//
//namespace MavenEvents\Core;
//
//// Exit if accessed directly 
//if ( ! defined( 'ABSPATH' ) )
//	exit;
//
//
//
//class Cart extends \Maven\Core\Cart{
//	
//	/**
//	 *
//	 * @var \MavenEvents\Core\Cart
//	 */
//	private static $instance;
//	
//	public function __construct( ) {
//		
//		$registry = \MavenEvents\Settings\EventsRegistry::instance();
//		
//		parent::__construct( $registry );
//	}
//	
//	/**
//	 * 
//	 * @return \Maven\Core\Cart
//	 */
//	public static function current(){
//		
//		if ( ! self::$instance ){
//			self::$instance = new \MavenEvents\Core\Cart();
//		}
//		
//		return self::$instance;
//	}
//	
//	public function addToCart( \Maven\Core\Domain\OrderItem $item ){ 
//		return parent::addToCart( $item, \MavenEvents\Settings\EventsRegistry::instance() );
//	}
//	
//	
//	
//	
//}