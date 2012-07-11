<?php $this->load->view('errors'); ?>

<div id="my_accordion">
<?php echo form_open($query_base.'delete_flagged_item', array('id'=>'flagged_entries')); ?>
<h3  class="accordion">Flagged Entries</h3>
<div id="flag_options">
	<?php 
	
	if(count($flagged_entries) > 0): 

		$this->table->clear();
		$this->table->set_template($cp_pad_table_template);
		$this->table->set_heading(
			lang('name'),
			lang('total_flags'),
			form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_files" id="select_all"').NBS.lang('delete', 'select_all')
		);
		
		foreach($flagged_entries as $option)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$option['id'],
					  'value'		=> $option['id'],
					  'class'		=>'toggle_files'
					  );
		
			$this->table->add_row(
									'<a href="'.$url_base.'view_option'.AMP.'option_id='.$option['id'].'">'.$option['title'].'</a>'. '<div class="subtext">'.$option['description'].'</div>',
									$option['total_flags'],
									form_checkbox($toggle)
									);
		}
		
		echo $this->table->generate();	
	else: 
		echo lang('no_flagged_entries'); 
	endif; ?>	
</div>

<h3  class="accordion">Flagged Comments</h3>
<div id="profile_flags">
	<?php 
	
	if(count($flagged_comments) > 0): 

		//echo form_open($query_base.'delete_option_confirm', array('id'=>'flagged_comments'));
		$this->table->clear();
		$this->table->set_template($cp_pad_table_template);
		$this->table->set_heading(
			lang('name'),
			lang('total_flags'),
			form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_files" id="select_all"').NBS.lang('delete', 'select_all')
		);
		
		foreach($flagged_comments as $option)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$option['id'],
					  'value'		=> $option['id'],
					  'class'		=>'toggle_files'
					  );
		
			$this->table->add_row(
									'<a href="'.$url_base.'view_option'.AMP.'option_id='.$option['id'].'">'.$option['title'].'</a>'. '<div class="subtext">'.$option['description'].'</div>',
									$option['total_flags'],
									form_checkbox($toggle)
									);
		}
		
		echo $this->table->generate();	
	else: 
		echo lang('no_flagged_comments'); 
	endif; ?>	
</div>

	<div class="tableFooter">
		<div class="tableSubmit">
			<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
		</div>
	</div>	
	<?php echo form_close()?>		
</div>