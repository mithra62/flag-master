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
 * Flag Master - Flagging Library
 *
 * Library Class
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/libraries/flag_master_flags.php
 */
class Flag_master_flags
{
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->model('Flag_master_flags_model', 'flag_master_flags_model');
		$this->settings = $this->EE->flag_master_lib->get_settings();
	}
	
	/**
	 * Generic wrapper to pull flags 
	 * @param array $where
	 */
	public function get_flags(array $where)
	{
		$this->EE->db->select("*, fmf.id AS flag_id");
		$this->EE->db->from('flag_master_flags fmf');
		$this->EE->db->join('flag_master_profile_options fmpo', 'fmf.option_id = fmpo.id');
		
		foreach($where AS $key => $value)
		{
			$this->EE->db->where($key, $value);
		}	

		$data = $this->EE->db->get();
		return $data->result_array();		
	}
	
	/**
	 * Returns flags for entries
	 * @param array $where
	 * @param bool $group
	 */
	public function get_entry_flags(array $where, $group = TRUE)
	{
		$this->EE->db->select("fmpo.*, COUNT( fmpo.id ) AS total_flags, fmpo.user_defined AS option_user_defined, fmf.*, MIN(fmf.created_date) AS first_flag, MAX(fmf.created_date) AS last_flag, fmf.id AS flag_id");
		
		$this->EE->db->from('flag_master_flags fmf');
		$this->EE->db->join('flag_master_profiles fmp', 'fmf.profile_id = fmp.id');
		$this->EE->db->join('flag_master_profile_options fmpo', 'fmf.option_id = fmpo.id');
						
		foreach($where AS $key => $value)
		{
			$this->EE->db->where($key, $value);
		}
		
		if($group)
		{
			$this->EE->db->group_by('fmpo.id');
		}
		
		$this->EE->db->order_by('total_flags DESC');
		$data = $this->EE->db->get();
		return $data->result_array();
	}
	
	/**
	 * Returns meta information about an entries flags
	 * @param array $where
	 */
	public function get_entry_flag_meta(array $where = array())
	{
		$this->EE->db->select("COUNT(fmf.id) AS total_flags, MIN(fmf.created_date) AS first_flag, MAX(fmf.created_date) AS last_flag");
		$this->EE->db->from('flag_master_flags fmf');
		$this->EE->db->join('flag_master_profiles fmp', 'fmf.profile_id = fmp.id');
		foreach($where AS $key => $value)
		{
			$this->EE->db->where($key, $value);
		}		
		$data = $this->EE->db->get();
		$return = $data->result_array();
		if(isset($return['0']))
		{
			return $return['0'];
		}
	}
	
	/**
	 * Removes a profile and all associated data
	 * @param int $profile_id
	 */
	public function delete_flags(array $flag_ids)
	{
		foreach($flag_ids AS $flag_id)
		{
			$flag_data = $this->get_flags(array('fmf.id' => $flag_id));
			if($this->EE->flag_master_flags_model->delete_flags(array('id' => $flag_id)))
			{
				$flag_data = $flag_data['0'];
				$this->EE->flag_master_profiles->update_profile_flag_count($flag_data['profile_id'], '-1');
				$this->EE->flag_master_profile_options->update_profile_option_flag_count($flag_data['option_id'], '-1');
			}
		}
	
		return $flag_data;
	}	
	
	public function get_flagged_comments($limit = '10')
	{
		$this->EE->db->select("c.*, ct.title AS entry_title,  fmp.name AS profile_name, COUNT(fmf.id) AS total_flags, MIN(fmf.created_date) AS first_flag, MAX(fmf.created_date) AS last_flag");
		
		$this->EE->db->from('flag_master_profiles fmp');
		$this->EE->db->join('flag_master_flags fmf', 'fmf.profile_id = fmp.id');
		$this->EE->db->join('comments c', 'c.comment_id = fmf.entry_id');
		$this->EE->db->join('channel_titles ct', 'ct.entry_id = c.entry_id');
		
		$this->EE->db->where('fmp.type', 'comment');
		$this->EE->db->group_by('c.comment_id');
		$this->EE->db->limit($limit);
		$data = $this->EE->db->get();
		return $data->result_array();	
	}
	
	public function get_flagged_entries($limit = '10')
	{
		$this->EE->db->select("ct.title, ct.entry_id, fmp.name AS profile_name, COUNT(fmf.id) AS total_flags, MIN(fmf.created_date) AS first_flag, MAX(fmf.created_date) AS last_flag");
		
		$this->EE->db->from('flag_master_profiles fmp');
		$this->EE->db->join('flag_master_flags fmf', 'fmf.profile_id = fmp.id');
		$this->EE->db->join('channel_titles ct', 'ct.entry_id = fmf.entry_id');
		
		$this->EE->db->where('fmp.type', 'entry');
		$this->EE->db->group_by('ct.entry_id');
		$this->EE->db->limit($limit);
		$data = $this->EE->db->get();
		return $data->result_array();		
	}
	
	/**
	 * Returns every flag based on entries
	 * @param array $where
	 * @param bool $group
	 */
	public function get_all_entry_flags(array $where, $group = TRUE)
	{
		$this->EE->db->select("fmf.*, fmp.name AS profile_name, fmpo.title AS option_title, username, email, fmf.id AS flag_id");
		
		$this->EE->db->from('flag_master_flags fmf');
		$this->EE->db->join('flag_master_profiles fmp', 'fmf.profile_id = fmp.id');
		$this->EE->db->join('flag_master_profile_options fmpo', 'fmf.option_id = fmpo.id');
		$this->EE->db->join('members m', 'm.member_id = fmf.member_id', 'left');
						
		foreach($where AS $key => $value)
		{
			$this->EE->db->where($key, $value);
		}
		
		$data = $this->EE->db->get();
		return $data->result_array();
	}

	/**
	 * Processes the flagging of an "item"
	 * @param int $profile_id
	 * @param int $entry_id
	 * @param array $data
	 * @return boolean|string
	 */
	public function flag_item($profile_id, $entry_id, array $data)
	{
		if(!isset($data['option_id']))
		{
			return FALSE;
		}
		
		if($this->is_duplicate_flag($profile_id, $entry_id))
		{
			return FALSE;
		}
		
		$profile_data = $this->EE->flag_master_profiles->get_profile(array('id' => $profile_id));
		if(!$profile_data)
		{
			return lang('no_profile');
		}
		
		$option_data = $this->EE->flag_master_profile_options->get_profile_option(array('id' => $data['option_id']));
		$data['option_id'] = $data['option_id'];
		$data['entry_id'] = $entry_id;
		$data['profile_id'] = $profile_id;
		$data['member_id'] = (isset($this->EE->session->userdata['member_id']) && $this->EE->session->userdata['member_id'] != '' ? $this->EE->session->userdata['member_id'] : '0');
		$data['user_defined'] = '';
		if(isset($option_data['user_defined']) && $option_data['user_defined'] == '1')
		{
			$key = 'option_other_'.$data['option_id'];
			$data['user_defined'] = (isset($data[$key]) ? $data[$key] : $data['user_defined']);
		}

		if($this->EE->flag_master_flags_model->add_flag($data))
		{
			$this->EE->flag_master_profiles->update_profile_flag_count($profile_id, 1);
			$this->EE->flag_master_profile_options->update_profile_option_flag_count($data['option_id'], 1);
			$this->proc_session_tracking($entry_id, $data['option_id'], $profile_id);
			
			return TRUE;
		}
	}
	
	/**
	 * Wrapper to process the session tracker
	 * @param int $entry_id
	 * @param int $option_id
	 * @param int $profile_id
	 */
	public function proc_session_tracking($entry_id, $option_id, $profile_id)
	{
		$cookie = $this->EE->input->cookie('flag_master');
		if(!$cookie)
		{
			$tracker = array();
			$tracker[$profile_id][$entry_id] = array('profile_id' => $profile_id, 'option_id' => $option_id);
			$this->EE->functions->set_cookie('flag_master', base64_encode(serialize($tracker)), '0'); 
		}
		else 
		{
			$tracker = unserialize(base64_decode($cookie));
			$tracker[$profile_id][$entry_id] = array('profile_id' => $profile_id, 'option_id' => $option_id);
			$this->EE->functions->set_cookie('flag_master', base64_encode(serialize($tracker)), '0');
		}
	}
	
	/**
	 * Wrapper to check if a user has already flagged an item
	 * @param int $profile_id
	 * @param int $entry_id
	 * @return void|boolean
	 */
	public function is_duplicate_flag($profile_id, $item_id)
	{
		$taken = FALSE;
		//first check if the flag is in the session
		$cookie = $this->EE->input->cookie('flag_master');
		
		if($item_id == '')
		{
			return; 
		}

		if($cookie)
		{
			$cookie = @unserialize(base64_decode($cookie));
			if(isset($cookie[$profile_id]))
			{
				if(isset($cookie[$profile_id][$item_id]['profile_id']) && $cookie[$profile_id][$item_id]['profile_id'] == $profile_id)
				{
					$taken = TRUE;
				}
			}
		}
		
		//next check the flags table for the user (if logged in)
		if(!$taken)
		{
			if(isset($this->EE->session->userdata['member_id']) && $this->EE->session->userdata['member_id'] != '')
			{
				$where = array('profile_id' => $profile_id, 'member_id' => $this->EE->session->userdata['member_id'], 'entry_id' => $item_id);
				$check = $this->EE->flag_master_flags_model->get_flag($where);
				if(is_array($check) && count($check) >= '1')
				{
					$taken = TRUE;
				}
			}
		}
		
		return $taken;
		
	}
	
}