<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - Flag Master
 *
 * @package		mithra62:Flag_master
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://blah.com
 * @version		1.0
 * @filesource 	./system/expressionengine/third_party/flag_master/
 */

/**
 * Flag Master - Profile Library
 *
 * Library Class
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/libraries/flag_master_profiles.php
 */
class Flag_master_profile_options
{
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->model('Flag_master_profile_options_model', 'flag_master_options_model');
		$this->settings = $this->EE->flag_master_lib->get_settings();
	}
	
	public function get_profile_options(array $where)
	{
		return $this->EE->flag_master_options_model->get_options($where);
	}
	
	public function get_profile_option(array $where)
	{
		return $this->EE->flag_master_options_model->get_option($where);
	}	
	
	public function add_profile_option($profile_id, $data)
	{
		$data['profile_id'] = $profile_id;		
		$data['created_by'] = $this->EE->session->userdata['member_id'];
		if(!isset($data['user_defined']) || $data['user_defined'] != '1')
		{
			$data['user_defined'] = '0';
		}
		
		if(!isset($data['member_only']) || $data['member_only'] != '1')
		{
			$data['member_only'] = '0';
		}		
		return $this->EE->flag_master_options_model->add_option($data);
	}
	
	public function update_profile_option(array $data, array $where, $complete = TRUE)
	{
		$data['created_by'] = $this->EE->session->userdata['member_id'];
		if(!isset($data['user_defined']) || $data['user_defined'] != '1')
		{
			$data['user_defined'] = '0';
		}

		if(!isset($data['member_only']) || $data['member_only'] != '1')
		{
			$data['member_only'] = '0';
		}		
		
		return $this->EE->flag_master_options_model->update_option($data, $where, $complete);
	}
	
	public function update_profile_option_flag_count($option_id, $count = '1')
	{
		$table = $this->EE->db->dbprefix.$this->EE->flag_master_options_model->get_table();
		return $this->EE->db->query("UPDATE $table SET total_flags = total_flags+$count WHERE id='$option_id'");
	}	
	
	/**
	 * Removes a profile and all associated data
	 * @param int $profile_id
	 */
	public function delete_options(array $option_ids)
	{
		foreach($option_ids AS $option_id)
		{
			$this->EE->flag_master_options_model->delete_options(array('id' => $option_id));
		}
	
		return TRUE;
	}
}