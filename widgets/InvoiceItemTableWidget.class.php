<?php
/**
 * InvoiceItemTableWidget.class.php
 *
 * This file contains the definition of the InvoiceItemTableWidget class.
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * InvoiceItemTableWidget
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class InvoiceItemTableWidget extends TableWidget {
	/**
	 * @var integer Invoice ID
	 */
	private $invoiceID = null;

	/**
	 * Initialize the Table
	 *
	 * @param array $params Parameters from the {form_table} tag
	 */
	public function init( $params ) {
		parent::init( $params );

		// Build an InvoiceItem filter
		$where = isset( $this->invoiceID ) ?
				sprintf( "invoiceid='%d'", $this->invoiceID ) : null;

		// Load the InvoiceItem Table
		try {
			// Build the table
			$items = load_array_InvoiceItemDBO( $where );
			foreach ( $items as $dbo ) {
				// Put the row into the table
				$this->data[] =
						array( "id" => $dbo->getID(),
						"text" => $dbo->getText(),
						"unitamount" => $dbo->getUnitAmount(),
						"quantity" => $dbo->getQuantity(),
						"amount" => $dbo->getAmount() );
			}
		}
		catch ( DBNoRowsFoundException $e ) {

		}
	}

	/**
	 * Set Invoice ID
	 *
	 * @param integer $id Invoice ID
	 */
	public function setInvoiceID( $id ) {
		$this->invoiceID = $id;
	}
}