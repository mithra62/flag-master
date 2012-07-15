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
 * Flag Master - Profile Options Model
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/models/flag_master_profile_options_model.php
 */
class Flag_master_profile_options_model extends CI_Model
{
	/**
	 * Name of the profile table
	 * @var string
	 */	
	private $_table = '';
	
	public function __construct()
	{
		parent::__construct();
		$path = dirname(realpath(__FILE__));
		include $path.'/../config'.EXT;
		$this->_table = $config['profile_options_table'];
	}
	
	private function get_sql(array $profile)
	{
		return $data = array(
		   'title' => $profile['title'],
		   'description' => $profile['description'],
		   'profile_id' => $profile['profile_id'],
		   'user_defined' => $profile['user_defined'],
		   'member_only' => $profile['member_only'],
		   'created_by' => $profile['created_by'],
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
	 * Adds a option to the databse
	 * @param string $cron
	 */
	public function add_option(array $profile)
	{
		$data = $this->get_sql($profile);
		$data['created_date'] = date('Y-m-d H:i:s');
		
		return $this->db->insert($this->_table, $data); 
	}	
	
	public function get_options(array $where = array(), $order = FALSE)
	{
		foreach($where AS $key => $value)
		{
			$this->db->where($key, $value);
		}
		
		if(!$order)
		{
			$this->db->order_by('sort_order asc');
		}
		else
		{
			$this->db->order_by($order);
		}
		$query = $this->db->get($this->_table);
		$data = $query->result_array();
		return $data;
	}
	
	/**
	 * Returns the value straigt from the database
	 * @param string $setting
	 */
	public function get_option(array $where)
	{
		$data = $this->db->get_where($this->_table, $where)->result_array();
		if($data)
		{
			return $data['0'];
		}
	}	
	
	public function update_options(array $data, $where)
	{
		foreach($data AS $key => $value)
		{	
			$this->update_profile($data, $where);
		}
		
		return TRUE;
	}
	
	/**
	 * Updates a option
	 * @param string $key
	 * @param string $value
	 */
	public function update_option($data, $where, $complete = TRUE)
	{
		if($complete)
		{
			$data = $this->get_sql($data);
		}
		
		return $this->db->update($this->_table, $data, $where);
	}
	
	public function delete_options(array $where)
	{
		return $this->db->delete($this->_table, $where);	
	}
}