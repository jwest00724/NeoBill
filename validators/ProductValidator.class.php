<?php
/**
 * ProductValidator.class.php
 *
 * This file contains the definition of the ProductValidator class.  
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Base class
require_once BASE_PATH . "solidworks/validators/FieldValidator.class.php";

// Exceptions
require_once BASE_PATH . "exceptions/RecordNotFoundException.class.php";

// Product DBO
require_once BASE_PATH . "DBO/ProductDBO.class.php";

/**
 * ProductValidator
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class ProductValidator extends FieldValidator
{
  /**
   * Validate a Product ID
   *
   * Verifies that the product exists.
   *
   * @param array $config Field configuration
   * @param string $data Field data
   * @return ProductDBO Product DBO for this Product ID
   * @throws RecordNotFoundException
   */
  public function validate( $data )
  {
    $data = parent::validate( $data );

    if( null == ($productDBO = load_ProductDBO( intval( $data ) )) )
      {
	throw new RecordNotFoundException( "Product" );
      }

    return $productDBO;
  }
}
?>