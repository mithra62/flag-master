<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'index'; ?>" ><?php echo lang('back_to_dashboard')?></a>
		</span>								
	</div>
</div>

<br clear="all" />
<div>
<?php 
$this->table->set_heading(lang('entry_title'), lang('profile'), lang('total_flags'), lang('first_flag'), lang('last_flag'));
$data = array(
		'<a href="'.$entry_view_url.'">'.$entry_data['title'].'</a>', 
		$profile_data['name'], 
		$flag_meta['total_flags'],
		m62_convert_timestamp($flag_meta['first_flag']),
		m62_convert_timestamp($flag_meta['last_flag'])
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
		lang('name'),
		lang('total_flags'),
		lang('first_flag'),
		lang('last_flag')
	);
	
	foreach($entry_flags as $option)
	{
	
		$this->table->add_row(
								'<a href="'.$url_base.'view_entry_flag_option'.AMP.'option_id='.$option['option_id'].AMP.'entry_id='.$option['entry_id'].'">'.$option['title'].'</a>'. '<div class="subtext">'.$option['description'].'</div>',
								$option['total_flags'],
								m62_convert_timestamp($option['first_flag']),
								m62_convert_timestamp($option['last_flag'])
								);
	}
	
	echo $this->table->generate();		
	else: 
		echo lang('no_flags'); 
	endif; ?>
	<?php echo form_close()?>		
</div>
</div>