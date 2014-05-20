<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class EventsContent {
     
	const eventColumnName = 'event';

	public static function init() {

		add_filter( 'manage_' . EventsConfig::eventContentTypeName . '_posts_columns', array( __CLASS__, 'eventColumnsHead' ) );
		add_action( 'manage_' . EventsConfig::eventContentTypeName . '_posts_custom_column', array( __CLASS__, 'eventColumnContent' ), 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'saveEventData' ) );

		if ( is_admin() )
			add_action( 'admin_menu', array( __CLASS__, 'addMetaBox' ) );
	}

	//add new column
	function eventColumnsHead( $defaults ) {
		$defaults[ EventsContent::eventColumnName ] = 'Event';
		return $defaults;
	}

	//show the event
	function eventColumnContent( $column_name, $post_ID ) {
		if ( $column_name == EventsContent::eventColumnName ) {
			$title = '';
			$related_post_id = get_post_meta( $post_ID, EventsContent::eventColumnName, TRUE );

			if ( $related_post_id )
				$title = get_post_field( 'post_title', $related_post_id );
			
			$url = get_admin_url()."admin.php?page=me-event#event/edit/{$related_post_id}";
			echo "<a href='{$url}'>{$title}</a>";
		}
	}

	function addMetaBox() {
		add_meta_box( 'event', __( 'Maven Events Options' ), array( __CLASS__, 'eventMetabox' ), EventsConfig::eventContentTypeName, 'normal', 'low' );
	}

	function eventMetabox( $post ) {
		$args = array( );
		$selected = get_post_meta( $post->ID, EventsContent::eventColumnName, TRUE );
		if ( $selected )
			$args[ 'selected' ] = $selected;
		else if ( \Maven\Core\Request::current()->exists( 'eid' ))
			$args[ 'selected' ] = \Maven\Core\Request::current()->getProperty( 'eid' );

		wp_nonce_field( EventsContent::eventColumnName . $post->ID, 'event_noncename' );
		echo '<input type="hidden" name="eventOldValue" value="'.$selected.'" />';
		$args[ 'post_type' ] = EventsConfig::eventTypeName;
		$args[ 'name' ] = 'eventDropDown';
		$args[ 'show_option_none' ] = '(no event)';
		$args[ 'option_none_value' ] = '';
		 
		?><p><strong>Event</strong></p><?php
		wp_dropdown_pages( $args );
	}

	function saveEventData( $post_id ) {
		
		$request = \Maven\Core\Request::current();
		
		// verify this came from the our screen and with proper authorization.
		if ( ! $request->exists( 'event_noncename' ) || ! wp_verify_nonce( $request->getProperty( 'event_noncename' ), EventsContent::eventColumnName . $post_id ) ) {
			return $post_id;
		}

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		// OK, we're authenticated: we need to find and save the data   
		$post = get_post( $post_id );
		if ( $post->post_type == EventsConfig::eventContentTypeName ) {
			$eventManager = new EventManager();
			$event_id = esc_attr( $request->getProperty( 'eventDropDown' ) );
			if ( $event_id ) {
				$event = $eventManager->get( $event_id );
				if ( $event ) {
					//add the content to the event
					$event->addPostContent( $post_id );
					//save
					$eventManager->addEvent( $event );
				}
			}
			//remove the content from the old event(if any)
			$old_event_id=esc_attr( $request->getProperty( 'eventOldValue' ) );
			if($old_event_id){
				$event = $eventManager->get( $old_event_id );
				if ( $event ) {
					//add the content to the event
					$event->removePostContent( $post_id );
					//save
					$eventManager->addEvent( $event );
				}
			}
			
			
			update_post_meta( $post_id, EventsContent::eventColumnName, esc_attr( $request->getProperty(  'eventDropDown' ) ) );
			return(esc_attr( $_POST[ 'eventDropDown' ] ));
		}
		return $post_id;
	}

}

EventsContent::init();


