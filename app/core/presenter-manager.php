<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class PresenterManager {

	
	public function __construct() {
		;
	}
	
	public function getAll( $orderBy = "display_name", $orderType = 'desc', $start = 0, $limit = 1000){
		
		$presenterMapper = new Mappers\PresenterMapper();
		
		return $presenterMapper->getAll($orderBy, $orderType, $start, $limit);
		
	}
	
	public function getCount(){
		$mapper=new Mappers\PresenterMapper();
		
		return $mapper->getCount();
	}

	/**
	 * 
	 * @param \MavenEvents\Core\Domain\Presenter or array $presenter
	 * @return \Maven\Core\Message\Message
	 */
	function addPresenter( $presenter ) {
		
		$presenterToUpdate = new Domain\Presenter();
		
		if ( is_array( $presenter ) )
			\Maven\Core\FillerHelper::fillObject($presenterToUpdate, $presenter);
		else
			$presenterToUpdate = $presenter;
		
		$update = false;
		
		if ( $presenterToUpdate->getId() )
			$update = true;
		
		$presenterMapper = new Mappers\PresenterMapper();
		
		$presenterMapper->save( $presenterToUpdate );
		
		
		if ( $update ){
			//Actions::UpdateEvent ( $event );
		}
		else{
			Actions::AddPresenter ( $presenterToUpdate );
		}
		
		return $presenterToUpdate;
		
	}
	
	public function get( $id ){
		
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'ID is required' );
		
		$presenterMapper = new Mappers\PresenterMapper();
		
		$presenter = $presenterMapper->get( $id );
		
		if ( ! $presenter )
			throw new \Maven\Exceptions\NotFoundException( 'Presenter not found:'.$id );
		
		return $presenter;
		
	}
	
	public function delete( $id ){
		
		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException('Id is required');
			
		$presenterMapper = new Mappers\PresenterMapper();
		
		return $presenterMapper->delete( $id );
	}

}

