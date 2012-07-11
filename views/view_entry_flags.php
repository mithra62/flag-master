<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_profile&profile_id='.$profile_id; ?>" ><?php echo lang('back_to_profile')?></a>
		</span>		
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_option&option_id='.$option_id; ?>" ><?php echo lang('back_to_option')?></a>
		</span>						
	</div>
</div>

<br clear="all" />
<div>
<?php 
$this->table->set_heading(lang('entry_title'), lang('option_title'), lang('profile'), lang('total_flags'), lang('first_flag'), lang('last_flag'));
$data = array(
		'<a href="'.$entry_view_url.'">'.$entry_data['title'].'</a>', 
		$option_data['title'],
		$profile_data['name'], 
		$option_data['total_flags'],
		m62_convert_timestamp($option_data['first_flag']),
		m62_convert_timestamp($option_data['last_flag'])
);
$this->table->add_row($data);
echo $this->table->generate();
$this->table->clear();
?>
</div>

<div id="my_accordion">
<h3  class="accordion">Flags</h3>
<div id="all_flags">
	<?php 
	
	if(count($entry_flags) > 0): 

		echo form_open($query_base.'delete_flags_confirm', array('id'=>'flag_options'));
		$this->table->clear();
		$this->table->set_template($cp_pad_table_template);
		$this->table->set_heading(
			lang('option_title'),
			lang('user_name'),
			lang('ip_address'),
			lang('created_date'),
			form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_files" id="select_all"').NBS.lang('delete', 'select_all')
		);
		
		foreach($entry_flags as $flag)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$flag['flag_id'],
					  'value'		=> $flag['flag_id'],
					  'class'		=>'toggle_files'
					  );
		
			$this->table->add_row(
									'<a href="'.$url_base.'view_option'.AMP.'option_id='.$flag['id'].'">'.$flag['option_title'].'</a>',
									(isset($flag['username']) ? '<a href="?D=cp&C=myaccount&id='.$flag['member_id'].'">'.$flag['username'].'</a>' : 'N/A'),
									'<a href="http://whatismyipaddress.com/ip/'.$flag['ip_address'].'" target="_blank">'.$flag['ip_address'].'</a>',
									m62_convert_timestamp($flag['created_date']),
									form_checkbox($toggle)
									);
		}
		
		echo $this->table->generate();	
	else: 
		echo lang('no_flags'); 
	endif; ?>
	<div class="tableFooter">
		<div class="tableSubmit">
			<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
		</div>
	</div>	
	<?php echo form_close()?>		
</div>
</div>