<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * mithra62 - EE Add-on Stub
 *
 * @package		mithra62:EE_addon_stub
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://blah.com
 * @version		1.0
 * @filesource 	./system/expressionengine/third_party/ee_addon_stub/
 */
 
 /**
 * EE Add-on Stub - Helper Functions
 *
 * Helper Functions
 *
 * @package 	mithra62:Ct_admin
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/ct_admin/helpers/utilities_helper.php
 */

if ( ! function_exists('m62_format_number'))
{


	/**
	 * Timestamp Format
	 * Wrapper that takes a string and converts it according to settings
	 * @param string $date
	 * @param string $format
	 */
	function m62_convert_timestamp($date, $format = FALSE)
	{
		$EE =& get_instance();
		if(!$format)
		{
			$format = $EE->flag_master_lib->settings['flag_master_date_format'];
		}		
		$EE->load->helper('date');		
		return mdate($format, strtotime($date));		
	}
	
	/**
	 * Returns the status color based on $status
	 * @param string $status
	 * @param array $statuses
	 * @return boolean|array
	 */
	function m62_status_color($status, array $statuses = array())
	{
		if(!is_array($statuses))
		{
			return FALSE;
		}

		foreach($statuses AS $color)
		{
			if($status == $color['status'])
				return $color['highlight'];
		}
	}
	
	function m62_country_code($code)
	{
		include APPPATH .'config/countries.php';
		if(isset($countries[$code]))
		{
			return $countries[$code];
		}
		else
		{
			return $code;
		}
	}

}