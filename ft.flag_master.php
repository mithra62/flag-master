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
		'version'	=> '1.0'
	);

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
		$this->EE->lang->loadfile('flag_master');
		
		$this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->load->helper('form');	
	}	

	public function install(){}
	
	public function unsinstall(){}	

	public function display_field($data)
	{
		$this->EE->load->helper('utilities');
		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');
		$this->EE->load->library('flag_master_js');
		
		$this->settings = $this->EE->flag_master_lib->get_settings();
		
		$this->query_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name.AMP.'method=';
		$this->url_base = BASE.AMP.$this->query_base;
		$this->EE->flag_master_lib->set_url_base($this->url_base);
		
		$this->EE->cp->set_variable('url_base', $this->url_base);
		$this->EE->cp->set_variable('query_base', $this->query_base);
		
		$this->EE->jquery->tablesorter('#flag_master_ft table', '{headers: {5: {sorter: false}}, widgets: ["zebra"], sortList: [[0,1]]}');
		$this->EE->javascript->compile();
		
		$entry_id = $this->EE->input->get('entry_id');
		$flags = $this->EE->flag_master_flags->get_entry_flags(array('entry_id' => $entry_id));
		
		$vars['flagged_entries'] = $flags;
		return $this->EE->load->view('ft', $vars, TRUE);

	}

	public function pre_process($data){}

	public function replace_tag($data, $params = FALSE, $tagdata = FALSE){}

	public function save($data){}

	public function post_save($data){}

	public function validate($data)
	{
		return TRUE;
	}
	public function display_settings($data){}

	public function save_settings($data){}
}