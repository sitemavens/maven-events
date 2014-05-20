<?php

namespace MavenEvents\Core\Domain;

class Venue extends \Maven\Core\DomainObject {

	private $name;
	private $address;
	private $address2;
	private $city;
	private $state;
	private $zip;
	private $country;
	private $description;
	private $contact;
	private $phone;
	private $twitter;
	private $website;
	private $featuredImage;
	private $galleryImages = array( );
	private $termId;
	private $termTaxonomyId;
	private $slug;
	private $seatingChart;
	
	/**
	 *
	 * This property it is not used to save a value, but to make toArray works, and fire getFeaturedImageUrl
	 */
	private $seatingChartUrl;
	
	/**
	 *
	 * This property it is not used to save a value, but to make toArray works, and fire getFeaturedImageUrl
	 */
	private $featuredImageUrl;
	
	
	public function __construct( $id = false ) {
		parent::__construct( $id );
		
		$rules = array(
			
			'name'			=> \Maven\Core\SanitizationRule::Text,
			'address'		=> \Maven\Core\SanitizationRule::Text,
			'address2'		=> \Maven\Core\SanitizationRule::Text,
			'city'			=> \Maven\Core\SanitizationRule::Text,
			'state'			=> \Maven\Core\SanitizationRule::Text,
			'zip'			=> \Maven\Core\SanitizationRule::Text,
			'country'		=> \Maven\Core\SanitizationRule::Text,
			'description'	=> \Maven\Core\SanitizationRule::Text,
			'contact'		=> \Maven\Core\SanitizationRule::Text,
			'phone'			=> \Maven\Core\SanitizationRule::Text,
			'twitter'		=> \Maven\Core\SanitizationRule::URL,
			'website'		=> \Maven\Core\SanitizationRule::URL,
			'featuredImage'	=> \Maven\Core\SanitizationRule::Integer,
			'termId'		=> \Maven\Core\SanitizationRule::Integer,
			'termTaxonomyId'=> \Maven\Core\SanitizationRule::Integer,
			'slug'			=> \Maven\Core\SanitizationRule::Slug,
			'seatingChart'	=> \Maven\Core\SanitizationRule::Integer
			
		);
		
		$this->setSanitizationRules( $rules );
		
	}
	
	public function getGalleryImages() {
		return $this->galleryImages;
	}

	public function getGalleryImagesForDB() {
		$ids = array( );
		foreach ( $this->galleryImages as $image ) {
			$ids[ ] = $image[ 'id' ];
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
			$image = array( );
			//set image id and url
			$image[ 'id' ] = $id;
			$image[ 'url' ] = wp_get_attachment_url( $id );



			$image[ 'caption' ] = $attachment->post_excerpt;
			$image[ 'alt' ] = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
			$image[ 'description' ] = $attachment->post_content;

			$this->galleryImages[ ] = $image;
		}
	}
	
	public function getSeatingChart() {
		return $this->seatingChart;
	}

	public function setSeatingChart( $seatingChart ) {
		$this->seatingChart = $seatingChart;
	}
	
	
	public function getFeaturedImage() {
		return $this->featuredImage;
	}

	public function setFeaturedImage( $featureImage ) {
		$this->featuredImage = $featureImage;
	}

	public function getAddress2() {
		return $this->address2;
	}

	public function setAddress2( $address2 ) {
		$this->address2 = $address2;
	}

		public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress( $address ) {
		$this->address = $address;
	}

	public function getCity() {
		return $this->city;
	}

	public function setCity( $city ) {
		$this->city = $city;
	}

	public function getState() {
		return $this->state;
	}

	public function setState( $state ) {
		$this->state = $state;
	}

	public function getZip() {
		return $this->zip;
	}

	public function setZip( $zip ) {
		$this->zip = $zip;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setCountry( $country ) {
		$this->country = $country;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getContact() {
		return $this->contact;
	}

	public function setContact( $contact ) {
		$this->contact = $contact;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone( $phone ) {
		$this->phone = $phone;
	}

	public function getTwitter() {
		return $this->twitter;
	}

	public function setTwitter( $twitter ) {
		$this->twitter = $twitter;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function setWebsite( $website ) {
		$this->website = $website;
	}
 
	public function getTermId() {
		return $this->termId;
	}

	public function setTermId( $termId ) {
		$this->termId = $termId;
	}

	public function getTermTaxonomyId() {
		return $this->termTaxonomyId;
	}

	public function setTermTaxonomyId( $termTaxonomyId ) {
		$this->termTaxonomyId = $termTaxonomyId;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug( $slug ) {
		$this->slug = $slug;
	}

	
	public function getSeatingChartUrl() {
		 
		if ( $this->seatingChart )
			return wp_get_attachment_url ( $this->seatingChart );
		
		return "";
	}
	
	public function getFeaturedImageUrl() {
		 
		if ( $this->featuredImage )
			return wp_get_attachment_url ( $this->featuredImage );
		
		return "";
	}
}
