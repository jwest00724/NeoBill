<?php
/**
 * OrderExistingDomainArrayValidator.class.php
 *
 * This file contains the definition of the OrderExistingDomainArrayValidator class.  
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

// Base class
require_once BASE_PATH . "validators/OrderExistingDomainValidator.class.php";

/**
 * OrderExistingDomainArrayValidator
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class OrderExistingDomainArrayValidator extends OrderExistingDomainValidator
{
  /**
   * Validate an OrderItem ID or an array of OrderItem ID's
   *
   * Verifies that the existing domain(s) exists.
   *
   * @param string $data Field data
   * @return array An array of OrderItemDBOs
   * @throws RecordNotFoundException
   */
  public function validate( $data )
  {
    // A single value gets transformed into an array first
    $data = !is_array( $data ) ? array( $data ) : $data;

    // Verify each element of the array and store the results in an array
    $result = array();
    foreach( $data as $dataitem )
      {
	// Pass up to OrderItemValidator to do the work
	$result[] = parent::validate( $dataitem );
      }

    return $result;
  }
}
?>