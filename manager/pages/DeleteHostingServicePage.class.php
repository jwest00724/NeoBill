<?php
/**
 * DeleteHostingServicePage.class.php
 *
 * This file contains the definition for the Delete Hosting Service Page class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require_once BASE_PATH . "include/SolidStateAdminPage.class.php";

require_once BASE_PATH . "DBO/HostingServiceDBO.class.php";
require_once BASE_PATH . "DBO/HostingServicePurchaseDBO.class.php";

/**
 * DeleteHostingServicePage
 *
 * Delete a Hosting Service from the database
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class DeleteHostingServicePage extends SolidStateAdminPage
{
  /**
   * Initialize Delete Hosting Service Page
   */
  function init()
  {
    parent::init();

    // Set URL Fields
    $this->setURLField( "hservice", $this->get['hservice']->getID() );

    // Store service DBO in session
    $this->session['hosting_dbo'] =& $this->get['hservice'];
  }

  /**
   * Action
   *
   * Actions handled by this page:
   *   delete_hosting (form)
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "delete_hosting":
	if( isset( $this->post['delete'] ) )
	  {
	    $this->delete_hosting();
	  }
	elseif( isset( $this->post['cancel'] ) )
	  {
	    $this->cancel();
	  }
	break;

      default:
	// No matching action, refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Cancel
   */
  function cancel()
  {
    $this->goback();
  }

  /**
   * Delete Hosting
   *
   * Delete HostingServiceDBO from database
   */
  function delete_hosting()
  {
    $id = $this->get['hservice']->getID();
    if( load_array_HostingServicePurchaseDBO( "hostingserviceid=" . $id ) != null )
      {
	// Can not delete service if any purchases exist
	$this->setError( array( "type" => "PURCHASES_EXIST" ) );
	$this->cancel();
      }

    // Delete Hosting Service DBO
    if( !delete_HostingServiceDBO( $this->get['hservice'] ) )
      {
	// Delete failed
	$this->setError( array( "type" => "DB_HOSTING_DELETE_FAILED",
				"args" => array( $this->session['hosting_dbo']->getTitle() ) ) );
	$this->cancel();
      }

    // Success - go back to web hosting services page
    $this->setMessage( array( "type" => "HOSTING_SERVICE_DELETED",
			      "args" => array( $this->session['hosting_dbo']->getTitle() ) ) );
    $this->goto( "services_web_hosting" );
  }
}

?>