<?php
/**
 * TaxesPage.class.php
 *
 * This file contains the definition for the Taxes Page class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require BASE_PATH . "include/SolidStatePage.class.php";

/**
 * TaxesPage
 *
 * Display all Taxes
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class TaxesPage extends SolidStatePage
{
  /**
   * Action
   *
   * Actions handled by this page:
   *   browse_taxes_action (form)
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "taxes_action":
	// Create a new tax rule
	$this->goto( "add_tax_rule" );
	break;

      case "tax_rules":
	if( isset( $this->post['remove'] ) )
	  {
	    $this->remove();
	  }
	break;

      default:
	// No matching action, refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Remove Tax Rule
   */
  function remove()
  {
    if( $_SESSION['client']['userdbo']->getType() != "Administrator" )
      {
	$this->setError( array( "type" => "ACCESS_DENIED" ) );
	return;
      }

    // Remove the Tax Rule(s) from the database
    foreach( $this->post['rules'] as $dbo )
      {
	if( !delete_TaxRuleDBO( $dbo ) )
	  {
	    $this->setError( array( "type" => "DB_DELETE_TAX_RULE_FAILED",
				    "args" => array( $id ) ) );
	    $this->reload();
	  }
      }

    // Success
    $this->setMessage( array( "type" => "TAX_RULES_DELETED" ) );
    $this->reload();
  }
}

?>