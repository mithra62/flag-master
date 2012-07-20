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
	
	function m62_format_user_agent($user_agent)
	{
		$EE =& get_instance();
		$EE->agent->agent = $user_agent;
		if ($EE->agent->_load_agent_file())
		{
			$EE->agent->_compile_data();
		}

		if ($EE->agent->is_browser())
		{
			$agent = $EE->agent->browser().' '.$EE->agent->version();
		}
		elseif ($EE->agent->is_robot())
		{
			$agent = $EE->agent->robot();
		}
		elseif ($EE->agent->is_mobile())
		{
			$agent = $EE->agent->mobile();
		}
		else
		{
			$agent = 'Unidentified User Agent';
		}	

		return $EE->agent->platform().'/'.$agent;
	}
	
	function m62_table_template()
	{
		return array (
				'table_open'          => '<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">',
		
				'heading_row_start'   => '<tr>',
				'heading_row_end'     => '</tr>',
				'heading_cell_start'  => '<th nowrap>',
				'heading_cell_end'    => '</th>',
		
				'row_start'           => '<tr>',
				'row_end'             => '</tr>',
				'cell_start'          => '<td nowrap>',
				'cell_end'            => '</td>',
		
				'row_alt_start'       => '<tr>',
				'row_alt_end'         => '</tr>',
				'cell_alt_start'      => '<td nowrap>',
				'cell_alt_end'        => '</td>',
		
				'table_close'         => '</table>'
		);		
	}

}