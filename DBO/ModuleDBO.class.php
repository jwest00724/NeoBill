<?php
/**
 * ModuleDBO.class.php
 *
 * This file contains the definition for the ModuleDBO class.
 *
 * @package DBO
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * ModuleDBO
 *
 * Represent a Module.
 *
 * @package DBO
 * @author John Diamond <jdiamond@solid-state.org>
 */
class ModuleDBO extends DBO
{
  /**
   * @var string Name of the module
   */
  var $name;

  /**
   * @var string Enabled
   */
  var $enabled;

  /**
   * @var string Module type
   */
  var $type;

  /**
   * @var string Short Description (for drop-down menus, etc.)
   */
  var $shortdescription;

  /**
   * @var string Description
   */
  var $description;

  /**
   * Convert to a String
   *
   * @return string Module name
   */
  function __toString() { return $this->getName(); }

  /**
   * Set the Module Name
   *
   * @param string $name Module name
   */
  function setName( $name ) { $this->name = $name; }

  /**
   * Get the Module Name
   *
   * @return string Module name
   */
  function getName() { return $this->name; }

  /**
   * Set the Enabled Flag
   *
   * @param string $enabled "Yes" or "No"
   */
  function setEnabled( $enabled ) 
  { 
    if( !( $enabled == "Yes" || $enabled == "No" ) )
      {
	fatal_error( "ModuleDBO::setEnabled()", 
		     "Invalid value for enabled: " . $enabled );
      }
    $this->enabled = $enabled; 
  }

  /**
   * Inidicate if the Module is Enabled
   *
   * @return string 'Yes' or 'No'
   */
  function getEnabled() { return $this->enabled; }

  /**
   * Is Enabled
   *
   * @return boolean True if the module is enabled
   */
  function isEnabled() { return $this->enabled == "Yes"; }

  /**
   * Set Type
   *
   * @param string $type Module type
   */
  function setType( $type ) { $this->type = $type; }

  /**
   * Get Type
   *
   * @return string Module type
   */
  function getType() { return $this->type; }

  /**
   * Set Short Description
   *
   * @param string $description Module short description
   */
  function setShortDescription( $description ) 
  { 
    $this->shortdescription = $description; 
  }

  /**
   * Get Short Description
   *
   * @return string Module short description
   */
  function getShortDescription() { return $this->shortdescription; }

  /**
   * Set Description
   *
   * @param string $description Module description
   */
  function setDescription( $description ) { $this->description = $description; }

  /**
   * Get Description
   *
   * @return string Module description
   */
  function getDescription() { return $this->description; }

  /**
   * Get Config Page
   *
   * @return string The name of the configuration page (from the global config var)
   */
  function getConfigPage() 
  { 
    $registry = ModuleRegistry::getModuleRegistry();
    return $registry->getModule( $this->getName() )->getConfigPage();
  }

  /**
   * Load Member Data from an Array
   *
   * @param array $data Date to load
   */
  function load( $data )
  {
    $this->setName( $data['name'] );
    $this->setEnabled( $data['enabled'] );
    $this->setType( $data['type'] );
    $this->setShortDescription( $data['shortdescription'] );
    $this->setDescription( $data['description'] );
  }

  /**
   * Load Module Setting
   *
   * @param string $name Setting name
   * @return string Setting value
   */
  function loadSetting( $name )
  {
    $DB = DBConnection::getDBConnection();

    $sql = $DB->build_select_sql( "modulesetting",
				  "*",
				  sprintf( "name=%s AND modulename=%s",
					   $DB->quote_smart( $name ),
					   $DB->quote_smart( $this->getName() ) ) );
    if( !( $result = @mysql_query( $sql, $DB->handle() ) ) ) 
      {
	throw new DBException( "Could not load module setting: " . mysql_error() );
      }

    $data = mysql_fetch_array( $result );
    return $data ? $data['value'] : null;
  }

  /**
   * Save Module Setting
   *
   * @param string $name Setting name
   * @param string $val Setting value
   * @return boolean True on success
   */
  function saveSetting( $name, $value )
  {
    $DB = DBConnection::getDBConnection();

    if( null == $this->loadSetting( $name ) )
      {
	// Initialize setting in DB
	$sql = $DB->build_insert_sql( "modulesetting",
				      array( "name" => $name,
					     "value" => $value,
					     "modulename" => $this->getName() ) );
      }
    else
      {
	// Update setting in DB
	$sql = $DB->build_update_sql( "modulesetting",
				      sprintf( "name=%s AND modulename=%s",
					       $DB->quote_smart( $name ),
					       $DB->quote_smart( $this->getName() ) ),
				      array( "value" => $value ) );
      }

    if( !mysql_query( $sql, $DB->handle() ) )
      {
	throw new DBException( "Could not insert/update module setting  " . $name . ": " . mysql_error() );
      }

    return true;
  }
}

/**
 * Insert ModuleDBO into database
 *
 * @param ModuleDBO &$dbo ModuleDBO to add to database
 * @return boolean True on success
 */
function add_ModuleDBO( &$dbo )
{
  $DB = DBConnection::getDBConnection();

  // Build SQL
  $sql = $DB->build_insert_sql( "module",
				array( "name" => $dbo->getName(),
				       "enabled" => $dbo->getEnabled(),
				       "type" => $dbo->getType(),
				       "shortdescription" => $dbo->getShortDescription(),
				       "description" => $dbo->getDescription() ) );

  // Run query
  if( !mysql_query( $sql, $DB->handle() ) )
    {
      throw new DBException();
    }
}

/**
 * Update ModuleDBO in database
 *
 * @param ModuleDBO &$dbo ModuleDBO to update
 * @return boolean True on success
 */
function update_ModuleDBO( &$dbo )
{
  $DB = DBConnection::getDBConnection();

  // Build SQL
  $sql = $DB->build_update_sql( "module",
				"name = " . $DB->quote_smart( $dbo->getName() ),
				array( "enabled" => $dbo->getEnabled(),
				       "type" => $dbo->getType(),
				       "shortdescription" => $dbo->getShortDescription(),
				       "description" => $dbo->getDescription() ) );

  // Run query
  if( !mysql_query( $sql, $DB->handle() ) )
    {
      throw new DBException();
    }
}

/**
 * Delete ModuleDBO from database
 *
 * @param ModuleDBO &$dbo ModuleDBO to delete
 * @return boolean True on success
 */
function delete_ModuleDBO( &$dbo )
{
  $DB = DBConnection::getDBConnection();

  // Build SQL
  $sql = $DB->build_delete_sql( "module",
				"name = " . $DB->quote_smart( $dbo->getName() ) );

  // Run query
  if( !mysql_query( $sql, $DB->handle() ) )
    {
      throw new DBException();
    }

  // Remove Module Settings
  $sql = $DB->build_delete_sql( "modulesetting",
				sprintf("modulename=%s",
					$DB->quote_smart( $dbo->getName() ) ) );
  if( !mysql_query( $sql, $DB->handle() ) )
    {
      throw new DBException();
    }
}

/**
 * Load a ModuleDBO from the database
 *
 * @param string Module name
 * @return ModuleDBO ModuleDBO, or null if not found
 */
function load_ModuleDBO( $name )
{
  $DB = DBConnection::getDBConnection();

  // Build query
  $sql = $DB->build_select_sql( "module",
				"*",
				"name=" . $DB->quote_smart( $name ),
				null,
				null,
				null,
				null );

  // Run query
  if( !($result = @mysql_query( $sql, $DB->handle() ) ) )
    {
      // Query error
      throw new DBException();
    }

  if( mysql_num_rows( $result ) == 0 )
    {
      // No rows found
      throw new DBNoRowsFoundException();
    }

  // Load a new HostingServiceDBO
  $dbo = new ModuleDBO();
  $data = mysql_fetch_array( $result );
  $dbo->load( $data );
  
  // Return the new UserDBO
  return $dbo;
}

/**
 * Load multiple ModuleDBO's from database
 *
 * @param string $filter A WHERE clause
 * @param string $sortby Field name to sort results by
 * @param string $sortdir Direction to sort in (ASEC or DESC)
 * @param int $limit Limit the number of results
 * @param int $start Record number to start the results at
 * @return array Array of ModuleDBO's
 */
function &load_array_ModuleDBO( $filter = null,
				$sortby = null,
				$sortdir = null,
				$limit = null,
				$start = null )
{
  $DB = DBConnection::getDBConnection();

  // Build query
  $sql = $DB->build_select_sql( "module",
				"*",
				$filter,
				$sortby,
				$sortdir,
				$limit,
				$start );

  // Run query
  if( !( $result = @mysql_query( $sql, $DB->handle() ) ) )
    {
      // Query error
      throw new DBException();
    }

  if( mysql_num_rows( $result ) == 0 )
    {	  
      throw new DBNoRowsFoundException();
    }

  // Build an array of DBOs from the result set
  $dbo_array = array();
  while( $data = mysql_fetch_array( $result ) )
    {
      // Create and initialize a new DBO with the data from the DB
      $dbo =& new ModuleDBO();
      $dbo->load( $data );

      // Add HostingServiceDBO to array
      $dbo_array[] = $dbo;
    }

  return $dbo_array;
}

/**
 * Count ModuleDBO's
 *
 * Same as load_array_ModuleDBO, except this function just COUNTs the 
 * number of rows in the database.
 *
 * @param string $filter A WHERE clause
 * @param string $sortby Field name to sort results by
 * @param string $sortdir Direction to sort in (ASEC or DESC)
 * @param int $limit Limit the number of results
 * @param int $start Record number to start the results at
 * @return integer Number of Module records
 */
function count_all_ModuleDBO( $filter = null )
{
  $DB = DBConnection::getDBConnection();
  
  // Build query
  $sql = $DB->build_select_sql( "module",
				"COUNT(*)",
				$filter,
				null,
				null,
				null,
				null );

  // Run query
  if( !( $result = @mysql_query( $sql, $DB->handle() ) ) )
    {
      // SQL error
      throw new DBException();
    }

  // Make sure the number of rows returned is exactly 1
  if( mysql_num_rows( $result ) != 1 )
    {
      // This must return 1 row
      throw new DBException();
    }

  $data = mysql_fetch_array( $result );
  return $data[0];
}
?>