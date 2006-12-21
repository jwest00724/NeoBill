<?php
/**
 * RegisteredDomainsPage.class.php
 *
 * This file contains the definition for the RegisteredDomainsPage class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require BASE_PATH . "include/SolidStatePage.class.php";

/**
 * RegisteredDomainsPage
 *
 * Display all Registered domains
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class RegisteredDomainsPage extends SolidStatePage
{
  /**
   * Action
   *
   * Actions handled by this page:
   *   none
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "search_domains":
	$this->searchTable( "registered_domains", "domains", $this->post );
	break;

      case "registered_domains":
	if( isset( $this->post['remove'] ) )
	  {
	    $this->removeDomains();
	  }
	break;

      default:
	// No matching action, refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Initialize Registered Domains Page
   *
   * Sets up the domaindbo table to filter out expired domains.
   */
  function init()
  {
    parent::init();

    // Filter out expired domains
    $this->forms['registered_domains']->getField( "domains" )->getWidget()->showActiveDomainsOnly();
  }

  /**
   * Remove Domains
   */
  public function removeDomains()
  {
    // Delete domains
    foreach( $this->post['domains'] as $domainDBO )
      {
	delete_DomainServicePurchaseDBO( $domainDBO );
      }

    $this->setMessage( array( "type" => "[DOMAINS_DELETED]" ) );
    $this->reload();
  }
}
?>