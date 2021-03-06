<?php

namespace MavenEvents\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class EventsConfig {

	const eventTypeName = 'mvn_event';
	const eventCategoryName = 'mvne_category';
	const venueTypeName = 'mvne_venue';
	const venueCategoryName = 'mvne_venue_category';
	const presenterTypeName = 'mvne_presenter';
	const presenterCategoryName = 'mvne_presenter_category';
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

	public static function init () {

		\Maven\Core\HookManager::instance()->addInit( array( __CLASS__, 'registerTypes' ) );
        \Maven\Core\HookManager::instance()->addAction( 'maven/cart/itemPaid/mavenevents', array( __CLASS__, 'paidEvent' ), 10, 1 );
        \Maven\Core\HookManager::instance()->addAction( 'maven/cart/checkStock', array( __CLASS__, 'checkEventStock' ), 10, 1 );
	}

	static function registerTypes () {
		// Add events
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
			'search_items' => __( 'Search events', 'text_domain' ),
			'not_found' => __( 'No events found', 'text_domain' ),
			'not_found_in_trash' => __( 'No events found in Trash', 'text_domain' ),
		);

		$registry = \MavenEvents\Settings\EventsRegistry::instance();
		$prefix = $registry->getEventsSlugPrefix();

		$slug = apply_filters( 'core/config/eventSlug', $registry->getEventsSlug() );

		if ( $prefix ) {
			$slug = "{$prefix}/{$slug}";
		}

		$slug = \Maven\Core\HookManager::instance()->applyFilters( 'maven-events/config/slug', $slug );

		$args = array(
			'label' => __( 'mvn_event', 'text_domain' ),
			'description' => __( 'Events', 'text_domain' ),
			'labels' => $labels,
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			//'taxonomies' => array( 'mvn_venue' ),
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 5,
			'menu_icon' => $registry->getImagesUrl() . "icon.png",
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => $slug, 'with_front' => false )
		);

		register_post_type( EventsConfig::eventTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Event Categories' ),
			'popular_items' => __( 'Popular Event Categories' ),
			'all_items' => __( 'All Event Categories' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Category' ),
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'separate_items_with_commas' => __( 'Separate event categories with commas' ),
			'add_or_remove_items' => __( 'Add or remove event categories' ),
			'choose_from_most_used' => __( 'Choose from the most used event categories' ),
			'not_found' => __( 'No category found.' ),
			'menu_name' => __( 'Categories' )
		);

		$categorySlug = apply_filters( 'core/config/eventCategorySlug', 'event-category' );
		
		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => $categorySlug )
		);

		register_taxonomy( EventsConfig::eventCategoryName, EventsConfig::eventTypeName, $args );



		// Add venues.
		$labels = array(
			'name' => _x( 'Venues', 'Post Type General Name', 'text_domain' ),
			'singular_name' => _x( 'Venue', 'Post Type Singular Name', 'text_domain' ),
			'menu_name' => __( 'Venues', 'text_domain' ),
			'parent_item_colon' => __( 'Parent Venue:', 'text_domain' ),
			'all_items' => __( 'All Venues', 'text_domain' ),
			'view_item' => __( 'View Venue', 'text_domain' ),
			'add_new_item' => __( 'Add New Venue', 'text_domain' ),
			'add_new' => __( 'New Venue', 'text_domain' ),
			'edit_item' => __( 'Edit Venue', 'text_domain' ),
			'update_item' => __( 'Update Venue', 'text_domain' ),
			'search_items' => __( 'Search Venues', 'text_domain' ),
			'not_found' => __( 'No venues found', 'text_domain' ),
			'not_found_in_trash' => __( 'No venues found in Trash', 'text_domain' ),
		);

		$venueSlug = apply_filters( 'core/config/venueSlug', 'venue' );
		$args = array(
		    'label' => __( EventsConfig::venueTypeName, 'text_domain' ),
		    'description' => __( 'Venues', 'text_domain' ),
		    'labels' => $labels,
		    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		    'hierarchical' => true,
		    'public' => true,
		    'show_ui' => true,
		    'show_in_menu' => true,
		    'show_in_nav_menus' => true,
		    'show_in_admin_bar' => true,
		    'menu_position' => 5,
		    'menu_icon' => $registry->getImagesUrl() . "icon-venue.png",
		    'can_export' => true,
		    'has_archive' => true,
		    'exclude_from_search' => false,
		    'publicly_queryable' => true,
		    'capability_type' => 'post',
		    'rewrite' => array( 'slug' => $venueSlug, 'with_front' => false )
		);

		register_post_type( EventsConfig::venueTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Venue Categories' ),
			'popular_items' => __( 'Popular Venue Categories' ),
			'all_items' => __( 'All Venue Categories' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Category' ),
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'separate_items_with_commas' => __( 'Separate venue categories with commas' ),
			'add_or_remove_items' => __( 'Add or remove venue categories' ),
			'choose_from_most_used' => __( 'Choose from the most used venue categories' ),
			'not_found' => __( 'No category found.' ),
			'menu_name' => __( 'Categories' )
		);

		$venueCategorySlug = apply_filters( 'core/config/venueCategorySlug', 'venue-category' );
		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => $venueCategorySlug )
		);

		register_taxonomy( EventsConfig::venueCategoryName, EventsConfig::venueTypeName, $args );


		// Add presenters.
		$labels = array(
			'name' => _x( 'Presenters', 'Post Type General Name', 'text_domain' ),
			'singular_name' => _x( 'Presenter', 'Post Type Singular Name', 'text_domain' ),
			'menu_name' => __( 'Presenters', 'text_domain' ),
			'parent_item_colon' => __( 'Parent Presenter:', 'text_domain' ),
			'all_items' => __( 'All Presenters', 'text_domain' ),
			'view_item' => __( 'View Presenter', 'text_domain' ),
			'add_new_item' => __( 'Add New Presenter', 'text_domain' ),
			'add_new' => __( 'New Presenter', 'text_domain' ),
			'edit_item' => __( 'Edit Presenter', 'text_domain' ),
			'update_item' => __( 'Update Presenter', 'text_domain' ),
			'search_items' => __( 'Search Presenters', 'text_domain' ),
			'not_found' => __( 'Nop presenters found', 'text_domain' ),
			'not_found_in_trash' => __( 'No presenters found in Trash', 'text_domain' ),
		);

		$presenterSlug = apply_filters( 'core/config/presenterSlug', 'presenter' );
		
		$args = array(
		    'label' => __( EventsConfig::presenterTypeName, 'text_domain' ),
		    'description' => __( 'Presenters', 'text_domain' ),
		    'labels' => $labels,
		    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		    'hierarchical' => true,
		    'public' => true,
		    'show_ui' => true,
		    'show_in_menu' => true,
		    'show_in_nav_menus' => true,
		    'show_in_admin_bar' => true,
		    'menu_position' => 5,
		    'menu_icon' => $registry->getImagesUrl()."icon-presenter.png",
		    'can_export' => true,
		    'has_archive' => true,
		    'exclude_from_search' => false,
		    'publicly_queryable' => true,
		    'capability_type' => 'post',
		    'rewrite' => array( 'slug' => $presenterSlug, 'with_front' => false )
		);

		register_post_type( EventsConfig::presenterTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Presenter Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Presenter Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Presenter Categories' ),
			'popular_items' => __( 'Popular Presenter Categories' ),
			'all_items' => __( 'All Presenter Categories' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Presenter Category' ),
			'update_item' => __( 'Update Presenter Category' ),
			'add_new_item' => __( 'Add New Presenter Category' ),
			'new_item_name' => __( 'New Presenter Category Name' ),
			'separate_items_with_commas' => __( 'Separate presenter categories with commas' ),
			'add_or_remove_items' => __( 'Add or remove presenter categories' ),
			'choose_from_most_used' => __( 'Choose from the most used presenter categories' ),
			'not_found' => __( 'No presenter category found.' ),
			'menu_name' => __( 'Presenter Categories' )
		);

		$presenterCategorySlug = apply_filters( 'core/config/presenterCategorySlug', 'presenter-category' );
		$args = array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		    'rewrite' => array( 'slug' => $presenterCategorySlug )
		);

		register_taxonomy( EventsConfig::presenterCategoryName, EventsConfig::presenterTypeName, $args );
	}
    
    public static function paidEvent ( $orderItem ) {
        
        $eventManager = new \MavenEvents\Core\EventManager();
        $variationGroupManager = new \MavenEvents\Core\VariationGroupManager();
		$event = $eventManager->get( $orderItem->getThingId());
		$variationGroup = $variationGroupManager->get($orderItem->getThingVariationId());
        if ($event->isSeatsEnabled() && $event->getAvailableSeats() >= $orderItem->getQuantity()) {
        	if ($variationGroup->getQuantity() >= $orderItem->getQuantity()) {
        		$variationGroup->setQuantity($variationGroup->getQuantity() - $orderItem->getQuantity());
        		$variationGroupManager->save($variationGroup);
        		$event->setAvailableSeats($event->getAvailableSeats() - $orderItem->getQuantity());
        		$eventManager->addEvent($event);
        	}
        }
	}
    
    public static function checkEventStock ( $orderItems ) {
        $eventManager = new \MavenEvents\Core\EventManager();
        $variationGroupManager = new \MavenEvents\Core\VariationGroupManager();
        $hasStock = true;
        foreach($orderItems as $orderItem){
        	if ($orderItem->getPluginKey() === 'mavenevents') {
	            if ($hasStock) {
	                $event = $eventManager->get( $orderItem->getThingId());
	                $variationGroup = $variationGroupManager->get($orderItem->getThingVariationId());
	                if ($event->isSeatsEnabled() && $event->getAvailableSeats() == 0) {
	                    $hasStock = false;
	                } else if ($variationGroup->getQuantity() < $orderItem->getQuantity()) {
	                	$hasStock = false;
	                }
	            }
            }
        }
        return $hasStock;
	}

}

EventsConfig::init();


