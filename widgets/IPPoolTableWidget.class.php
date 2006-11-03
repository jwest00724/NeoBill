<?php
/**
 * IPPoolTableWidget.class.php
 *
 * This file contains the definition of the IPPoolTableWidget class.  
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Base class
require_once BASE_PATH . "solidworks/widgets/TableWidget.class.php";

// IPAddressDBO
require_once BASE_PATH . "DBO/IPAddressDBO.class.php";

/**
 * IPPoolTableWidget
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class IPPoolTableWidget extends TableWidget
{
  /**
   * Initialize the Table
   *
   * @param array $params Parameters from the {form_table} tag
   */
  public function init( $params ) 
  { 
    parent::init( $params );

    // Load the IP Address pool
    $IPAddresses = load_array_IPAddressDBO();

    // Build the table
    foreach( $IPAddresses as $dbo )
      {
	// Put the row into the table
	$this->data[] = 
	  array( "ipaddress" => $dbo->getIP(),
                 "ipaddressstring" => $dbo->getIPString(),
		 "server" => $dbo->getServerID(),
                 "hostname" => $dbo->getHostName(),
                 "isAvailable" => $dbo->isAvailable(),
		 "accountname" => $dbo->getAccountName(),
		 "service" => $dbo->getServiceTitle() );
      }
  }
}