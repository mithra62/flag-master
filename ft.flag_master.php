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
 * Flag Master - Fieldtype Class
 *
 * Fieldtype class
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/ft.flag_master.php
 */
class Flag_master_ft extends EE_Fieldtype
{
	public $info = array(
		'name'		=> 'Flag Master',
		'version'	=> '1.2'
	);

	public $flag_types = array(
		'entry' => 'Entry', 
		'comment' => 'Comment'
	);
	
	public $zenbu_data = array();
		
	public function __construct()
	{
		
		$path = dirname(realpath(__FILE__));
		include $path.'/config'.EXT;
		$this->info['version'] = $config['version'];
		$this->mod_name = $config['mod_url_name'];
					
		if (version_compare(APP_VER, '2.1.4', '>'))
		{
			parent::__construct();
		}
		else
		{
			parent::EE_Fieldtype();
		}

		$this->EE->load->add_package_path(PATH_THIRD.'flag_master/');
		$this->EE->load->helper('utilities');
		$this->EE->load->helper('text');
		$this->EE->lang->loadfile('flag_master');
		$this->query_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name;
		if(defined('BASE'))
			$this->url_base = BASE.AMP.$this->query_base;		
		
		$this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->load->model('field_model');
		$this->EE->load->helper('form');
		$this->EE->load->remove_package_path();

	}		

	public function display_field($data)
	{
		$this->EE->load->helper('utilities');
		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');
		$this->EE->load->library('flag_master_js');
		
		//$fm_settings = $this->EE->flag_master_lib->get_settings();
		
		
		if(isset($this->settings['field_settings']))
		{
			$field_settings = unserialize(base64_decode($this->settings['field_settings']));
			if(count($field_settings) == '0')
			{
				$field_settings = $this->settings;
			}
		}
		else
		{
			$field_settings = $this->settings;
		}
		
		$this->query_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name.AMP.'method=';
		$this->url_base = BASE.AMP.$this->query_base;
		$this->EE->flag_master_lib->set_url_base($this->url_base);
		
		$this->EE->cp->set_variable('url_base', $this->url_base);
		$this->EE->cp->set_variable('query_base', $this->query_base);
		
		$this->EE->jquery->tablesorter('#flag_master_ft_'.$field_settings['flag_type'].' table', '{headers: {5: {sorter: false}}, widgets: ["zebra"], sortList: [[0,1]]}');
		$this->EE->javascript->compile();
		
		$entry_id = $this->EE->input->get('entry_id');
		
		if($field_settings['flag_type'] == 'comment')
		{
			$flags = $this->EE->flag_master_flags->get_flagged_comments(array('c.entry_id' => $entry_id));
		}
		else
		{
			$flags = $this->EE->flag_master_flags->get_entry_flags(array('entry_id' => $entry_id));
		}
		
		$vars['flagged_entries'] = $flags;
		$vars['field_settings'] = $field_settings;
		$vars['field_id'] = $this->settings['field_id'];
		$vars['field_value'] = ($data == '' ? '0' : $data);
		return $this->EE->load->view('ft', $vars, TRUE);

	}

	public function display_settings($data)
	{
		$selected = (!isset($data['flag_type']) || $data['flag_type'] == '') ? FALSE : $data['flag_type'];
		$this->EE->table->add_row(
				'<strong>'.lang('flag_type').'</strong><div class="subtext">'.lang('flag_type_instructions').'</div>',
				form_dropdown('flag_type', $this->flag_types, $selected)
		);		
	}
	
	function save_settings($data)
	{
		return array(
			'flag_type'		=> $this->EE->input->post('flag_type')
		);
	}	
	
	public function install()
	{
		return array(
			'flag_type' => 'entry',
		);
	}	
	
	public function save($data)
	{
		return $data;
	}

	public function zenbu_display($entry_id, $channel_id, $data, $table_data, $field_id, $settings, $rules, $upload_prefs, $installed_addons)
	{
		if($data == '')
		{
			$data = '0';
		}
		
		$type = 'entry';
		$url = $data;
		if(isset($table_data[$field_id]['field_data']['field_settings']))
		{
			$settings = unserialize(base64_decode($table_data[$field_id]['field_data']['field_settings']));
			$type = (isset($settings['flag_type']) ? $settings['flag_type'] : $type);
		}
		
		switch($type)
		{
			case 'entry':
				$url = '<a href="'.$this->url_base.AMP.'method=view_entry_flags'.AMP.'entry_id='.$entry_id.'">'.lang('view').' ('.$data.')'.'</a>';
			break;
				
			case 'comment':
				$url = '<a href="'.$this->url_base.AMP.'method=view_entry_comment_flags'.AMP.'entry_id='.$entry_id.'">'.lang('view').' ('.$data.')'.'</a>';
			break;
		}
		
		return $url;
	}
	
	public function zenbu_get_table_data($entry_ids, $field_ids, $channel_id, $output_upload_prefs, $settings, $rel_array)
	{
		$total = count($entry_ids);
		foreach($field_ids AS $key => $value)
		{
			$data[$value]['field_data'] = $this->EE->field_model->get_field($value)->row_array();
		}
		
		return $data;
	}

	public function unsinstall(){}
	
	public function pre_process($data){}

	public function replace_tag($data, $params = FALSE, $tagdata = FALSE){
		
	}

	public function post_save($data){}

	public function validate($data)
	{
		return TRUE;
	}
}
