<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - CT Admin
 *
 * @package		mithra62:Ct_admin
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/ct-admin/
 * @since		1.4.1
 * @filesource 	./system/expressionengine/third_party/ct_admin/
 */
 
 /**
 * CT Admin - Member Data Class
 *
 * Contains all the methods for interacting with the EE member system
 *
 * @package 	mithra62:Ct_admin
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/ct_admin/libraries/Member_data.php
 */
class Member_data
{
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->model('member_model');
	}

	public function register_update($data)
	{
		//get user
		$user = $this->EE->db->get_where('members', array('email' => $data['email']));
		$custom = $this->_generate_custom_fields($data);
		$data = $this->_generate_member_fields($data);	
		if($user->num_rows == '0')
		{
			return $this->EE->member_model->create_member($data, $custom);
		}
		else
		{
			$user_data = $user->row();			
			$this->EE->member_model->update_member($user_data->member_id, $data);	
			$this->EE->member_model->update_member_data($user_data->member_id, $custom);	
			return $user_data->member_id;	
		}
	}
	
	public function get_members($where = FALSE, $include_custom_fields = FALSE, $complete = FALSE, $limit = FALSE, $page = '0', $order = 'member_id ASC')
	{
		if($complete)
		{
			$this->EE->db->select("members.*, member_groups.group_title");
		}
		else
		{
			$this->EE->db->select("members.username, members.member_id, members.screen_name, members.email, members.join_date, members.last_visit, members.group_id, members.member_id, members.in_authorlist, member_groups.group_title");
		}
		
		$this->EE->db->from('members');

		if(is_array($where) && count($where) >= '1')
		{
			foreach($where AS $key => $value)
			{
				$this->EE->db->where($key, $value);
			}
		}
		elseif($where)
		{
			$this->EE->db->where($where);
		}
		
		$this->EE->db->join('member_groups', 'member_groups.group_id = members.group_id');
		$this->EE->db->join('member_data', 'member_data.member_id = members.member_id');
		
		if($include_custom_fields)
		{
			$this->EE->db->select('member_data.*');
		}
		
		if($limit)
		{
			$this->EE->db->limit($limit, $page);
		}
		
		if($order)
		{
			$this->EE->db->order_by($order);
		}
		
		$data = $this->EE->db->get();
		$users = $data->result_array();
		
		if($include_custom_fields)
		{
			$users = $this->_parse_custom_fields($users);
		}
		return $users;
	}
	
	public function get_total_members($where = FALSE)
	{
		if(is_array($where) && count($where) >= '1')
		{
			foreach($where AS $key => $value)
			{
				$this->EE->db->where($key, $value);
			}
		}
		elseif($where)
		{
			$this->EE->db->where($where);
		}
		
		$this->EE->db->join('member_data', 'member_data.member_id = members.member_id');
		$data = $this->EE->db->count_all_results('members');
		return $data;	
	}
	
	public function get_first_date($where = FALSE)
	{
		if(is_array($where) && count($where) >= '1')
		{
			foreach($where AS $key => $value)
			{
				$this->EE->db->where($key, $value);
			}
		}
		elseif($where)
		{
			$this->EE->db->where($where);
		}	
		
		$this->EE->db->select_min('join_date');	
		$this->EE->db->join('member_data', 'member_data.member_id = members.member_id');
		$data = $this->EE->db->get('members');
		return $data->row('join_date');
	}	
	
	public function get_member($member_id = '', $include_custom_fields = FALSE, $complete = FALSE)
	{
		if($complete)
		{
			$this->EE->db->select("members.*");
		}
		else
		{
			$this->EE->db->select("members.username, members.member_id, members.screen_name, members.email, members.join_date, members.last_visit, members.group_id, members.member_id, members.in_authorlist");
		}
		
		$this->EE->db->from('members');
		if($member_id && $member_id != '')
		{
			$this->EE->db->where('members.member_id', $member_id);
		}
		
		if($include_custom_fields)
		{
			$this->EE->db->select('member_data.*');
			$this->EE->db->join('member_data', 'member_data.member_id = members.member_id');
		}
		
		$data = $this->EE->db->get();
		$users = $data->result_array();
		
		if($include_custom_fields)
		{
			$users = $this->_parse_custom_fields($users);
		}
		return $users;
	}
	
	public function get_member_by_email()
	{
		
	}

	private function _parse_custom_fields($users)
	{
		$remove = array('salt', 'unique_id', 'crypt_key', 'show_sidebar');
		$fields = $this->EE->member_model->get_custom_member_fields()->result_array();
		foreach($users AS $key => $user)
		{
			foreach($remove AS $kill)
			{
				if(in_array($key, $remove))
				{
					unset($users[$key][$kill]);
					continue;
				}
			}
			foreach($fields AS $field)
			{
				if(array_key_exists('m_field_id_'.$field['m_field_id'], $user))
				{
					$users[$key][$field['m_field_name']] = $user['m_field_id_'.$field['m_field_id']];
					unset($users[$key]['m_field_id_'.$field['m_field_id']]);
				}
			}
		}
		return $users;	
	}
	
	private function _generate_custom_fields($data)
	{
		//get custom fields
		$fields = $this->EE->member_model->get_custom_member_fields()->result_array();
		$arr = array();
		foreach($fields AS $field)
		{
			if(array_key_exists($field['m_field_name'], $data))
			{
				$arr['m_field_id_'.$field['m_field_id']] = $data[$field['m_field_name']];
			}
		}
		return $arr;
	}
	
	public function get_group_id(array $where)
	{
		$groups = $this->EE->member_model->get_member_groups(FALSE, $where);
		if($groups->num_rows == '1')
		{
			$data = $groups->row();
			return $data->group_id;
		}	
	}
	
	private function _generate_member_fields($data)
	{
		$group_id = (isset($data['group_id']) ? $data['group_id'] : $this->get_group_id(array('group_title' => $data['access'])));
		$data = array(
			'username'		=> $data['username'],
			'password'		=> $this->EE->functions->hash($data['password']),
			'ip_address'	=> $this->EE->input->ip_address(),
			'unique_id'		=> $this->EE->functions->random('encrypt'),
			'join_date'		=> $this->EE->localize->now,
			'email'			=> $data['email'],
			'group_id'		=> $group_id,
			'screen_name'	=> $data['username'],

			// overridden below if used as optional fields
			'language'		=> ($this->EE->config->item('deft_lang')) ?
									$this->EE->config->item('deft_lang') : 'english',
			'time_format'	=> ($this->EE->config->item('time_format')) ?
									$this->EE->config->item('time_format') : 'us',
			'timezone'		=> ($this->EE->config->item('default_site_timezone') &&
								$this->EE->config->item('default_site_timezone') != '') ?
									$this->EE->config->item('default_site_timezone') : $this->EE->config->item('server_timezone'),
			'daylight_savings' => ($this->EE->config->item('default_site_dst') &&
									$this->EE->config->item('default_site_dst') != '') ?
										$this->EE->config->item('default_site_dst') : $this->EE->config->item('daylight_savings')
		);

		return $data;
	}
	
	public function get_member_groups()
	{
		$groups = $this->EE->member_model->get_member_groups(FALSE)->result_array();
		$arr = array();
		$arr['0'] = 'All';
		foreach($groups AS $group)
		{
			$arr[$group['group_id']] = $group['group_title'];
		}
		
		return $arr;
	}
}