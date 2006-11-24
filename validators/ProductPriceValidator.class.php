<?php
/**
 * HostingServicePriceValidator.class.php
 *
 * This file contains the definition of the HostingServicePriceValidator class.  
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * HostingServicePriceValidator
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class ProductPriceValidator extends FieldValidator
{
  /**
   * Validate a Hosting Service Price
   *
   * Verifies that the hosting service price exists.
   *
   * @param string $data Field data
   * @return HostingServiceDBO HostingService DBO for this HostingService ID
   * @throws RecordNotFoundException
   */
  public function validate( $data )
  {
    $data = parent::validate( $data );

    $id = explode( "-", $data );
    if( null == ($priceDBO = load_ProductPriceDBO( intval( $id[0] ), 
						   $id[1], 
						   intval( $id[2] ) )) )
      {
	throw new RecordNotFoundException( "HostingServicePrice" );
      }

    return $priceDBO;
  }
}
?>