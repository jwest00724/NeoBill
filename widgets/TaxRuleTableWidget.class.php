<?php
/**
 * TaxRuleTableWidget.class.php
 *
 * This file contains the definition of the TaxRuleTableWidget class.  
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Base class
require_once BASE_PATH . "solidworks/widgets/TableWidget.class.php";

// TaxRuleDBO
require_once BASE_PATH . "DBO/TaxRuleDBO.class.php";

/**
 * TaxRuleTableWidget
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class TaxRuleTableWidget extends TableWidget
{
  /**
   * Initialize the Table
   *
   * @param array $params Parameters from the {form_table} tag
   */
  public function init( $params ) 
  { 
    parent::init( $params );

    // Load the TaxRule Table
    if( null != ($taxRules = load_array_TaxRuleDBO( $where )) )
      {
	// Build the table
	foreach( $taxRules as $dbo )
	  {
	    // Put the row into the table
	    $this->data[] = 
	      array( "id" => $dbo->getID(),
		     "country" => $dbo->getCountry(),
		     "allstates" => $dbo->getAllStates(),
		     "state" => $dbo->getState(),
		     "description" => $dbo->getDescription(),
		     "rate" => $dbo->getRate() );
	  }
      }
  }
}