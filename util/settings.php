<?php
/**
 * settings.php
 *
 * This file contains functions that manage configuration settings
 *
 * @package Utilities
 * @author John Diamond <jdiamond@solid-state.org>
 * @copyright John Diamond <jdiamond@solid-state.org>
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Load Settings
 *
 * Load the application settings from the database
 *
 * @param array $conf Configuration data
 */
function load_settings( &$conf )
{
  global $DB;

  // Build Query
  $sql = $DB->build_select_sql( "settings", "*" );

  // Run Query
  if( !( $result = @mysql_query( $sql, $DB->handle() ) ) )
    {
      fatal_error( "load_settings", 
		   "Could not load configuration settings from database!" );
    }

  while( $setting = mysql_fetch_array( $result ) )
    {
      $key = $setting['setting'];
      $val = $setting['value'];
      switch( $key )
	{
	case "company_name": $conf['company']['name'] = $val; break;
	case "company_email": $conf['company']['email'] = $val; break;

	case "welcome_email": $conf['welcome_email'] = $val; break;
	case "welcome_subject": $conf['welcome_subject'] = $val; break;

	case "nameservers_ns1": $conf['dns']['nameservers'][0] = $val; break;
	case "nameservers_ns2": $conf['dns']['nameservers'][1] = $val; break;
	case "nameservers_ns3": $conf['dns']['nameservers'][2] = $val; break;
	case "nameservers_ns4": $conf['dns']['nameservers'][3] = $val; break;

	case "invoice_text": $conf['invoice_text'] = $val; break;

	case "locale_language": $conf['locale']['language'] = $val; break;
	case "locale_currency_symbol": $conf['locale']['currency_symbol'] = $val; break;

	case "order_remote_password": $conf['order']['remote_password'] = $val; break;
	}
    }
}

/**
 * Save Settings
 *
 * Save the application settings to the database
 *
 * @param array $conf Configuration data
 */
function save_settings( &$conf )
{
  update_setting( "company_name", $conf['company']['name'] );
  update_setting( "company_email", $conf['company']['email'] );

  update_setting( "welcome_email", $conf['welcome_email'] );
  update_setting( "welcome_subject", $conf['welcome_subject'] );

  update_setting( "nameservers_ns1", $conf['dns']['nameservers'][0] );
  update_setting( "nameservers_ns2", $conf['dns']['nameservers'][1] );
  update_setting( "nameservers_ns3", $conf['dns']['nameservers'][2] );
  update_setting( "nameservers_ns4", $conf['dns']['nameservers'][3] );

  update_setting( "invoice_text", $conf['invoice_text'] );

  update_setting( "locale_currency_symbol", $conf['locale']['currency_symbol'] );
  update_setting( "locale_language", $conf['locale']['language'] );

  update_setting( "order_remote_password", $conf['order']['remote_password'] );

  // Reload
  load_settings( $conf );
}

/**
 * Update Setting
 *
 * Updates a single setting record
 *
 * @param string $key Key
 * @param string $value New value
 */
function update_setting( $key, $value )
{
  global $DB;

  // Build SQL
  $sql = $DB->build_update_sql( "settings",
				"setting = " . $DB->quote_smart( $key ),
				array( "value" => $value ) );

  // Run query
  if( !mysql_query( $sql, $DB->handle() ) )
    {
      fatal_error( "update_setting", "Failed to update setting: " . mysql_error() );
    }
}

/**
 * Load Order Settings (RPC Server)
 *
 * @param string $params[0] Remote Username
 * @param string $params[1] Remote Password
 *
 * @return array Config settings related to the Order interface
 */
function load_OrderSettings_RPCServer( $params )
{
  global $conf;

  // Return only the settings that relate to the Order interface
  $settings = array( "company" => $conf['company'],
		     "dns" => $conf['dns'],
		     "locale" => $conf['locale'] );

  // Verify access
  if( !verify_remote_order( $params[0], $params[1] ) )
    {
      return "Access Denied";
    }

  return $settings;
}

?>
