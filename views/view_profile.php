<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'edit_profile&profile_id='.$profile_id; ?>" ><?php echo lang('edit_profile')?></a>
		</span>	
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'add_profile'; ?>" ><?php echo lang('add_profile')?></a>
		</span>						
	</div>
</div>

<br clear="all" />
<div>
<?php 
$this->table->set_heading(lang('name'), lang('type'), lang('total_flags'),lang('status'));
$data = array(
		$profile_data['name'], 
		lang($profile_data['type']), 
		$profile_data['total_flags'],
		($profile_data['active'] == '1' ? lang('active') : lang('inactive')),
);
$this->table->add_row($data);
echo $this->table->generate();
$this->table->clear();
?>
</div>


<div id="my_accordion">
	<div class="fm_top_nav">
		<div class="fm_nav">
			<span class="button"> 
				<a class="nav_button" href="<?php echo $url_base.'add_option'.AMP.'profile_id='.$profile_id; ?>" ><?php echo lang('add_option')?></a>
			</span>						
		</div>
	</div>
<h3  class="accordion">Flag Options</h3>
<div id="flag_options">
	<?php 
	
	if(count($flag_options) > 0): 
	
		echo form_open($query_base.'delete_option_confirm', array('id'=>'profile_options'));
	?>
	<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>Name</th>
			<th>Total Flags</th>
			<th>
				<input type="checkbox" name="select_all" value="true" class="toggle_all_files" id="select_all" />&nbsp;
				<label for="select_all">Delete</label>
			</th>
		</tr>
	</thead>
	<tbody>	
	<?php 
		foreach($flag_options as $option)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$option['id'],
					  'value'		=> $option['id'],
					  'class'		=>'toggle_files'
					  );
			echo '<tr rel="option_id" rel_value="'.$option['id'].'" id="option_id_'.$option['id'].'">
					<td style="cursor:move;">
						<a href="'.$url_base.'view_option'.AMP.'option_id='.$option['id'].'">'.$option['title'].'</a><div class="subtext">'.$option['description'].'</div>
					</td>
					<td>
						'.$option['total_flags'].'
					</td>
					<td>
						'.form_checkbox($toggle).'
					</td>												
				  </tr>';
		}
		echo '</tbody></table>';
			
	else: 
		echo lang('no_flag_options'); 
	endif; ?>
	<div class="tableFooter">
		<div class="tableSubmit">
			<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
		</div>
	</div>
	<input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>" id="profile_id" />	
	<?php echo form_close()?>		
</div>

</div>