<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - Flag Master
 *
 * @package		mithra62:Flag_master
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/automat-ee/
 * @version		1.2
 * @filesource 	./system/expressionengine/third_party/flag_master/
 */
 
 /**
 * Flag Master - Profile Model
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/models/flag_master_profiles_model.php
 */
class Flag_master_profiles_model extends CI_Model
{
	/**
	 * Name of the profile table
	 * @var string
	 */	
	private $_table = '';
	
	/**
	 * The various types of statuses for profiles
	 * @var array
	 */
	public $statuses = array(
							 '0' => 'inactive',
							 '1' => 'active'
	);
	
	/**
	 * The profile types
	 * @var array
	 */
	public $profile_types = array(
							'entry' => 'entry',
							'comment' => 'comment'			
	);
	
	public function __construct()
	{
		parent::__construct();
		$path = dirname(realpath(__FILE__));
		include $path.'/../config'.EXT;
		$this->_table = $config['profiles_table'];
		$this->statuses = $this->_set_lang($this->statuses);
		$this->profile_types = $this->_set_lang($this->profile_types);
	}
	
	private function get_sql(array $profile)
	{
		return $data = array(
		   'name' => $profile['name'],
		   'type' => $profile['type'],
		   'active' => $profile['active'],
		   'notify_emails' => $profile['notify_emails'],
		   'last_modified' => date('Y-m-d H:i:s')
		);
	}
	
	public function get_table()
	{
		return $this->_table;
	}
	
	public function _set_lang($arr)
	{
		foreach($arr AS $key => $value)
		{
			$arr[$key] = lang($value);
		}
		return $arr;
	}
	
	/**
	 * Adds a profile to the databse
	 * @param string $cron
	 */
	public function add_profile(array $profile)
	{
		$data = $this->get_sql($profile);
		$data['created_date'] = date('Y-m-d H:i:s');
		if($this->db->insert($this->_table, $data))
		{
			return $this->db->insert_id();
		}
	}	
	
	public function get_profiles(array $where = array())
	{
		foreach($where AS $key => $value)
		{
			$this->db->where($key, $value);
		}
		
		$query = $this->db->get($this->_table);
		$data = $query->result_array();
		return $data;
	}
	
	/**
	 * Returns the value straigt from the database
	 * @param string $setting
	 */
	public function get_profile(array $where)
	{
		$data = $this->db->get_where($this->_table, $where)->result_array();
		if($data)
		{
			return $data['0'];
		}
	}	
	
	public function update_profiles(array $data, $where)
	{
		foreach($data AS $key => $value)
		{	
			$this->update_profile($data, $where);
		}
		
		return TRUE;
	}
	
	/**
	 * Updates a profile
	 * @param string $key
	 * @param string $value
	 */
	public function update_profile($data, $where, $complete = TRUE)
	{
		if($complete)
		{
			$data = $this->get_sql($data);
		}
		
		return $this->db->update($this->_table, $data, $where);
	}
	
	public function delete_profiles(array $where)
	{
		return $this->db->delete($this->_table, $where);	
	}
}