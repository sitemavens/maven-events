<?php

namespace MavenEvents\Core\Domain;

class Event extends \Maven\Core\DomainObject {

	private $description;
	private $name;
	private $registrationStartDate;
	private $registrationEndDate;
	private $registrationStartTime;
	private $registrationEndTime;
	private $eventStartDate;
	private $eventEndDate;
	private $eventStartTime;
	private $eventEndTime;
	private $featuredImage;
	private $galleryImages = array();
	private $postsContent = array();
	private $allowGroupRegistration;
	private $maxGroupRegistrants;
	private $closed = false;
	private $attendeeLimit;
	private $summary;
	private $price;
	private $seatsEnabled = false;
	private $availableSeats ;
	
	/**
	 *
	 * This property it is not used to save a value, but to make toArray works, and fire getFeaturedImageUrl
	 */
	private $featuredImageUrl;
	private $venueId;
	private $url;

	/**
	 *
	 * @var \MavenEvents\Core\Domain\Attendee[] 
	 * @collectionType: \MavenEvents\Core\Domain\Attendee
	 */
	private $attendees = array();

	/**
	 *
	 * @var \MavenEvents\Core\Domain\EventPrices[] 
	 * @collectionType: \MavenEvents\Core\Domain\EventPrices
	 */
	private $prices = array();

	/**
	 *
	 * @var \MavenEvents\Core\Domain\Venue 
	 */
	private $venue = null;
	private $maillist;

	/**
	 * @collectionType: \MavenEvents\Core\Domain\Presenter
	 * @var \MavenEvents\Core\Domain\Presenter[] 
	 */
	private $presenters = array();

	/**
	 * @collectionType: \MavenEvents\Core\Domain\Category
	 * @var \MavenEvents\Core\Domain\Category[] 
	 */
	private $categories = array();

	/**
	 *
	 * @var \Maven\Core\Domain\Variation[] 
	 */
	private $variations;
	private $variationsEnabled = TRUE;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		// We need to initialice the instances
		$this->venue = new \MavenEvents\Core\Domain\Venue();

		$rules = array(
		    'description' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'registrationStartDate' => \Maven\Core\SanitizationRule::Date,
		    'registrationEndDate' => \Maven\Core\SanitizationRule::Date,
		    'registrationStartTime' => \Maven\Core\SanitizationRule::Time,
		    'registrationEndTime' => \Maven\Core\SanitizationRule::Time,
		    'eventStartDate' => \Maven\Core\SanitizationRule::Date,
		    'eventEndDate' => \Maven\Core\SanitizationRule::Date,
		    'eventStartTime' => \Maven\Core\SanitizationRule::Time,
		    'eventEndTime' => \Maven\Core\SanitizationRule::Time,
		    'featuredImage' => \Maven\Core\SanitizationRule::Integer,
		    'allowGroupRegistration' => \Maven\Core\SanitizationRule::Boolean,
		    'maxGroupRegistrants' => \Maven\Core\SanitizationRule::Integer,
		    'closed' => \Maven\Core\SanitizationRule::Boolean,
		    'attendeeLimit' => \Maven\Core\SanitizationRule::Integer,
		    'summary' => \Maven\Core\SanitizationRule::Text,
		    'price' => \Maven\Core\SanitizationRule::Float,
		    'variationsEnabled' => \Maven\Core\SanitizationRule::Boolean,
			'availableSeats' => \Maven\Core\SanitizationRule::Integer,
			'seatsEnabled' => \Maven\Core\SanitizationRule::Boolean
		);

		$this->setSanitizationRules( $rules );

		$this->variations = array();
	}

	public function getGalleryImages() {
		return $this->galleryImages;
	}

	public function getGalleryImagesForDB() {
		$ids = array();
		foreach ( $this->galleryImages as $image ) {
			$ids[] = $image[ 'id' ];
		}
		return implode( ',', $ids );
	}

	public function setGalleryImages( $galleryImages ) {
		if ( is_array( $galleryImages ) ) {
			$this->galleryImages = $galleryImages;
		} else {
			//not an array, should be the first load from database
			//TODO: We should add a validation here, if an attachment
			// has been deleted, or maybe the url changed, etc
			$ids = explode( ',', $galleryImages );
			foreach ( $ids as $id ) {
				$this->addGalleryImage( $id );
			}
			//$this->galleryImages = unserialize( $galleryImages );
		}
	}

	private function addGalleryImage( $id ) {
		//Get other properties of the image
		$attachment = get_post( $id );

		//If no attachment, the image maybe has been deleted or something, dont process
		if ( $attachment ) {
			$image = array();
			//set image id and url
			$image[ 'id' ] = $id;
			$image[ 'url' ] = wp_get_attachment_url( $id );



			$image[ 'caption' ] = $attachment->post_excerpt;
			$image[ 'alt' ] = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
			$image[ 'description' ] = $attachment->post_content;

			$this->galleryImages[] = $image;
		}
	}

	public function getPostsContent() {
		return $this->postsContent;
	}

	public function getPostsContentForDB() {
		$ids = array();
		foreach ( $this->postsContent as $post ) {
			$ids[] = $post[ 'id' ];
		}
		return implode( ',', $ids );
	}

	public function setPostsContent( $postsContent ) {
		if ( is_array( $postsContent ) ) {
			$this->postsContent = $postsContent;
		} else {
			//not an array, should be the first load from database
			//TODO: We should add a validation here, if an attachment
			// has been deleted, or maybe the url changed, etc
			$ids = explode( ',', $postsContent );
			foreach ( $ids as $id ) {
				$this->addPostContent( $id );
			}
			//$this->galleryImages = unserialize( $galleryImages );
		}
	}

	public function addPostContent( $id ) {
		//Get other properties of the post
		$post = get_post( $id );

		//If no post, maybe has been deleted or something, dont process
		if ( $post ) {
			$data = array();
			//set image id and url
			$data[ 'id' ] = $id;

			$data[ 'title' ] = $post->post_title;
			$data[ 'author' ] = $post->post_author;
			$data[ 'name' ] = $post->post_name;
			$data[ 'editLink' ] = get_edit_post_link( $id, '&' );
			$data[ 'parent' ] = $post->post_parent;

			//Added this to avoid getting duplicated objects
			if ( ! in_array( $data, $this->postsContent ) )
				$this->postsContent[] = $data;
		}
	}

	public function removePostContent( $id ) {
		$found = false;
		foreach ( $this->postsContent as $key => $value ) {
			if ( $value[ 'id' ] == $id ) {
				$found = true;
				break;
			}
		}
		if ( $found )
			unset( $this->postsContent[ $key ] );
	}

	/**
	 * Get event attendees
	 * @collectionType: \MavenEvents\Core\Domain\Attendee
	 * @return \MavenEvents\Core\Domain\Attendee[] 
	 */
	public function getAttendees() {
		return $this->attendees;
	}

	public function getNewAttendees() {
		$newAttendees = array();

		foreach ( $this->attendees as $attendee ) {
			if ( $attendee->isNew() )
				$newAttendees[] = $attendee;
		}

		return $newAttendees;
	}

	/**
	 * Get event prices
	 * @collectionType: \MavenEvents\Core\Domain\EventPrices
	 * @return \MavenEvents\Core\Domain\EventPrices[] 
	 */
	public function getPrices() {
		return $this->prices;
	}

	public function getFirstPrice() {
		if ( $this->hasPrices() )
			return $this->prices[ 0 ];

		return false;
	}

	public function isClosed() {
		return $this->closed;
	}

	public function setClosed( $closed ) {
		$this->closed = $closed;
	}

	public function getMaillist() {
		return $this->maillist;
	}

	public function setMaillist( $maillist ) {
		$this->maillist = $maillist;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Attendee[] 
	 */
	public function setAttendees( $attendees ) {
		$this->attendees = $attendees;
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\EventPrices[] 
	 */
	public function setPrices( $prices ) {
		$this->prices = $prices;
	}

	/**
	 * Get event attendees
	 * @collectionType: \MavenEvents\Core\Domain\Presenter
	 * @return \MavenEvents\Core\Domain\Presenter[] 
	 */
	public function getPresenters() {
		return $this->presenters;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\Presenter[] 
	 */
	public function setPresenters( $presenters ) {
		$this->presenters = $presenters;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\Attendee
	 */
	public function addPresenter( \MavenEvents\Core\Domain\Presenter $presenter ) {
		$this->presenters[] = $presenter;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\Attendee
	 */
	public function addAttendee( \MavenEvents\Core\Domain\Attendee $attendee ) {
		$this->attendees[] = $attendee;
	}

	/**
	 * 
	 * @return \MavenEvents\Core\Domain\Attendee
	 */
	public function newAttendee( $email ) {

		//Check if the attendee already exists
		foreach ( $this->attendees as $attendee )
			if ( $attendee->getEmail() === $email )
				return $attendee;

		$attendee = new \MavenEvents\Core\Domain\Attendee();
		$attendee->setEmail( $email );
		$this->attendees[] = $attendee;

		return $attendee;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\EventPrice
	 */
	public function addPrice( \MavenEvents\Core\Domain\EventPrices $eventPrice ) {
		$this->prices[] = $eventPrice;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}

	public function getVenueId() {
		return $this->venueId;
	}

	public function setVenueId( $venueId ) {
		$this->venueId = $venueId;
		$this->getVenue()->setId( $venueId );
	}

	public function getVenue() {
		return $this->venue;
	}

	public function setVenue( \MavenEvents\Core\Domain\Venue $venue ) {
		$this->venue = $venue;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getRegistrationStartDate() {
		return $this->registrationStartDate;
	}

	public function setRegistrationStartDate( $registrationStartDate ) {
		$this->registrationStartDate = $registrationStartDate;
	}

	public function getRegistrationEndDate() {
		return $this->registrationEndDate;
	}

	public function setRegistrationEndDate( $registrationEndDate ) {
		$this->registrationEndDate = $registrationEndDate;
	}

	public function getRegistrationStartTime() {
		return $this->registrationStartTime;
	}

	public function setRegistrationStartTime( $registrationStartTime ) {
		$this->registrationStartTime = $registrationStartTime;
	}

	public function getRegistrationEndTime() {
		return $this->registrationEndTime;
	}

	public function setRegistrationEndTime( $registrationEndTime ) {
		$this->registrationEndTime = $registrationEndTime;
	}

	public function getEventStartDate() {
		return $this->eventStartDate;
	}

	public function setEventStartDate( $eventStartDate ) {
		$this->eventStartDate = $eventStartDate;
	}

	public function getEventEndDate() {
		return $this->eventEndDate;
	}

	public function setEventEndDate( $eventEndDate ) {
		$this->eventEndDate = $eventEndDate;
	}

	public function getEventStartTime() {
		return $this->eventStartTime;
	}

	public function setEventStartTime( $eventStartTime ) {
		$this->eventStartTime = $eventStartTime;
	}

	public function getEventEndTime() {
		return $this->eventEndTime;
	}

	public function setEventEndTime( $eventEndTime ) {
		$this->eventEndTime = $eventEndTime;
	}

	public function getFeaturedImage() {
		return $this->featuredImage;
	}

	public function setFeaturedImage( $featuredImage ) {
		$this->featuredImage = $featuredImage;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function hasVenue() {

		$venueId = ( int ) $this->getVenue()->getId();

		if ( $this->getVenue() && $venueId > 0 )
			return true;

		return false;
	}

	public function hasAttendees() {
		if ( $this->attendees && count( $this->attendees ) > 0 )
			return true;

		return false;
	}

	public function hasPrices() {
		if ( $this->prices && count( $this->prices ) > 0 )
			return true;

		return false;
	}

	public function hasPresenters() {
		if ( $this->presenters && count( $this->presenters ) > 0 )
			return true;

		return false;
	}

	public function getUrl() {
		return $this->url;
	}

	/**
	 * Just an alias for getUrl
	 * @return string
	 */
	public function getSlug() {
		return $this->getUrl();
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}

	public function getFullUrl() {

		return get_permalink( $this->getId() );
	}

	public function getFeaturedImageUrl() {

		if ( $this->featuredImage )
			return wp_get_attachment_url( $this->featuredImage );

		return "";
	}

	public function isAllowGroupRegistration() {
		return $this->allowGroupRegistration;
	}

	public function setAllowGroupRegistration( $allowGroupRegistration ) {
		$this->allowGroupRegistration = $allowGroupRegistration;
	}

	public function getMaxGroupRegistrants() {
		return $this->maxGroupRegistrants;
	}

	public function setMaxGroupRegistrants( $maxGroupRegistrants ) {
		$this->maxGroupRegistrants = $maxGroupRegistrants;
	}

	public function getAttendeeLimit() {
		return $this->attendeeLimit;
	}

	public function setAttendeeLimit( $attendeeLimit ) {
		$this->attendeeLimit = $attendeeLimit;
	}

	public function getSummary() {
		return $this->summary;
	}

	public function setSummary( $summary ) {
		$this->summary = $summary;
	}

	/**
	 * Get event categories
	 * @collectionType: \MavenEvents\Core\Domain\Category
	 * @return \MavenEvents\Core\Domain\Category[] 
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\Category[] 
	 */
	public function setCategories( $categories ) {
		$this->categories = $categories;
	}

	/**
	 * 
	 * @param type \MavenEvents\Core\Domain\Category
	 */
	public function addCategory( \MavenEvents\Core\Domain\Category $category ) {
		$this->categories[] = $category;
	}

	public function hasCategories() {
		if ( $this->categories && count( $this->categories ) > 0 )
			return true;

		return false;
	}

	public function sanitize() {

		parent::sanitize();

		foreach ( $this->attendees as $attendee )
			$attendee->sanitize();

		foreach ( $this->prices as $price )
			$price->sanitize();

		foreach ( $this->categories as $category ) {
			$category->sanitize();
		}

		foreach ( $this->presenters as $presenter ) {
			$presenter->sanitize();
		}
	}

	/**
	 * @collectionType: \Maven\Core\Domain\Variation
	 * @return \Maven\Core\Domain\Variation[]
	 */
	public function getVariations() {

		// We check if it is enabled first
		if ( ! $this->isVariationsEnabled() ) {
			return array();
		}

		return $this->variations;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Variation[] $variations
	 */
	public function setVariations( $variations ) {
		$this->variations = $variations;
	}

	public function hasVariations() {
		return $this->variations && count( $this->variations ) > 0;
	}

	public function isVariationsEnabled() {
		return $this->variationsEnabled;
	}

	public function setVariationsEnabled( $variationsEnabled ) {
		$this->variationsEnabled = $variationsEnabled;
	}
	
	public function isSeatsEnabled () {
		return $this->seatsEnabled;
	}

	public function setSeatsEnabled ( $seatsEnabled ) {
		$this->seatsEnabled = $seatsEnabled;
	}

	public function getAvailableSeats () {
		return $this->availableSeats;
	}

	public function setAvailableSeats ( $availableSeats ) {
		$this->availableSeats = $availableSeats;
	}



}
