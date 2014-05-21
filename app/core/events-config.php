<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class EventsConfig {

	const eventTypeName = 'mvn_event';
	const venueTypeName = 'mvne_venue';
	const categoryTypeName = 'mvne_category';
	const presenterTypeName = 'mvne_presenter';
	
	const eventContentTypeName='mvn_event_content';
	const categoryContentTypeName = 'mvne_category_content';
	
	const eventTableName = 'mvne_events';
	const eventPricesTableName = 'mvne_events_prices';
	const presentersTableName = 'mvne_presenters';
	const eventsPresentersTableName = 'mvne_events_presenters';
	const venuesTableName = 'mvne_venues';
	const eventsVenuesTableName = 'mvne_events_venues';
	const attendeesTableName = 'mvne_attendees';
	const eventsAttendeesTableName = 'mvne_events_attendees';
	const categoriesTableName = 'mvne_categories';
	const eventsCategoriesTableName = 'mvne_events_categories';

	public static function init() {

		\Maven\Core\HookManager::instance()->addInit( array( __CLASS__, 'registerTypes' ) );
	}

	static function registerTypes() {

		$labels = array(
		    'name' => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		    'singular_name' => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		    'menu_name' => __( 'Event', 'text_domain' ),
		    'parent_item_colon' => __( 'Parent Event:', 'text_domain' ),
		    'all_items' => __( 'All Events', 'text_domain' ),
		    'view_item' => __( 'View Event', 'text_domain' ),
		    'add_new_item' => __( 'Add New Event', 'text_domain' ),
		    'add_new' => __( 'New Event', 'text_domain' ),
		    'edit_item' => __( 'Edit Event', 'text_domain' ),
		    'update_item' => __( 'Update Event', 'text_domain' ),
		    'search_items' => __( 'Search products', 'text_domain' ),
		    'not_found' => __( 'No products found', 'text_domain' ),
		    'not_found_in_trash' => __( 'No products found in Trash', 'text_domain' ),
		);

		$registry = \MavenEvents\Settings\EventsRegistry::instance();
		$prefix = $registry->getEventsSlugPrefix();

		$slug = $registry->getEventsSlug();

//		if ( $prefix )
//			$slug = "{$prefix}/{$slug}";

		$args = array(
		    'label' => __( 'mvn_event', 'text_domain' ),
		    'description' => __( 'Maven events', 'text_domain' ),
		    'labels' => $labels,
		    'supports' => array( ),
		    //'taxonomies' => array( 'mvn_venue' ),
		    'hierarchical' => true,
		    'public' => true,
		    'show_ui' => true,
		    'show_in_menu' => true,
		    'show_in_nav_menus' => true,
		    'show_in_admin_bar' => true,
		    'menu_position' => 5,
		    'menu_icon' => $registry->getImagesUrl()."icon.png",
		    'can_export' => true,
		    'has_archive' => true,
		    'exclude_from_search' => false,
		    'publicly_queryable' => true,
		    'capability_type' => 'post',
		    'rewrite' => array( 'slug' => $slug, 'with_front' => false )
		);

		register_post_type( EventsConfig::eventTypeName, $args );

		// Add venue taxonomy. It's not hierarchical
		$labels = array(
		    'name' => _x( 'Venue', 'taxonomy general name' ),
		    'singular_name' => _x( 'Venue', 'taxonomy singular name' ),
		    'search_items' => __( 'Search Venues' ),
		    'popular_items' => __( 'Popular Venues' ),
		    'all_items' => __( 'All Venues' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Venue' ),
		    'update_item' => __( 'Update Venue' ),
		    'add_new_item' => __( 'Add New Venue' ),
		    'new_item_name' => __( 'New Venue Name' ),
		    'separate_items_with_commas' => __( 'Separate writers with commas' ),
		    'add_or_remove_items' => __( 'Add or remove writers' ),
		    'choose_from_most_used' => __( 'Choose from the most used writers' ),
		    'not_found' => __( 'No writers found.' ),
		    'menu_name' => __( 'Venues' )
		);

		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => 'venue' )
		);

		register_taxonomy( EventsConfig::venueTypeName, EventsConfig::eventTypeName, $args );

		// Add presenter taxonomy. It's not hierarchical
		$labels = array(
		    'name' => _x( 'Presenter', 'taxonomy general name' ),
		    'singular_name' => _x( 'Presenter', 'taxonomy singular name' ),
		    'search_items' => __( 'Search Presenters' ),
		    'popular_items' => __( 'Popular Presenters' ),
		    'all_items' => __( 'All Presenters' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Presenter' ),
		    'update_item' => __( 'Update Presenter' ),
		    'add_new_item' => __( 'Add New Presenter' ),
		    'new_item_name' => __( 'New Presenter Name' ),
		    'separate_items_with_commas' => __( 'Separate presenters with commas' ),
		    'add_or_remove_items' => __( 'Add or remove presenters' ),
		    'choose_from_most_used' => __( 'Choose from the most used presenters' ),
		    'not_found' => __( 'No presenter found.' ),
		    'menu_name' => __( 'Presenter' )
		);

		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => 'presenter' )
		);

		register_taxonomy( EventsConfig::presenterTypeName, EventsConfig::eventTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
		    'name' => _x( 'Category', 'taxonomy general name' ),
		    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
		    'search_items' => __( 'Search Categories' ),
		    'popular_items' => __( 'Popular Categories' ),
		    'all_items' => __( 'All Categories' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Category' ),
		    'update_item' => __( 'Update Category' ),
		    'add_new_item' => __( 'Add New Category' ),
		    'new_item_name' => __( 'New Category Name' ),
		    'separate_items_with_commas' => __( 'Separate categories with commas' ),
		    'add_or_remove_items' => __( 'Add or remove categories' ),
		    'choose_from_most_used' => __( 'Choose from the most used categories' ),
		    'not_found' => __( 'No category found.' ),
		    'menu_name' => __( 'Categories' )
		);

		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => 'category' )
		);

		register_taxonomy( EventsConfig::categoryTypeName, EventsConfig::eventTypeName, $args );
	
		//Register Event Content
		$labels = array(
		    'name' => _x( 'Events Content', 'Post Type General Name', 'text_domain' ),
		    'singular_name' => _x( 'Event Content', 'Post Type Singular Name', 'text_domain' ),
		    'menu_name' => __( 'Events Content', 'text_domain' ),
		    'parent_item_colon' => __( 'Parent Event:', 'text_domain' ),
		    'all_items' => __( 'All Events Content', 'text_domain' ),
		    'view_item' => __( 'View Event Content', 'text_domain' ),
		    'add_new_item' => __( 'Add New Event Content', 'text_domain' ),
		    'add_new' => __( 'New Event Content', 'text_domain' ),
		    'edit_item' => __( 'Edit Event Content', 'text_domain' ),
		    'update_item' => __( 'Update Event Content', 'text_domain' ),
		    'search_items' => __( 'Search event content', 'text_domain' ),
		    'not_found' => __( 'No events content found', 'text_domain' ),
		    'not_found_in_trash' => __( 'No events content found in Trash', 'text_domain' ),
		);

		$args = array(
		    'label' => __( 'mvn_event', 'text_domain' ),
		    'description' => __( 'Maven events', 'text_domain' ),
		    'labels' => $labels,
		    'supports' => array('title', 'editor' ,'page-attributes', EventsContent::eventColumnName),
		    //'taxonomies' => array( 'mvn_venue' ),
		    'hierarchical' => true,
		    'public' => true,
		    'show_ui' => true,
		    'show_in_menu' => true,
		    'show_in_nav_menus' => true,
		    'show_in_admin_bar' => true,
		    'menu_position' => 5,
		    'menu_icon' => $registry->getImagesUrl()."icon.png",
		    'can_export' => true,
		    'has_archive' => true,
		    'exclude_from_search' => false,
		    'publicly_queryable' => true,
		    'capability_type' => 'post',
		    'rewrite' => array( 'slug' => EventsConfig::eventContentTypeName, 'with_front' => false )
		);

		register_post_type( EventsConfig::eventContentTypeName, $args );
		
		// Add category taxonomy for Event Content. It's not hierarchical
		$labels = array(
		    'name' => _x( 'Category', 'taxonomy general name' ),
		    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
		    'search_items' => __( 'Search Categories' ),
		    'popular_items' => __( 'Popular Categories' ),
		    'all_items' => __( 'All Categories' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Category' ),
		    'update_item' => __( 'Update Category' ),
		    'add_new_item' => __( 'Add New Category' ),
		    'new_item_name' => __( 'New Category Name' ),
		    'separate_items_with_commas' => __( 'Separate categories with commas' ),
		    'add_or_remove_items' => __( 'Add or remove categories' ),
		    'choose_from_most_used' => __( 'Choose from the most used categories' ),
		    'not_found' => __( 'No category found.' ),
		    'menu_name' => __( 'Categories' )
		);

		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => 'category' )
		);

		register_taxonomy( EventsConfig::categoryContentTypeName, EventsConfig::eventContentTypeName, $args );
	
	}

}

EventsConfig::init();


