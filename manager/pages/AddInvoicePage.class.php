<?php
/**
 * AddInvoicePage.class.php
 *
 * This file contains the definition for the AddInvoicePage class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require BASE_PATH . "include/SolidStatePage.class.php";

/**
 * AddInvoicePage
 *
 * Creates a new invoice.
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class AddInvoicePage extends SolidStatepage
{
  /**
   * Initialize Add Invoice Page
   */
  function init()
  {
    parent::init();

    if( isset( $this->get['account'] ) )
      {
	$this->setURLField( "account", $this->get['account']->getID() );
	$this->session['account_dbo'] =& $this->get['account'];
	$this->smarty->assign( "account",
			       $this->get['account']->getID() );
	$this->smarty->assign( "account_name", 
			       $this->get['account']->getAccountName() );
      }
  }

  /**
   * Action
   *
   * Actions handled by this page:
   *   new_invoice
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "new_invoice":
	if( isset( $this->post['cancel'] ) )
	  {
	    // Cancel
	    $this->cancel();
	  }
	elseif( isset( $this->post['continue'] ) )
	  {
	    // Generate Invoice
	    $this->generate_invoice();
	  }
	break;

      default:
	// No matching action, refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Form Canceled
   */
  function cancel()
  {
    $this->goback();
  }

  /**
   * Generate Invoice
   *
   * Creates a new Invoice and adds it to the database.
   */
  function generate_invoice()
  {
    // Determine the correct source of the account ID
    $account_id = isset( $this->get['account'] ) ?
      $this->get['account']->getID() : $this->post['account']->getID();

    // Create a new invoice DBO
    $invoice = new InvoiceDBO();
    $invoice->setAccountID( $account_id );
    $invoice->setDate( $this->DB->format_datetime( $this->post['date'] ) );
    $invoice->setPeriodBegin( $this->DB->format_datetime( $this->post['periodbegin'] ) );
    $invoice->setPeriodEnd( $this->DB->format_datetime( $this->post['periodend'] ) );
    $invoice->setNote( $this->post['note'] );
    $invoice->setTerms( $this->post['terms'] );

    // Generate lineitems
    $invoice->generate();

    // Insert invoice into database
    if( !add_InvoiceDBO( $invoice ) )
      {
	// Add failed
	$this->setError( array( "type" => "DB_ADD_INVOICE_FAILED" ) );
	$this->reload();
      }

    // Success
    $this->setMessage( array( "type" => "INVOICE_CREATED" ) );
    $this->goto( "billing_view_invoice",
		 null,
		 "invoice=" . $invoice->getID() );
  }

}

?>