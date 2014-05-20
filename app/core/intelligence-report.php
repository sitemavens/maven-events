<?php

namespace MavenEvents\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class IntelligenceReport {

	public static function generateData( $data, $lastRun ){
		
		$table = new \Maven\Core\Domain\IntelligenceReport\Table();
		
		$table->setTitle( 'Events Activity');
		
		$table->addColumn( "# of Carts" );
		$table->addColumn( "# of Carts Received" );
		$table->addColumn( "# of Carts Completed" );
		$table->addColumn( "# of Carts with Error" );
		
		$orderManager = new OrderManager();
		
		$countTotal = $orderManager->getCount('total', $lastRun);
		$countError = $orderManager->getCount('error', $lastRun);
		$countCompleted = $orderManager->getCount('completed', $lastRun);
		$countReceived = $orderManager->getCount('received', $lastRun);
		
		$table->addRow( array( $countTotal, $countReceived, $countCompleted, $countError) );
		
		
		$data[] = $table;
		
		
		
		$table = new \Maven\Core\Domain\IntelligenceReport\Table();
		
		$table->setTitle( 'Events eCommerce Activity');
		
		$table->addColumn( "# of Orders" );
		$table->addColumn( "Total Revenue" );
		$table->addColumn( "Avg. Revenue per Order" );
		
		
		$totalRevenue = $orderManager->getRevenue('completed', $lastRun);
		
		$avgRevenue = 0;
		if ( $countCompleted )
			$avgRevenue = $totalRevenue / $countCompleted;
	 
			
		$table->addRow( array( $countCompleted, "$".number_format( $totalRevenue,2), "$".number_format( $avgRevenue,2)) );
		
		$data[] = $table;
		
		
		
		$gGraph = new \Maven\Core\Domain\IntelligenceReport\GoogleGraph();
		$gGraph->setTitle( 'Sales' );
		$gGraph->setUrl("http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countReceived},{$countCompleted},{$countError}&chdl=Received|Completed|Error");
		
		
		$data[] = $gGraph;
		
		return $data;
		
	}
}