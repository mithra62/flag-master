<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'add_profile'; ?>" ><?php echo lang('add_profile')?></a>
		</span>				
	</div>
</div>
<br clear="all" />
<?php 
echo lang('profiles_instructions'); ?>
<div class="clear_left shun"></div>

<?php echo form_open($query_base.'delete_profile_confirm', array('id'=>'my_accordion')); ?>

<div id="profiles">
	<?php 
if(count($profiles) > 0): 
	$this->table->set_template($cp_pad_table_template);
	$this->table->set_heading(
		lang('id'),
		lang('name'),
		lang('status'),
		lang('type'),
		lang('total_flags'),
		form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_profiles" id="select_all"').NBS.lang('delete', 'select_all')
	);
	
	foreach($profiles as $profile)
	{
		$toggle = array(
				  'name'		=> 'toggle[]',
				  'id'		=> 'edit_box_'.$profile['id'],
				  'value'		=> $profile['id'],
				  'class'		=>'toggle_profile'
				  );
	
		$this->table->add_row(
								'<a href="'.$url_base.'view_profile'.AMP.'profile_id='.$profile['id'].'" rel="'.$profile['name'].'" id="profile_title_'.$profile['id'].'">'.$profile['id'].'</a>',
								'<a href="'.$url_base.'view_profile'.AMP.'profile_id='.$profile['id'].'" rel="'.$profile['name'].'" id="profile_title_'.$profile['id'].'">'.$profile['name'].'</a>',
								($profile['active'] == '1' ? lang('active') : lang('inactive')),
								lang($profile['type']),
								$profile['total_flags'],
								form_checkbox($toggle)
								);
	}
	
	echo $this->table->generate();
	?>
	<?php else: ?>
	<p><?php echo lang('no_profiles')?></p>
	<?php endif; ?>
</div>


<br />
<div class="tableFooter">
	<div class="tableSubmit">
		<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
	</div>
</div>	
<?php echo form_close()?>