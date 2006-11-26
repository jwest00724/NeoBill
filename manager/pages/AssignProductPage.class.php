<?php
/**
 * AssignProductPage.class.php
 *
 * This file contains the definition for the AssignProductPage class
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Include the parent class
require_once BASE_PATH . "include/SolidStatePage.class.php";

require_once BASE_PATH . "DBO/AccountDBO.class.php";

/**
 * AssignProductPage
 *
 * Assign a product purchase to an account
 *
 * @package Pages
 * @author John Diamond <jdiamond@solid-state.org>
 */
class AssignProductPage extends SolidStatePage
{
  /**
   * Action
   *
   * Actions handled by this page:
   *   assign_product (form)
   *
   * @param string $action_name Action
   */
  function action( $action_name )
  {
    switch( $action_name )
      {
      case "assign_product":
	if( isset( $this->post['continue'] ) )
	  {
	    // Add product to account
	    $this->assign_product();
	  }
	elseif( isset( $this->post['cancel'] ) )
	  {
	    // Cancel
	    $this->goback();
	  }
	elseif( isset( $this->post['product'] ) )
	  {
	    $this->updatePrices( $this->post['product'] );
	  }
	break;

      default:
	// No matching action - refer to base class
	parent::action( $action_name );
      }
  }

  /**
   * Initialize Assign Product Page
   */
  function init()
  {
    parent::init();

    // Set URL Fields
    $this->setURLField( "account", $this->get['account']->getID() );

    // Store service DBO in session
    $this->session['account_dbo'] = $dbo;

    if( null == ($products = load_array_ProductDBO()) )
      {
	$this->setError( array( "type" => "[THERE_ARE_NO_PRODUCTS]" ) );
	$this->goback();
      }

    if( !isset( $this->post['product'] ) )
      {
	$this->updatePrices( array_shift( $products ) );
      }
  }

  /**
   * Assign Product
   *
   * Create a Product Purchase DBO and add it to the database
   */
  function assign_product()
  {
    // Create new ProductPurchase DBO
    $purchase_dbo = new ProductPurchaseDBO();
    $purchase_dbo->setAccountID( $this->get['account']->getID() );
    $purchase_dbo->setProductID( $this->post['product']->getID() );
    $purchase_dbo->setTerm( $this->post['term']->getTermLength() );
    $purchase_dbo->setDate( $this->DB->format_datetime( $this->post['date'] ) );
    $purchase_dbo->setNote( $this->post['note'] );

    // Save purchase
    if( !add_ProductPurchaseDBO( $purchase_dbo ) )
      {
	// Add failed
	$this->setError( array( "type" => "DB_ASSIGN_PRODUCT_FAILED",
				"args" => array( $service_dbo->getName() ) ) );
	$this->reload();
      }
    
    // Success
    $this->setMessage( array( "type" => "PRODUCT_ASSIGNED" ) );
    $this->goto( "accounts_view_account",
		 null,
		 "action=products&account=" . $this->get['account']->getID() );
  }

  /**
   * Update Prices Box
   *
   * @param ProductDBO The product to show prices for
   */
  protected function updatePrices( ProductDBO $productDBO )
  {
    // Update the service terms box
    $widget = $this->forms['assign_product']->getField( "term" )->getWidget();
    $widget->setPurchasable( $productDBO );
  }
}