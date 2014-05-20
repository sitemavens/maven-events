<?php

namespace MavenEvents\Core\Mappers;
use \MavenEvents\Core\EventsConfig,
		MavenEvents\Core\Domain\EventFilterType;

class WpSearchMapper extends \Maven\Core\Db\WordpressMapper {

	/**
	 *
	 * @var \MavenEvents\Core\Domain\EventFilter 
	 */
	private $filter;
	
	public function __construct() {

		parent::__construct( \MavenEvents\Core\EventsConfig::eventTableName );
		add_filter( 'mvn_events_search_join', array( &$this, 'joinVenues' ) );
		add_filter( 'mvn_events_search_where', array( &$this, 'whereVenues' ) );
	}
	
	/**
	 * 
	 * @param \MavenEvents\Core\Domain\EventFilter $filter
	 * @return posts
	 */
	public function getEvents( \MavenEvents\Core\Domain\EventFilter $filter ){
		
		// We save the filter so we can use it later
		$this->filter = $filter;
		
		
		$args = array( 'post_type' => EventsConfig::eventTypeName, 'suppress_filters' => false );
			
		if( $filter->getVenueId() ){
			$args['tax_query'][] = array(
											'taxonomy' => EventsConfig::venueTypeName,
											'field' => 'id',
											'terms' => $filter->getVenueId()
										);
		}
		
		// If there is a text to search, use it as s (search) wordpress value
		if( $filter->getText() ){
			$args['s'] = $filter->getText();
		}


		add_filter('posts_where', array( $this, 'where' ) );
		add_filter('posts_join', array( $this, 'join' ) );
		$wpEvents = get_posts( $args );
		
		return $wpEvents;
		
	}
	
	
	public  function where ( $wpWhere ) {
		// Initialize where variable so it always contains a value
		$where = '';
		$today = new \Maven\Core\MavenDateTime();
		$to = $today->mySqlFormatDate();

		switch ( $this->filter->getType() ) {
			case EventFilterType::last7days:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P7D' );
				$from = $fromDate->mySqlFormatDate();
				$where .= " AND {$this->tableName}.event_start_date >= '{$from}' and {$this->tableName}.event_start_date <= '{$to}'";
				break;
			
			case EventFilterType::last14days:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P14D' );
				$from = $fromDate->mySqlFormatDate();
				$where .= " AND {$this->tableName}.event_start_date >= '{$from}' and {$this->tableName}.event_start_date <= '{$to}'";
				break;
			
			case EventFilterType::lastMonth:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1M' );
				$from = $fromDate->mySqlFormatDate();
				$where .= " AND {$this->tableName}.event_start_date >= '{$from}' and {$this->tableName}.event_start_date <= '{$to}'";
				break;
			
			case EventFilterType::lastweek:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1W' );
				$from = $fromDate->mySqlFormatDate();

				$where .= "AND {$this->tableName}.event_start_date >= '{$from}' and {$this->tableName}.event_start_date <= '{$to}'";
				break;
			
			case EventFilterType::lastyear:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1Y' );
				$from = $fromDate->mySqlFormatDate();

				$where .= " AND {$this->tableName}.event_start_date >= '{$from}' and {$this->tableName}.event_start_date <= '{$to}'";
				break;
			
			case EventFilterType::comingNext:
				$where .= " AND {$this->tableName}.event_start_date >= '{$to}' ";
				break;
			
			case EventFilterType::comingWeek:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1W' );
				$from = $fromDate->mySqlFormatDate();
				$where .= " AND {$this->tableName}.event_start_date >= '{$to}' and {$this->tableName}.event_start_date <= '{$from}'";
				break;
			
			case EventFilterType::comingMonth:
				$fromDate = new \Maven\Core\MavenDateTime();
				$fromDate->subFromInterval( 'P1M' );
				$from = $fromDate->mySqlFormatDate();
				$where .= " AND {$this->tableName}.event_start_date >= '{$to}' and {$this->tableName}.event_start_date <= '{$from}'";
				break;
			
			case EventFilterType::custom:
				$whereAux = array();
				
				if ( $this->filter->getEventStartDate() )
					$whereAux[] = "{$this->tableName}.event_start_date >= '{$this->filter->getEventStartDate()}'";
					
				if ( $this->filter->getEventEndDate() )	
					$whereAux[] = "{$this->tableName}.event_start_date <= '{$this->filter->getEventEndDate()}'";
				
				if ( $whereAux )
					$where .= " AND " . implode(" AND ", $whereAux);
				
				break;
		}
		$where = apply_filters( 'mvn_events_search_where', $where);
		return "{$wpWhere} {$where}";
	}
	
	public function whereVenues ( $where ) {
		if( $this->filter->getVenueCity() 
				|| $this->filter->getVenueState() 
				|| $this->filter->getVenueCountry()
			){
			$venuesTable = \MavenEvents\Core\EventsConfig::venuesTableName;
			if( $this->filter->getVenueCity() ){
				$cities = esc_sql( $this->filter->getVenueCity() );
				if( is_array( $cities ) ){
					$cities = implode( "','", $cities);
				}
				$where .= " AND {$venuesTable}.city IN ( '{$cities}' )";
			}
			if( $this->filter->getVenueState() ){
				$states = esc_sql( $this->filter->getVenueState() );
				if( is_array( $states ) ){
					$states = implode( "','", $states);
				}
				$where .= " AND {$venuesTable}.state IN ( '{$states}' )";
			}
			if( $this->filter->getVenueCountry() ){
				$countries = esc_sql( $this->filter->getVenueCountry() );
				if( is_array( $countries ) ){
					$countries = implode( "','", $countries);
				}
				$where .= " AND {$venuesTable}.country IN ( '{$countries}' )";
			}
		}
		return $where;
	}
	
	public  function join ( $join ) {
		$join .= " INNER JOIN {$this->tableName} ON ({$this->db->posts}.ID = {$this->tableName}.id) ";
		return apply_filters( 'mvn_events_search_join', $join);
	}
	
	public function joinVenues ( $join ) {
		if( $this->filter->getVenueCity() 
				|| $this->filter->getVenueState() 
				|| $this->filter->getVenueCountry()
			){
			$venuesTable = \MavenEvents\Core\EventsConfig::venuesTableName;
			$join .= " INNER JOIN {$this->db->term_relationships} tr_venue ON ({$this->db->posts}.ID = tr_venue.object_id) ";
			$join .= " INNER JOIN {$venuesTable} ON (tr_venue.term_taxonomy_id = {$venuesTable}.term_taxonomy_id) ";
		}

		return $join;
	}
	
	
	 
	
}