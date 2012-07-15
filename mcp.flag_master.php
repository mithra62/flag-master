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
 * Flag Master - CP Class
 *
 * Control Panel class
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/mcp.flag_master.php
 */
class Flag_master_mcp 
{
	public $url_base = '';
	
	/**
	 * The amount of pagination items per page
	 * @var int
	 */
	public $perpage = 10;
	
	/**
	 * The delimiter for the datatables jquery
	 * @var stirng
	 */
	public $pipe_length = 1;
	
	/**
	 * The name of the module; used for links and whatnots
	 * @var string
	 */
	private $mod_name = '';
		
	public function __construct()
	{
		$this->EE =& get_instance();
		$path = dirname(realpath(__FILE__));
		include $path.'/config'.EXT;
		$this->class = $config['class_name'];
		$this->settings_table = $config['settings_table'];
		$this->version = $config['version'];
				
		$this->mod_name = $config['mod_url_name'];
		
		//load EE stuff
		$this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->load->helper('form');
		$this->EE->load->library('form_validation');

		$this->EE->load->helper('utilities');
		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');
		$this->EE->load->library('flag_master_js');
		$this->EE->load->library('channel_data');

		$this->settings = $this->EE->flag_master_lib->get_settings();		

		$this->query_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name.AMP.'method=';
		$this->url_base = BASE.AMP.$this->query_base;
		$this->EE->flag_master_lib->set_url_base($this->url_base);
		
		$this->EE->cp->set_variable('url_base', $this->url_base);
		$this->EE->cp->set_variable('query_base', $this->query_base);
		
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name, $this->EE->lang->line('flag_master_module_name'));
		$this->EE->cp->set_right_nav($this->EE->flag_master_lib->get_right_menu());	
		
		$this->errors = $this->EE->flag_master_lib->error_check();
		
		$this->EE->cp->set_variable('errors', $this->errors);
		$this->EE->cp->set_variable('settings', $this->settings);
		$this->EE->cp->set_variable('theme_folder_url', $this->EE->config->item('theme_folder_url'));
		$this->EE->cp->set_variable('statuses', $this->EE->flag_master_profiles_model->statuses);
		$this->EE->cp->set_variable('profile_types', $this->EE->flag_master_profiles_model->profile_types);

		$ignore_methods = array('profiles');
		$method = $this->EE->input->get('method', TRUE);
		if($this->settings['disable_accordions'] === FALSE && !in_array($method, $ignore_methods))
		{
			$this->EE->javascript->output($this->EE->flag_master_js->get_accordian_css());
		}		
	}
	
	public function index()
	{
		$this->EE->cp->add_js_script('ui', 'accordion');
		$this->EE->javascript->compile();		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('dashboard'));
		$vars = array();
		$vars['flagged_comments'] = array();
		$vars['flagged_entries'] = array();
		return $this->EE->load->view('index', $vars, TRUE);
	}
	
	public function profiles()
	{
		$this->EE->jquery->tablesorter('#profiles table', '{headers: {4: {sorter: false}}, widgets: ["zebra"], sortList: [[0,1]]}');
		$this->EE->javascript->compile();
				
		$vars = array();
		$vars['errors'] = $this->errors;	
		$vars['profiles'] = $this->EE->flag_master_profiles->get_profiles();
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('profiles'));
		return $this->EE->load->view('profiles', $vars, TRUE);
	}
	
	public function add_profile()
	{
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('add_profile'));
		$this->EE->form_validation->set_rules('name', 'Name', 'required');
		$proc_profile = $this->EE->input->get_post('go_profile_form', FALSE);
		if ($this->EE->form_validation->run() == TRUE)
		{
			$data = $_POST;
			$profile_id = $this->EE->flag_master_profiles->add_profile($data);
			if($profile_id)
			{
				$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('profile_added'));
				$this->EE->functions->redirect($this->url_base.'view_profile'.AMP.'profile_id='.$profile_id);
				exit;
			}
		}
		
		$this->EE->cp->add_js_script('ui', 'accordion');
		$this->EE->javascript->output($this->EE->flag_master_js->get_accordian_css());
		$this->EE->javascript->output($this->EE->flag_master_js->get_form_profile());
		 
		$this->EE->javascript->compile();
		
		$vars = array();
		$vars['profile_data'] = array();
		return $this->EE->load->view('add_profile', $vars, TRUE);		
	}
	
	public function view_profile()
	{
		$this->EE->cp->add_js_script('ui', 'accordion', 'sortable');
		$this->EE->javascript->output($this->EE->flag_master_js->get_sortable());
		$this->EE->javascript->compile();
		
		$vars = array();
		$profile_id = $this->EE->input->get('profile_id', FALSE);
		if(!$profile_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;			
		}
		
		$where = array('id' => $profile_id);
		$profile_data = $this->EE->flag_master_profiles->get_profile($where);
		if(!$profile_data)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		
		$where = array('profile_id' => $profile_id);
		$flag_options = $this->EE->flag_master_profile_options->get_profile_options($where);
		
		//$flagged_items = $this->EE->flag_master_flags->get_flags();
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('view_profile'));
		
		$vars['profile_data'] = $profile_data;
		$vars['flag_options'] = $flag_options;
		$vars['profile_id'] = $profile_id;
		return $this->EE->load->view('view_profile', $vars, TRUE);		
	}
	
	public function edit_profile()
	{
		$vars = array();
		$profile_id = $this->EE->input->get('profile_id', FALSE);
		if(!$profile_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		
		$profile_data = $this->EE->flag_master_profiles->get_profile(array('id' => $profile_id));
		if(!$profile_data)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}

		$this->EE->form_validation->set_rules('name', 'Name', 'required');
		if ($this->EE->form_validation->run() == TRUE)
		{	
			if($this->EE->flag_master_profiles->update_profile($_POST, array('id' => $profile_id)))
			{
				$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('profile_updated'));
				$this->EE->functions->redirect($this->url_base.'view_profile&profile_id='.$profile_id);
				exit;
			}
			else
			{
				$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_updated'));
				$this->EE->functions->redirect($this->url_base.'edit_profile&profile_id='.$profile_id);
				exit;
			}
		}		
				
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('edit_profile'));
		
		$vars['profile_id'] = $profile_id;
		$vars['profile_data'] = $profile_data;
		return $this->EE->load->view('edit_profile', $vars, TRUE);	
	}
	
	public function delete_profile_confirm()
	{
		$profile_ids = $this->EE->input->get_post('toggle', TRUE);
		if(!$profile_ids || count($profile_ids) == 0)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('no_profiles'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
	
		$profile_data = array();
		$ids = array();
		foreach($profile_ids AS $id)
		{
			$data = $this->EE->flag_master_profiles->get_profile(array('id' => $id));
			if(is_array($data) && count($data) != '0')
			{
				$profile_data[] = $data;
				$ids[] = $data['id'];
			}
		}

		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('delete_profiles_confirm'));
		$this->EE->cp->set_variable('delete_profiles_question', $this->EE->lang->line('delete_profiles_confirm'));
	
		$vars = array();
		$vars['form_action'] = $this->query_base.'delete_profiles';
		$vars['damned'] = $ids;
		$vars['data'] = $profile_data;
		return $this->EE->load->view('delete_profile_confirm', $vars, TRUE);
	}	
	
	public function delete_profiles()
	{
		$profile_ids = $this->EE->input->get_post('delete', FALSE);
		if($this->EE->flag_master_profiles->delete_profiles($profile_ids))
		{
			$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('profiles_deleted'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profiles_delete_failure'));
		$this->EE->functions->redirect($this->url_base.'profiles');
		exit;
	
	}	
	
	public function add_option()
	{
		$vars = array();
		$profile_id = $this->EE->input->get('profile_id', FALSE);
		if(!$profile_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		
		$profile_data = $this->EE->flag_master_profiles->get_profile(array('id' => $profile_id));
		if(!$profile_data)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}		
				
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('add_option'));
		$this->EE->form_validation->set_rules('title', 'Title', 'required');
		if ($this->EE->form_validation->run() == TRUE)
		{
			$data = $_POST;
			$option_id = $this->EE->flag_master_profile_options->add_profile_option($profile_id, $data);
			if($option_id)
			{
				$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('option_added'));
				$this->EE->functions->redirect($this->url_base.'view_profile'.AMP.'profile_id='.$profile_id);
				exit;
			}
		}
		
		$this->EE->cp->add_js_script('ui', 'accordion');
		$this->EE->javascript->output($this->EE->flag_master_js->get_accordian_css());
		$this->EE->javascript->output($this->EE->flag_master_js->get_form_profile());
		$this->EE->javascript->compile();
		
		$vars['option_data'] = array();
		$vars['profile_id'] = $profile_id;
		return $this->EE->load->view('add_option', $vars, TRUE);		
	}
	
	public function view_option()
	{
		$this->EE->cp->add_js_script('ui', 'accordion');
		$this->EE->javascript->compile();
		
		$vars = array();
		$option_id = $this->EE->input->get('option_id', FALSE);
		if(!$option_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('option_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		
		$where = array('id' => $option_id);
		$option_data = $this->EE->flag_master_profile_options->get_profile_option($where);
		if(!$option_data)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('option_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		
		$where = array('id' => $option_data['profile_id']);
		$profile_data = $this->EE->flag_master_profiles->get_profile($where);
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('view_option'));
				
		$vars['option_data'] = $option_data;
		$vars['profile_data'] = $profile_data;
		$vars['option_id'] = $option_id;
		$vars['profile_id'] = $option_data['profile_id'];
		$vars['child_options'] = array();
		return $this->EE->load->view('view_option', $vars, TRUE);		
	}
	
	public function order_options()
	{
		$option_ids = $this->EE->input->get_post('option_id', FALSE);	
		if(!$option_ids)
		{
			echo json_encode(array('status' => 'Failue'));
			exit;
		}
		
		$order = 0;
		foreach($option_ids AS $option_id)
		{
			$this->EE->flag_master_profile_options->update_option_order($option_id, $order);
			$order++;
		}
		
		echo json_encode(array('status' => 'Success'));
		exit;
	}
	
	public function view_entry_flags()
	{
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('view_entry_flags'));
		$entry_id = $this->EE->input->get_post('entry_id', FALSE);
		$option_id = $this->EE->input->get_post('option_id', FALSE);
		if(!$entry_id || !$option_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('no_options'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;			
		}
		
		$option_data = $this->EE->flag_master_flags->get_entry_flags(array('entry_id' => $entry_id, 'option_id' => $option_id));
		if(!$option_data || !isset($option_data['0']))
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('no_options'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;			
		}
		
		$entry_data = $this->EE->channel_data->get_entry(array('entry_id' => $entry_id));
		if(!$entry_data || !isset($entry_data['0']))
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('entry_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}		
		
		$where = array('option_id' => $option_id, 'entry_id' => $entry_id);
		$entry_flags = $this->EE->flag_master_flags->get_all_entry_flags($where, FALSE);
		
		$option_data = $option_data['0'];
		$entry_data = $entry_data['0'];
		
		$where = array('id' => $option_data['profile_id']);
		$profile_data = $this->EE->flag_master_profiles->get_profile($where);
		$vars = array();
		$vars['entry_view_url'] = '?D=cp&C=content_publish&M=entry_form&channel_id='.$entry_data['channel_id'].'&entry_id='.$entry_id;
		$vars['option_data'] = $option_data;
		$vars['profile_data'] = $profile_data;
		$vars['option_id'] = $option_id;
		$vars['entry_data'] = $entry_data;
		$vars['profile_id'] = $option_data['profile_id'];
		$vars['entry_flags'] = $entry_flags;
		
		$this->EE->cp->add_js_script('ui', 'accordion');
		$this->EE->javascript->compile();		
		return $this->EE->load->view('view_entry_flags', $vars, TRUE);	
	}
	
	public function delete_flags_confirm()
	{
		$flag_ids = $this->EE->input->get_post('toggle', TRUE);
		if(!$flag_ids|| count($flag_ids) == 0)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('no_profiles'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
	
		$flag_data = array();
		$ids = array();
		foreach($flag_ids AS $id)
		{
			$data = $this->EE->flag_master_flags->get_flags(array('fmf.id' => $id));
			if(is_array($data) && isset($data['0']))
			{
				$flag_data[] = $data['0'];
				$ids[] = $data['0']['flag_id'];
			}
		}
	
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('delete_flags_confirm'));
		$this->EE->cp->set_variable('delete_flags_question', $this->EE->lang->line('delete_flags_confirm'));
	
		$vars = array();
		$vars['form_action'] = $this->query_base.'delete_flags';
		$vars['damned'] = $ids;
		$vars['data'] = $flag_data;
		return $this->EE->load->view('delete_flags_confirm', $vars, TRUE);
	}
	
	public function delete_flags()
	{
		$flag_ids = $this->EE->input->get_post('delete', FALSE);
		if($this->EE->flag_master_flags->delete_flags($flag_ids))
		{
			$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('flags_deleted'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('flags_delete_failure'));
		$this->EE->functions->redirect($this->url_base.'profiles');
		exit;
	
	}	
	
	public function delete_option_confirm()
	{
		$option_ids = $this->EE->input->get_post('toggle', TRUE);
		if(!$option_ids || count($option_ids) == 0)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('no_options'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
	
		$option_data = array();
		$ids = array();
		foreach($option_ids AS $id)
		{
			$data = $this->EE->flag_master_profiles->get_profile_option(array('id' => $id));
			if(is_array($data) && count($data) != '0')
			{
				$option_data[] = $data;
				$ids[] = $data['id'];
			}
		}
	
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('delete_options_confirm'));
		$this->EE->cp->set_variable('delete_options_question', $this->EE->lang->line('delete_options_confirm'));
	
		$vars = array();
		$vars['form_action'] = $this->query_base.'delete_options';
		$vars['damned'] = $ids;
		$vars['data'] = $option_data;
		return $this->EE->load->view('delete_option_confirm', $vars, TRUE);
	}
	
	public function delete_options()
	{
		$profile_ids = $this->EE->input->get_post('delete', FALSE);
		if($this->EE->flag_master_profiles->delete_options($profile_ids))
		{
			$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('profiles_deleted'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
		$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profiles_delete_failure'));
		$this->EE->functions->redirect($this->url_base.'profiles');
		exit;
	
	}	
	
	public function edit_option()
	{
		$vars = array();
		$option_id = $this->EE->input->get('option_id', FALSE);
		if(!$option_id)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('option_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
	
		$option_data = $this->EE->flag_master_profile_options->get_profile_option(array('id' => $option_id));
		if(!$option_data)
		{
			$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('option_not_found'));
			$this->EE->functions->redirect($this->url_base.'profiles');
			exit;
		}
	
		$this->EE->form_validation->set_rules('title', 'Title', 'required');
		if ($this->EE->form_validation->run() == TRUE)
		{
			$data = $_POST;
			if($this->EE->flag_master_profile_options->update_profile_option($data, array('id' => $option_id)))
			{
				$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('profile_updated'));
				$this->EE->functions->redirect($this->url_base.'view_option&option_id='.$option_id);
				exit;
			}
			else
			{
				$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('profile_not_updated'));
				$this->EE->functions->redirect($this->url_base.'edit_profile&option_id='.$option_id);
				exit;
			}
		}
	
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('edit_option'));
	
		$vars['option_id'] = $option_id;
		$vars['option_data'] = $option_data;
		return $this->EE->load->view('edit_option', $vars, TRUE);
	}	
	
	public function settings()
	{
		if(isset($_POST['go_settings']))
		{				
			if($this->EE->flag_master_settings->update_settings($_POST))
			{	
				$this->EE->logger->log_action($this->EE->lang->line('log_settings_updated'));
				$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('settings_updated'));
				$this->EE->functions->redirect($this->url_base.'settings');		
				exit;			
			}
			else
			{
				$this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('settings_update_fail'));
				$this->EE->functions->redirect($this->url_base.'settings');	
				exit;					
			}
		}
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('settings'));
		
		$this->EE->cp->add_js_script('ui', 'accordion'); 
		$this->EE->javascript->compile();
		$vars['settings_disable'] = FALSE;
		if(isset($this->EE->config->config['ct_admin']))
		{
			$vars['settings_disable'] = 'disabled="disabled"';
		}	
			
		return $this->EE->load->view('settings', $vars, TRUE);
	}
}