<div id="flag_master_ft">
<?php 
if(count($flagged_entries) > 0): 

	$this->table->clear();
	$this->table->set_template($cp_pad_table_template);
	$this->table->set_heading(
		lang('name'),
		lang('total_flags'),
		lang('first_flag'),
		lang('last_flag')
	);
	
	foreach($flagged_entries as $option)
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
	echo lang('no_flagged_entries'); 
endif; ?>	
</div>