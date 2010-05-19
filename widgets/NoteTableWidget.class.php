<?php
/**
 * NoteTableWidget.class.php
 *
 * This file contains the definition of the NoteTableWidget class.
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * NoteTableWidget
 *
 * @package SolidWorks
 * @author John Diamond <jdiamond@solid-state.org>
 */
class NoteTableWidget extends TableWidget {
	/**
	 * @var integer Account ID
	 */
	private $accountID = null;

	/**
	 * Initialize the Table
	 *
	 * @param array $params Parameters from the {form_table} tag
	 */
	public function init( $params ) {
		parent::init( $params );

		// Build an note filter
		$where = isset( $this->accountID ) ?
				sprintf( "accountid='%d'", $this->accountID ) : null;

		// Load the Note Table
		try {
			// Build the table
			$notes = load_array_NoteDBO( $where );
			foreach ( $notes as $dbo ) {
				// Put the row into the table
				$this->data[] =
						array( "id" => $dbo->getID(),
						"username" => $dbo->getUsername(),
						"updated" => $dbo->getUpdated(),
						"text" => $dbo->getText() );
			}
		}
		catch ( DBNoRowsFoundException $e ) {

		}
	}

	/**
	 * Set Account ID
	 *
	 * @param integer $id Account ID
	 */
	public function setAccountID( $id ) {
		$this->accountID = $id;
	}
}