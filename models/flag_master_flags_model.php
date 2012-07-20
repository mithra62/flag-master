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
 * Flag Master - Flags Model
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/models/flag_master_flags_model.php
 */
class Flag_master_flags_model extends CI_Model
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
		$this->_table = $config['flags_table'];
	}
	
	private function get_sql(array $flag)
	{
		return $data = array(
		   'entry_id' => $flag['entry_id'],
		   'profile_id' => $flag['profile_id'],
		   'option_id' => $flag['option_id'],
		   'ip_address' => (isset($flag['ip_address']) ? $flag['ip_address'] : $this->input->ip_address()),
		   'member_id' => (isset($flag['member_id']) ? $flag['member_id'] : '0'),
		   'user_defined' => (isset($flag['user_defined']) ? $flag['user_defined'] : ''),
		   'last_modified' => date('Y-m-d H:i:s')
		);
	}
	
	public function _set_lang($arr)
	{
		foreach($arr AS $key => $value)
		{
			$arr[$key] = lang($value);
		}
		return $arr;
	}
	
	public function get_table()
	{
		return $this->_table;
	}	
	
	/**
	 * Adds a option to the databse
	 * @param string $flag
	 */
	public function add_flag(array $flag)
	{
		$this->load->library('user_agent');
		$data = $this->get_sql($flag);
		$data['created_date'] = date('Y-m-d H:i:s');	
		$data['user_agent'] = $this->agent->agent;
		return $this->db->insert($this->_table, $data); 
	}	
	
	public function get_flags(array $where = array())
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
	public function get_flag(array $where)
	{
		$data = $this->db->get_where($this->_table, $where)->result_array();
		if($data)
		{
			return $data['0'];
		}
	}	
	
	public function update_flags(array $data, $where)
	{
		foreach($data AS $key => $value)
		{	
			$this->update_flag($data, $where);
		}
		
		return TRUE;
	}
	
	/**
	 * Updates a flag
	 * @param string $key
	 * @param string $value
	 */
	public function update_flag($data, $where, $complete = TRUE)
	{
		if($complete)
		{
			$data = $this->get_sql($data);
		}
		
		return $this->db->update($this->_table, $data, $where);
	}
	
	public function delete_flags(array $where)
	{
		return $this->db->delete($this->_table, $where);	
	}
}