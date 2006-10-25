<?php
/**
 * AssignHostingPage.class.php
 *
 * This file contains the definition for the AssignHostingPage class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require_once BASE_PATH . "include/SolidStatePage.class.php";

require_once BASE_PATH . "DBO/AccountDBO.class.php";
require_once BASE_PATH . "DBO/HostingServiceDBO.class.php";
require_once BASE_PATH . "DBO/ServerDBO.class.php";
require_once BASE_PATH . "DBO/IPAddressDBO.class.php";

/**
 * AssignHostingPage
 *
 * Assign a hosting service purchase to an account.
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class AssignHostingPage extends SolidStatePage
{
  /**
   * Initialize Assign Hosting Page
   */
  function init()
  {
    parent::init();

    // Set URL Fields
    $this->setURLField( "account", $this->get['account']->getID() );

    // Store service DBO in session
    $this->session['account_dbo'] =& $this->get['account'];
  }

  /**
   * Action
   *
   * Actions handled by this page:
   *   assign_hosting (form)
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "assign_hosting":
	if( isset( $this->post['continue'] ) )
	  {
	    // Add service to account
	    $this->assign_service();
	  }
	elseif( isset( $this->post['cancel'] ) )
	  {
	    // Cancel
	    $this->goback();
	  }
	break;

      default:
	// No matching action - refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Assign Service
   *
   * Create a HostingServicePurchaseDBO and add it to the database
   */
  function assign_service()
  {
    // If this HostingService requires a unique IP, make sure the user selected one
    if( $this->post['service']->getUniqueIP() == "Required" && 
	!isset( $this->post['ipaddress'] ) )
      {
	$this->setError( array( "type" => "SELECT_IP" ) );
	$this->reload();
      }

    // Create new HostingServicePurchase DBO
    $serverID = isset( $this->post['server'] ) ? $this->post['server']->getID() : null;

    $purchase_dbo = new HostingServicePurchaseDBO();
    $purchase_dbo->setAccountID( $this->get['account']->getID() );
    $purchase_dbo->setHostingServiceID( $this->post['service']->getID() );
    $purchase_dbo->setTerm( $this->post['term'] );
    $purchase_dbo->setServerID( $serverID );
    $purchase_dbo->setDate( $this->DB->format_datetime( $this->post['date'] ) );

    // Save purchase
    if( !add_HostingServicePurchaseDBO( $purchase_dbo ) )
      {
	// Add failed
	$this->setError( array( "type" => "DB_ASSIGN_HOSTING_FAILED",
				"args" => array( $service_dbo->getTitle() ) ) );
	$this->reload();
      }

    // If an IP address was selected, assign that IP address to this purchase
    if( isset( $this->post['ipaddress'] ) )
      {
	if( $this->post['ipaddress']->getServerID() != $serverID )
	  {
	    // IP Address does not match Server
	    $this->setError( array( "type" => "IP_MISMATCH",
				    "args" => array( $this->post['ipaddress']->getIPString(),
						     $this->post['server']->getHostName() ) ) );
	    // Roll-back
	    delete_HostingServicePurchaseDBO( $purchase_dbo );
	    $this->reload();
	  }

	// Update IP Address record
	$this->post['ipaddress']->setPurchaseID( $purchase_dbo->getID() );
	if( !update_IPAddressDBO( $this->post['ipaddress'] ) )
	  {
	    // Invalid IP
	    $this->setError( array( "type" => "DB_IP_UPDATE_FAILED" ) );

	    // Roll-back
	    delete_HostingServicePurchaseDBO( $purchase_dbo );
	    $this->reload();
	  }
      }
    
    // Success
    $this->setMessage( array( "type" => "HOSTING_ASSIGNED" ) );
    $this->goto( "accounts_view_account",
		 null,
		 "action=services&account=" . $this->get['account']->getID() );
  }
}