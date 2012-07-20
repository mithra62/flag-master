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
class Flag_master_profiles
{
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->model('Flag_master_profiles_model', 'flag_master_profiles_model');
		$this->EE->load->library('Flag_master_profile_options');
		$this->EE->load->library('Flag_master_flags');
		$this->settings = $this->EE->flag_master_lib->get_settings();
	}
	
	/**
	 * Returns a multidimensional array of multiple profiles
	 * @param array $where
	 */
	public function get_profiles(array $where = array())
	{
		return $this->EE->flag_master_profiles_model->get_profiles($where);
	}
	
	/**
	 * Returns a single array of a profile
	 * @param array $where
	 */
	public function get_profile(array $where = array())
	{
		$profile_data = $this->EE->flag_master_profiles_model->get_profile($where);
		if($profile_data)
		{
			$profile_data['profile_name'] = $profile_data['name'];
			$profile_data['profile_id'] = $profile_data['id'];
			return $profile_data;
		}
	}
	
	/**
	 * Creates a new profile
	 * @param array $data
	 */
	public function add_profile(array $data)
	{
		$data['total_flags'] = '0';
		return $this->EE->flag_master_profiles_model->add_profile($data);
	}	
	
	/**
	 * Removes a profile and all associated data
	 * @param int $profile_id
	 */
	public function delete_profiles(array $profile_ids)
	{
		foreach($profile_ids AS $profile_id)
		{
			if($this->EE->flag_master_profiles_model->delete_profiles(array('id' => $profile_id)))
			{
				$this->EE->flag_master_options_model->delete_options(array('profile_id' => $profile_id));
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Updates a profile
	 * @param int $profile_id
	 * @param array $data
	 */
	public function update_profile(array $data, array $where, $complete = TRUE)
	{
		return $this->EE->flag_master_profiles_model->update_profile($data, $where, $complete);
	}
	
	public function update_profile_flag_count($profile_id, $count = '1')
	{
		$table = $this->EE->db->dbprefix.$this->EE->flag_master_profiles_model->get_table();
		return $this->EE->db->query("UPDATE $table SET total_flags = total_flags+$count WHERE id='$profile_id'");
	}
}