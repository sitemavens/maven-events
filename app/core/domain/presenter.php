<?php

namespace MavenEvents\Core\Domain;

class Presenter extends \Maven\Core\Domain\Profile {

	private $displayName;
	private $termId;
	private $termTaxonomyId;
	private $slug;
	
	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$rules = array(
			
			'displayName'		=> \Maven\Core\SanitizationRule::Text,
			'termId'			=> \Maven\Core\SanitizationRule::Integer,
			'termTaxonomyId'	=> \Maven\Core\SanitizationRule::Integer,
			'slug'				=> \Maven\Core\SanitizationRule::Slug
			
		);
		
		$this->setSanitizationRules( $rules );
		
	}
	
	public function getDisplayName() {
		return $this->displayName;
	}

	public function setDisplayName( $displayName ) {
		$this->displayName = $displayName;
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
	
	

}
