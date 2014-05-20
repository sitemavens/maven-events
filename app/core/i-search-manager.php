<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


interface iSearchManager {
	
	function getEvents( \MavenEvents\Core\Domain\EventFilter $filter );
}	