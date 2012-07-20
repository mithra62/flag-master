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
 * Flag Master - Module Class
 *
 * Module class
 *
 * @package 	mithra62:Flag_master
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/flag_master/mod.flag_master.php
 */
class Flag_master 
{
	
	/**
	 * The data to return from the module
	 * @var stirng
	 */
	public $return_data	= '';
	
	/**
	 * The delimeter to split template vars up with
	 * @var string
	 */
	public $delim = ':';

	/**
	 * The delimeter to split template vars up with
	 * @var string
	 */
	public $prefix = 'fm';	
	
	/**
	 * The number of results
	 * @var int
	 */
	public $limit = '20';
	
	/**
	 * Where to start the returned data
	 * @var int
	 */
	public $offset = '0';
	
	/**
	 * 
	 * @var unknown_type
	 */
	public $order = FALSE;
	
	public $paginate = FALSE;
	
	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
		$path = dirname(realpath(__FILE__));
		include $path.'/config'.EXT;
		$this->class = $config['class_name'];
		$this->settings_table = $config['settings_table'];
		$this->version = $config['version'];
				
		$this->EE->load->helper('utilities');
		$this->EE->load->helper('url');
		$this->EE->load->helper('form');
		$this->EE->load->library('flag_master_lib');
		$this->EE->load->library('flag_master_profiles');	
		$this->EE->lang->loadfile('flag_master');
		//$this->settings = $this->EE->ct_admin_lib->get_settings();
		
		if(isset($this->EE->TMPL))
		{
			$this->EE->meetup_lib->prefix = $this->prefix = $this->EE->TMPL->fetch_param('prefix', $this->prefix) . $this->delim;
			$this->per_page = $this->EE->TMPL->fetch_param('limit', '20');
			$this->offset = $this->EE->TMPL->fetch_param('offset', '0');
			$this->order = $this->EE->TMPL->fetch_param('order', FALSE);	
		}	
	}
	
	public function action_test()
	{
		
	}
	
	public function simple_form()
	{
		$form_id = $this->EE->TMPL->fetch_param('form_id', 'flag_master_form');
		$return = $this->EE->TMPL->fetch_param('return');
		$return_template = $this->EE->TMPL->fetch_param('return_template', FALSE);
		if($return_template)
		{
			$return = $return_template;
		}
		
		$profile_id = $this->EE->TMPL->fetch_param('profile_id');
		$entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$comment_id = $this->EE->TMPL->fetch_param('comment_id');
		if(!$profile_id)
		{
			return lang('profile_id_required');
		}
		
		$profile_data = $this->EE->flag_master_profiles->get_profile(array('id' => $profile_id, 'active' => '1'));
		if(!$profile_data)
		{
			return $this->EE->TMPL->no_results();
		}
		
		if($profile_data['type'] == 'comment')
		{
			$entry_id = $comment_id;
		}		
		
		$return_data['0'] = $profile_data;
		
		$duplicate_check = $this->EE->flag_master_flags->is_duplicate_flag($profile_id, $entry_id);
		$return_data['0']['is_duplicate'] = ($duplicate_check ? '1' : '0');
		$return_data['0'] = $this->prep_prefix($return_data['0']);
		$option_data = $this->EE->flag_master_profile_options->get_profile_options(array('profile_id' => $profile_id));
		$i=0;
		foreach($option_data AS $option)
		{
			if($option['member_only'] == '1' && $this->EE->session->userdata['member_id'] == '')
			{
				continue;
			}
			
			$temp = $option;
			$temp['js_id'] = 'option_'.$option['id'];
			$temp['option_id'] = $option['id'];
			$temp['option_title'] = $option['title'];
			
			$extra = 'id="'.$temp['js_id'].'"';
			if($option['user_defined'] == '1')
			{
				$extra .= ' rel="user_defined"';
			}			
			
			$temp['radio_button'] = form_radio('option_id', $option['id'], FALSE, $extra);
			$temp['checkbox'] = "<input type='checkbox' name='option_id' value='".$option['id']."' id='".$temp['js_id']."' />";
			
			if($option['user_defined'] == '1')
			{
				$temp['textarea'] = "<textarea name='option_other_".$option['id']."' id='option_other_".$option['id']."'></textarea>";
			}
			else 
			{
				$temp['textarea'] = '';
			}
			
			$return_data['0'][$this->prefix.'options'][] = $this->prep_prefix($temp);
			$i++;
		}
		
		if ($this->EE->input->post('fm_form') == $form_id)
		{	
			if($this->EE->flag_master_flags->flag_item($profile_id, $entry_id, $_POST))
			{
				//send email notification
				redirect('/'.$return);
			}
		}
		
		$output = $this->EE->functions->form_declaration(array(
				'id' => $form_id,
				'action' => '/'.$this->EE->uri->uri_string,
				'hidden_fields' => array(
						'fm_form' => $form_id,
						'return_url' => $this->EE->TMPL->fetch_param('return'),
				),
		));	

		//$output .= $this->EE->TMPL->parse_tags($profile_data);
		$output .= $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $return_data);
		$output .=  '</form>';

		return $output;
	}	
	
	/**
	 * Wrapper for all the TMPL and pagination stuff
	 * @param array $data
	 * @param array $meta
	 */
	private function prep_output($data, $meta = array())
	{
		//pagination stage 1
		if (preg_match("/".LD.$this->prefix."paginate".RD."(.+?)".LD.'\/'.$this->prefix."paginate".RD."/s", $this->EE->TMPL->tagdata, $match))
		{
			$this->paginate = TRUE;
			$this->paginate_data = $match['1'];
			$this->p_page = '';
			$this->EE->TMPL->tagdata = preg_replace("/".LD.$this->prefix."paginate".RD.".+?".LD.'\/'.$this->prefix."paginate".RD."/s", "", $this->EE->TMPL->tagdata);
			$this->basepath = $this->EE->functions->create_url($this->EE->uri->uri_string, 1);
			$paginate_prefix = $this->prefix;
			if ($this->EE->uri->query_string != '' && preg_match("#^".$paginate_prefix."(\d+)|/".$paginate_prefix."(\d+)#", $this->EE->uri->query_string, $match))
			{
				$this->p_page = (isset($match['2'])) ? $match['2'] : $match['1'];
				$this->basepath = $this->EE->functions->remove_double_slashes(str_replace($match['0'], '', $this->basepath));
			}
	
			$this->total_rows = $meta['total_count'];
			$this->p_limit  = ( ! $this->EE->TMPL->fetch_param('limit'))  ? 50 : $this->EE->TMPL->fetch_param('limit');
			$this->p_page = ($this->p_page == '' OR ($this->p_limit > 1 AND $this->p_page == 1)) ? 0 : $this->p_page;
				
			if ($this->p_page > $this->total_rows)
			{
				$this->p_page = 0;
			}
				
			$this->current_page = floor(($this->p_page / $this->p_limit) + 1);
				
			$this->total_pages = intval(floor($this->total_rows / $this->p_limit));
	
			if ($this->total_rows % $this->p_limit)
			{
				$this->total_pages++;
			}
	
			if ($this->total_rows > $this->p_limit)
			{
				if (strpos($this->basepath, SELF) === FALSE && $this->EE->config->item('site_index') != '')
				{
					$this->basepath .= SELF;
				}
					
				$this->basepath = rtrim($this->basepath,'/').'/';
					
				$config['anchor_class'] = $this->anchor_class;
				$config['base_url'] = $this->basepath;
				$config['prefix'] = $paginate_prefix;
				$config['use_page_numbers'] = TRUE;
				$config['total_rows'] = $this->total_rows;
				$config['per_page'] = $this->p_limit;
				$config['cur_page'] = $this->p_page;
				$config['first_link'] = $this->EE->lang->line('pag_first_link');
				$config['last_link'] = $this->EE->lang->line('pag_last_link');
	
				$this->EE->pagination->initialize($config);
				$this->pagination_links = $this->EE->pagination->create_links();
					
				if ((($this->total_pages * $this->p_limit) - $this->p_limit) > $this->p_page)
				{
					$this->page_next = $this->basepath.$paginate_prefix.($this->p_page + $this->p_limit);
				}
					
				if (($this->p_page - $this->p_limit ) >= 0)
				{
					$this->page_previous = $this->basepath.$paginate_prefix.($this->p_page - $this->p_limit);
				}
			}
			else
			{
				$this->p_page = '';
			}
				
		}
	
		//setup primary vars
		$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
	
		//pagination stage 2
		if ($this->paginate == TRUE)
		{
			$this->paginate_data = str_replace(LD.$this->prefix.'current_page'.RD, $this->current_page, $this->paginate_data);
			$this->paginate_data = str_replace(LD.$this->prefix.'total_pages'.RD,	$this->total_pages, $this->paginate_data);
			$this->paginate_data = str_replace(LD.$this->prefix.'pagination_links'.RD, $this->pagination_links, $this->paginate_data);
	
			if (preg_match("/".LD."if ".$this->prefix."previous_page".RD."(.+?)".LD.'\/'."if".RD."/s", $this->paginate_data, $match))
			{
				if ($this->page_previous == '')
				{
					$this->paginate_data = preg_replace("/".LD."if ".$this->prefix."previous_page".RD.".+?".LD.'\/'."if".RD."/s", '', $this->paginate_data);
				}
				else
				{
					$match['1'] = preg_replace("/".LD.'path.*?'.RD."/", 	$this->page_previous, $match['1']);
					$match['1'] = preg_replace("/".LD.'auto_path'.RD."/",	$this->page_previous, $match['1']);
	
					$this->paginate_data = str_replace($match['0'],	$match['1'], $this->paginate_data);
				}
			}
	
			if (preg_match("/".LD."if ".$this->prefix."next_page".RD."(.+?)".LD.'\/'."if".RD."/s", $this->paginate_data, $match))
			{
				if ($this->page_next == '')
				{
					$this->paginate_data = preg_replace("/".LD."if ".$this->prefix."next_page".RD.".+?".LD.'\/'."if".RD."/s", '', $this->paginate_data);
				}
				else
				{
					$match['1'] = preg_replace("/".LD.'path.*?'.RD."/", 	$this->page_next, $match['1']);
					$match['1'] = preg_replace("/".LD.'auto_path'.RD."/",	$this->page_next, $match['1']);
	
					$this->paginate_data = str_replace($match['0'],	$match['1'], $this->paginate_data);
				}
			}
	
			$position = ( ! $this->EE->TMPL->fetch_param('paginate')) ? '' : $this->EE->TMPL->fetch_param('paginate');
	
			switch ($position)
			{
				case "top":
					$output  = $this->paginate_data.$output;
					break;
				case "both":
					$output  = $this->paginate_data.$output.$this->paginate_data;
					break;
				default:
					$output .= $this->paginate_data;
					break;
			}
		}
	
		//setup meta vars
	
		return $output;
	}	
	
	/**
	 * Preps the TMPL variables to contain the prefix
	 * @param array $data
	 * @return multitype:array NULL
	 */
	private function prep_prefix($data)
	{
		$return = array();
		foreach($data AS $key => $value)
		{
			if(is_array($value))
			{
				if(!is_numeric($key))
				{
					$key = $this->prefix.$key;
				}
				$return[$key] = $this->prep_prefix($value);
			}
			else
			{
				$return[$this->prefix.$key] = $value;
			}
		}
	
		return $return;
	}	
}