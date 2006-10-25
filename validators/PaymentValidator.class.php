<?php
/**
 * PaymentValidator.class.php
 *
 * This file contains the definition of the PaymentValidator class.  
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

// Payment DBO
require_once BASE_PATH . "DBO/PaymentDBO.class.php";

/**
 * PaymentValidator
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class PaymentValidator extends FieldValidator
{
  /**
   * Validate a Payment ID
   *
   * Verifies that the payment exists.
   *
   * @param string $data Field data
   * @return PaymentDBO Payment DBO for this Payment ID
   * @throws RecordNotFoundException
   */
  public function validate( $data )
  {
    $data = parent::validate( $data );

    if( null == ($paymentDBO = load_PaymentDBO( intval( $data ) )) )
      {
	throw new RecordNotFoundException( "Payment" );
      }

    return $paymentDBO;
  }
}
?>