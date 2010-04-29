<?php
/**
 * OrderHostingDBO.class.php
 *
 * This file contains the definition for the OrderHostingDBO class.
 *
 * @package DBO
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * OrderHostingDBO
 *
 * Represent an OrderHosting.
 *
 * @package DBO
 * @author John Diamond <jdiamond@solid-state.org>
 */
class OrderHostingDBO extends OrderItemDBO {
    /**
     * @var string Domain name to be hosted with this service
     */
    protected $domainName = null;

    /**
     * @var integer OrderHosting ID
     */
    protected $id = null;

    /**
     * Set OrderHosting ID
     *
     * @param integer $id OrderHosting ID
     */
    public function setID( $id ) {
        $this->id = $id;
    }

    /**
     * Get OrderHosting ID
     *
     * @return integer OrderHosting ID
     */
    public function getID() {
        return $this->id;
    }

    /**
     * Set Purchasable
     *
     * @param HostingServiceDBO The hosting service to be purchased
     */
    public function setPurchasable( HostingServiceDBO $purchasable ) {
        // The purpose of this function is to forc the purchasable to be a HostingServiceDBO
        parent::setPurchasable( $purchasable );
    }

    /**
     * Set Service ID
     *
     * @param integer $id Hosting Service ID
     */
    public function setServiceID( $id ) {
        $this->setPurchasable( load_HostingServiceDBO( $id ) );
    }

    /**
     * Get Service ID
     *
     * @return integer Hosting Service ID
     */
    public function getServiceID() {
        return $this->purchasable->getID();
    }

    /**
     * Get Description
     *
     * @return string Description of this order item
     */
    public function getDescription() {
        return sprintf( "%s (%s)", $this->purchasable->getTitle(), $this->getDomainName() );
    }

    /**
     * Set Domain Name
     *
     * @param string $domainName The domain name to be hosted by this service
     */
    public function setDomainName( $domainName ) {
        $this->domainName = $domainName;
    }

    /**
     * Get Domain Name
     *
     * @return string The domain name to be hosted by this service
     */
    public function getDomainName() {
        return $this->domainName;
    }

    /**
     * Execute Hosting Service Order
     *
     * Create a new Hosting Service Purchase for this order item
     *
     * @param AccountDBO $accountDBO Account object
     * @return boolean True for success
     */
    public function execute( $accountDBO ) {
        // Create a hosting service purchase record
        $purchaseDBO = new HostingServicePurchaseDBO();
        $purchaseDBO->setAccountID( $accountDBO->getID() );
        $purchaseDBO->setHostingServiceID( $this->getServiceID() );
        $purchaseDBO->setTerm( $this->getTerm() );
        $purchaseDBO->setDate( DBConnection::format_datetime( time() ) );
        $purchaseDBO->setDomainName( $this->getDomainName() );
        $purchaseDBO->setPrevInvoiceID( -1 );
        $purchaseDBO->incrementNextBillingDate();
        add_HostingServicePurchaseDBO( $purchaseDBO );

        // Fulfill the order and return
        $this->setStatus( "Fulfilled" );
        update_OrderHostingDBO( $this );

        // Success
        return true;
    }
}

/**
 * Insert OrderHostingDBO into database
 *
 * @param OrderHostingDBO &$dbo OrderHostingDBO to add to database
 */
function add_OrderHostingDBO( OrderHostingDBO $dbo ) {
    $DB = DBConnection::getDBConnection();

    // Build SQL
    $sql = $DB->build_insert_sql( "orderhosting",
            array( "orderid" => intval( $dbo->getOrderID() ),
            "orderitemid" => intval( $dbo->getOrderItemID() ),
            "serviceid" => $dbo->getServiceID(),
            "status" => $dbo->getStatus(),
            "term" => $dbo->getTerm(),
            "domainname" => $dbo->getDomainName() ) );

    // Run query
    if( !mysql_query( $sql, $DB->handle() ) ) {
        throw new DBException( mysql_error( $DB->handle() ) );
    }

    // Get auto-increment ID
    $id = mysql_insert_id( $DB->handle() );

    // Validate ID
    if( $id === false ) {
        // DB error
        throw new DBException( "Could not retrieve ID from previous INSERT!" );
    }
    if( $id == 0 ) {
        // No ID?
        throw new DBException( "Previous INSERT did not generate an ID" );
    }

    // Store ID in DBO
    $dbo->setID( $id );
}

/**
 * Update OrderHostingDBO in database
 *
 * @param OrderHostingDBO &$dbo OrderHostingDBO to update
 */
function update_OrderHostingDBO( OrderHostingDBO $dbo ) {
    $DB = DBConnection::getDBConnection();

    // Build SQL
    $sql = $DB->build_update_sql( "orderhosting",
            "id = " . intval( $dbo->getID() ),
            array( "orderid" => intval( $dbo->getOrderID() ),
            "serviceid" => $dbo->getServiceID(),
            "status" => $dbo->getStatus(),
            "term" => $dbo->getTerm(),
            "domainname" => $dbo->getDomainName() ) );

    // Run query
    if( !mysql_query( $sql, $DB->handle() ) ) {
        throw new DBException( mysql_error( $DB->handle() ) );
    }
}

/**
 * Delete OrderHostingDBO from database
 *
 * @param OrderHostingDBO &$dbo OrderHostingDBO to delete
 * @return boolean True on success
 */
function delete_OrderHostingDBO( OrderHostingDBO $dbo ) {
    $DB = DBConnection::getDBConnection();

    // Build SQL
    $sql = $DB->build_delete_sql( "orderhosting",
            "id = " . intval( $dbo->getID() ) );

    // Run query
    if( !mysql_query( $sql, $DB->handle() ) ) {
        throw new DBException( mysql_error( $DB->handle() ) );
    }
}

/**
 * Load a OrderHostingDBO from the database
 *
 * @param integer $id OrderHosting ID
 * @return OrderHostingDBO OrderHostingDBO, or null if not found
 */
function load_OrderHostingDBO( $id ) {
    $DB = DBConnection::getDBConnection();

    // Build query
    $sql = $DB->build_select_sql( "orderhosting",
            "*",
            "id=" . intval( $id ),
            null,
            null,
            null,
            null );

    // Run query
    if( !($result = @mysql_query( $sql, $DB->handle() ) ) ) {
        // Query error
        throw new DBException( mysql_error( $DB->handle() ) );
    }

    if( mysql_num_rows( $result ) == 0 ) {
        // No rows found
        throw new DBNoRowsFoundException();
    }

    // Load a new OrderDBO
    $dbo = new OrderHostingDBO();
    $data = mysql_fetch_array( $result );
    $dbo->load( $data );

    // Return the new UserDBO
    return $dbo;
}

/**
 * Load multiple OrderHostingDBO's from database
 *
 * @param string $filter A WHERE clause
 * @param string $sortby Field name to sort results by
 * @param string $sortdir Direction to sort in (ASEC or DESC)
 * @param int $limit Limit the number of results
 * @param int $start Record number to start the results at
 * @return array Array of OrderDBO's
 */
function &load_array_OrderHostingDBO( $filter = null,
        $sortby = null,
        $sortdir = null,
        $limit = null,
        $start = null ) {
    $DB = DBConnection::getDBConnection();

    // Build query
    $sql = $DB->build_select_sql( "orderhosting",
            "*",
            $filter,
            $sortby,
            $sortdir,
            $limit,
            $start );

    // Run query
    if( !( $result = @mysql_query( $sql, $DB->handle() ) ) ) {
        // Query error
        throw new DBException( mysql_error( $DB->handle() ) );
    }

    if( mysql_num_rows( $result ) == 0 ) {
        // No rows found
        throw new DBNoRowsFoundException();
    }

    // Build an array of DBOs from the result set
    $dbo_array = array();
    while( $data = mysql_fetch_array( $result ) ) {
        // Create and initialize a new DBO with the data from the DB
        $dbo = new OrderHostingDBO();
        $dbo->load( $data );

        // Add OrderDBO to array
        $dbo_array[] = $dbo;
    }

    return $dbo_array;
}

/**
 * Count OrderHostingDBO's
 *
 * Same as load_array_OrderHostingDBO, except this function just COUNTs the number
 * of rows in the database.
 *
 * @param string $filter A WHERE clause
 * @param string $sortby Field name to sort results by
 * @param string $sortdir Direction to sort in (ASEC or DESC)
 * @param int $limit Limit the number of results
 * @param int $start Record number to start the results at
 * @return array Array of OrderDBO's
 */
function count_all_OrderHostingDBO( $filter = null ) {
    $DB = DBConnection::getDBConnection();

    // Build query
    $sql = "SELECT COUNT(*) FROM orderhosting";

    // Run query
    if( !( $result = @mysql_query( $sql, $DB->handle() ) ) ) {
        // SQL error
        throw new DBException( mysql_error( $DB->handle() ) );
    }

    // Make sure the number of rows returned is exactly 1
    if( mysql_num_rows( $result ) != 1 ) {
        // This must return 1 row
        throw new DBException( "Expected exactly one row from count query" );
    }

    $data = mysql_fetch_array( $result );
    return $data[0];
}
?>