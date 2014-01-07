<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - Flag Master
 *
 * @package		mithra62:Flag_master
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2012, mithra62, Eric Lamb.
 * @link		http://mithra62.com
 * @version		1.0
 * @filesource 	./system/expressionengine/third_party/flag_master/
 */
$config['name'] = 'Flag Master'; 
$config['class_name'] = 'Flag_master'; 
$config['settings_table'] = 'flag_master_settings';
$config['profiles_table'] = 'flag_master_profiles';
$config['profile_options_table'] = 'flag_master_profile_options';
$config['flags_table'] = 'flag_master_flags';
$config['description'] = 'Allows visitors to flag entries and inform administrators of innapropriate or special content.';

$config['mod_url_name'] = strtolower($config['class_name']);
$config['ext_class_name'] = $config['class_name'].'_ext';

$config['version'] = '1.1.1';
//$config['nsm_addon_updater']['versions_xml'] = 'http://mithra62.com/flag-master.xml';
$config['docs_url'] = 'http://mithra62.com/docs/flag-master';