<?php $this->load->view('errors'); ?>
<?php 

$tmpl = array (
	'table_open'          => '<table class="mainTable" border="0" cellspacing="0" cellpadding="0">',

	'row_start'           => '<tr class="even">',
	'row_end'             => '</tr>',
	'cell_start'          => '<td style="width:50%;">',
	'cell_end'            => '</td>',

	'row_alt_start'       => '<tr class="odd">',
	'row_alt_end'         => '</tr>',
	'cell_alt_start'      => '<td>',
	'cell_alt_end'        => '</td>',

	'table_close'         => '</table>'
);

$this->table->set_template($tmpl); 
$this->table->set_empty("&nbsp;");
?>
<div class="clear_left shun"></div>

<?php echo form_open($query_base.'settings', array('id'=>'my_accordion'))?>
<input type="hidden" value="yes" name="go_settings" />

<h3  class="accordion"><?=lang('global_config')?></h3>
<div>
	<?php 
	$this->table->set_heading(lang('settings'),' ');
	$this->table->add_row('<label for="flag_master_date_format">'.lang('flag_master_date_format').'</label><div class="subtext">'.lang('flag_master_date_format_instructions').'</div>', form_input('flag_master_date_format', $settings['flag_master_date_format'], 'id="flag_master_date_format"'. $settings_disable));
	
	echo $this->table->generate();
	$this->table->clear();	
	?>
</div>

<h3  class="accordion"><?=lang('license_number')?></h3>
<div>
	<?php
	$this->table->set_heading(lang('settings'),' ');
	$this->table->add_row('<label for="license_number">'.lang('license_number').'</label>', form_input('license_number', $settings['license_number'], 'id="license_number"'. $settings_disable));	
	echo $this->table->generate();
	$this->table->clear();
	?>
</div>

<br />
<div class="tableFooter">
	<div class="tableSubmit">
		<?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));?>
	</div>
</div>	
<?php echo form_close()?>