<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_profile&profile_id='.$profile_id; ?>" ><?php echo lang('back_to_profile')?></a>
		</span>		
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'edit_option&option_id='.$option_id; ?>" ><?php echo lang('edit_option')?></a>
		</span>	
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'add_option'.AMP.'profile_id='.$profile_id; ?>" ><?php echo lang('add_option')?></a>
		</span>						
	</div>
</div>

<br clear="all" />
<div>
<?php 
$this->table->set_heading(lang('title'), lang('profile'), lang('total_flags'));
$data = array(
		$option_data['title'], 
		$profile_data['name'], 
		$option_data['total_flags']
);
$this->table->add_row($data);
echo $this->table->generate();
$this->table->clear();
?>
</div>

<!-- 
<div id="my_accordion">
	<div class="fm_top_nav">
		<div class="fm_nav">
			<span class="button"> 
				<a class="nav_button" href="<?php echo $url_base.'add_option'.AMP.'profile_id='.$profile_id; ?>" ><?php echo lang('add_child_option')?></a>
			</span>						
		</div>
	</div>
<h3  class="accordion">Child Options</h3>
<div id="child_options">
	<?php 
	
	if(count($child_options) > 0): 

		echo form_open($query_base.'delete_option_confirm', array('id'=>'profile_options'));
		$this->table->clear();
		$this->table->set_template($cp_pad_table_template);
		$this->table->set_heading(
			lang('name'),
			lang('total_flags'),
			form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_files" id="select_all"').NBS.lang('delete', 'select_all')
		);
		
		foreach($child_options as $option)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$option['id'],
					  'value'		=> $option['id'],
					  'class'		=>'toggle_files'
					  );
		
			$this->table->add_row(
									'<a href="'.$url_base.'view_option'.AMP.'option_id='.$option['id'].'">'.$option['title'].'</a>',
									$option['total_flags'],
									form_checkbox($toggle)
									);
		}
		
		echo $this->table->generate();	
	else: 
		echo lang('no_child_options'); 
	endif; ?>
	<div class="tableFooter">
		<div class="tableSubmit">
			<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
		</div>
	</div>	
	<?php echo form_close()?>		
</div>

</div>
 -->