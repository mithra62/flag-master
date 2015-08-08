<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - EE Add-on Stub
 *
 * @package		mithra62:EE_addon_stub
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://blah.com
 * @version		1.0
 * @filesource 	./system/expressionengine/third_party/ee_addon_stub/
 */
 
 /**
 * EE Add-on Stub - Ext Class
 *
 * Extension class
 *
 * @package 	mithra62:Ee_addon_stub
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/ee_addon_stub/ext.flag_master.php
 */
class Flag_master_ext 
{	
	public $settings = array();
	
	public $description	= '';
	
	public $settings_exist	= 'y';
	
	public $docs_url = ''; 
	
	public $required_by = array('module');	
		
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		$path = dirname(realpath(__FILE__));
		include $path.'/config'.EXT;
		$this->description = $config['description'];
		$this->docs_url = $config['docs_url'];
		$this->class = $this->name = $config['class_name'];
		$this->settings_table = $config['settings_table'];
		$this->version = $config['version'];
		$this->mod_name = $config['mod_url_name'];
		$this->ext_class_name = $config['ext_class_name'];
		
		$this->EE->lang->loadfile('flag_master');		
	}
	
	public function settings_form()
	{
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->mod_name.AMP.'method=settings');
	}
	
	/**
	 * Adds additional template tags to the comment template tag
	 * @param string $tagdata
	 * @param array $row
	 * @return string
	 */
	public function comment_entries_tagdata($tagdata, $row)
	{
		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');
		
		$total = (string)$this->EE->flag_master_flags->get_total_flags($row['comment_id'], 'comment');
		$tagdata = $this->EE->TMPL->swap_var_single('flag_master:comment_total_flags', $total, $tagdata);
		return $tagdata;
	}
	
	/**
	 * Adds additional template tags to the entry template tag
	 * @param string $tagdata
	 * @param array $row
	 * @return string
	 */
	public function channel_entries_tagdata($tagdata, $row)
	{

		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');
				
		$total = (string)$this->EE->flag_master_flags->get_total_flags($row['entry_id'], 'entry');
		$tagdata = $this->EE->TMPL->swap_var_single('flag_master:entry_total_flags', $total, $tagdata);
		return $tagdata;		
	}
	
	public function void()
	{
		
	}
	
	public function activate_extension() 
	{
		return TRUE;
	}
	
	public function update_extension($current = '')
	{
		return TRUE;
	}

	public function disable_extension()
	{
		return TRUE;

	}
}