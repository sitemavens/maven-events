<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Installer {
	
	public function __construct() {
		;
	}
	
	public  function install(){
		
		global $wpdb;

		$create = array(
			
			"CREATE TABLE  IF NOT EXISTS `mvne_events` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(500) NOT NULL,
				`description` text,
				`registration_start_date` date DEFAULT NULL,
				`registration_end_date` date DEFAULT NULL,
				`registration_start_time` time DEFAULT NULL,
				`registration_end_time` time DEFAULT NULL,
				`event_start_date` date DEFAULT NULL,
				`event_end_date` date DEFAULT NULL,
				`event_start_time` time DEFAULT NULL,
				`event_end_time` time DEFAULT NULL,
				`featured_image` varchar(500) DEFAULT NULL,
				`venue_id` int(11) DEFAULT NULL,
				`allow_group_registration` tinyint(4) NOT NULL DEFAULT '0',
				`max_group_registrants` tinyint(4) DEFAULT NULL,
				`maillist` varchar(45) DEFAULT NULL,
				`closed` tinyint(4) NOT NULL DEFAULT '0',
				`attendee_limit` tinyint(4) DEFAULT NULL,
				`gallery_images` varchar(256) DEFAULT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`summary` varchar(500) DEFAULT NULL,
				`price` float DEFAULT NULL,
				`seats_enabled` BOOLEAN NOT NULL,
				`available_seats` INT NOT NULL,
				PRIMARY KEY (`id`)

			  )  ",
			
			"CREATE  TABLE  IF NOT EXISTS `mvne_events_prices` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`event_id` INT NOT NULL ,
				`price` FLOAT NOT NULL ,
				`name` VARCHAR(255) NOT NULL ,
				`exclusive_for_members` TINYINT NOT NULL DEFAULT 0,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			  ",
			
			"CREATE TABLE IF NOT EXISTS `mvne_attendees` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`profile_id` int(11) DEFAULT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			  ) ",
			
			"CREATE TABLE IF NOT EXISTS `mvne_events_attendees` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`attendee_id` int(11) NOT NULL,
				`event_id` int(11) NOT NULL,
				`number_of_tickets` int(11) NOT NULL,
				`amount_paid` float DEFAULT NULL,
				`checked_in` tinyint(4) DEFAULT NULL,
				`checked_in_datetime` datetime DEFAULT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			  ) ",
			
			"CREATE TABLE IF NOT EXISTS `mvne_venues` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`name` VARCHAR(255) NOT NULL ,
				`description` text,
				`address` VARCHAR(500) NULL ,
				`address2` VARCHAR(500) NULL ,
				`city` VARCHAR(255) NULL ,
				`state` VARCHAR(255) NULL ,
				`zip` VARCHAR(30) NULL ,
				`country` VARCHAR(50) NULL ,
				`contact` VARCHAR(255) NULL ,
				`phone` VARCHAR(50) NULL ,
				`twitter` VARCHAR(50) NULL ,
				`website` VARCHAR(512) NULL ,
				`featured_image` VARCHAR(128) NULL ,
				`gallery_images` VARCHAR(256),
				`term_id` INT NOT NULL ,
				`term_taxonomy_id` INT NOT NULL,
				`seating_chart` VARCHAR(256) NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			  ", 
			
			"CREATE TABLE IF NOT EXISTS `mvne_events_venues` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`event_id` INT NOT NULL ,
				`venue_id` INT NOT NULL ,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			  ",
		    
			"CREATE TABLE IF NOT EXISTS `mvne_presenters` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`display_name` varchar(100) NOT NULL,
				`description` text,
				`term_id` int(11) DEFAULT NULL,
				`term_taxonomy_id` int(11) DEFAULT NULL,
				`profile_id` int(11) NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) ;

			",
		    
			"CREATE TABLE IF NOT EXISTS `mvne_events_presenters` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`event_id` int(11) NOT NULL,
				`presenter_id` int(11) NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			",
		    
			"CREATE TABLE IF NOT EXISTS `mvne_categories` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`name` VARCHAR(255) NOT NULL ,
				`description` text,
				`term_id` INT NOT NULL ,
				`term_taxonomy_id` INT NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			  ", 
		    
			  "CREATE TABLE IF NOT EXISTS `mvne_events_categories` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`event_id` int(11) NOT NULL,
				`category_id` int(11) NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`) );
			",
			
		);
		
		
		
		//ALTER TABLE `mvne_events_attendees` ADD COLUMN `status_description` VARCHAR(250) NULL  , ADD COLUMN `registration_id` VARCHAR(250) NULL   , ADD COLUMN `primary_attendee_id` INT NULL    , ADD COLUMN `order_id` INT NULL    , ADD COLUMN `status_id` VARCHAR(45) NULL  ;
 
		// ALTER TABLE `msfc_live`.`mvne_events_attendees` ADD UNIQUE INDEX `attendee_event` (`attendee_id` ASC, `event_id` ASC) ;

		foreach ( $create AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}
		
	}
	
	public function uninstall(){
		
		global $wpdb;
		
		$settings = \MavenEvents\Settings\EventsRegistry::instance();
		$settings->reset();
		//To danger to remove the tables in the uninstall process
		$drop = array(
//			"DROP TABLE `mvne_events`;",
//			"DROP TABLE `mvne_events_prices`;",
//			"DROP TABLE `mvne_attendees`;",
//			"DROP TABLE `mvne_venues`;"
			);
		
		
		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}
	}
	
}
