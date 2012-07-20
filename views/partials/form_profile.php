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

<?php 
if(!isset($form_action))
{
	$form_action = 'add_profile';
}
echo form_open($query_base.$form_action, array('id'=>'my_accordion'));
?>
	<?php 
	
	//set form defaults
	$defaults = array();
	$defaults['name'] = (isset($profile['name']) ? $profile['name'] : FALSE);
	$defaults['notify_emails'] = (isset($profile['notify_emails']) ? $profile['notify_emails'] : FALSE);
	$defaults['active'] = (isset($profile['active']) ? $profile['active'] : '1');
	$defaults['type'] = (isset($profile['type']) ? $profile['type'] : 'url');
	$defaults['auto_close_threshold'] = (isset($profile['auto_close_threshold']) ? $profile['auto_close_threshold'] : FALSE);

	$this->table->set_heading('&nbsp;',' ');
	$this->table->add_row('<label for="name">'.lang('name').'</label><div class="subtext">'.lang('name_instructions').'</div>', form_input('name', $defaults['name'], 'id="name"'). form_error('name'));
	$this->table->add_row('<label for="status">'.lang('status').'</label><div class="subtext">'.lang('status_instructions').'</div>', form_dropdown('active', $statuses, $defaults['active'], 'id="active"'). form_error('status'));
	$this->table->add_row('<label for="type">'.lang('type').'</label><div class="subtext">'.lang('type_instructions').'</div>', form_dropdown('type', $profile_types, $defaults['type'], 'id="type"'). form_error('type'));	
	$this->table->add_row('<label for="auto_close_threshold">'.lang('auto_close_threshold').'</label><div class="subtext">'.lang('auto_close_threshold_instructions').'</div>', form_input('auto_close_threshold', $defaults['auto_close_threshold'], 'id="auto_close_threshold"'). form_error('auto_close_threshold'));
	$this->table->add_row('<label for="notify_emails">'.lang('notify_emails').'</label><div class="subtext">'.lang('notify_emails_instructions').'</div>', form_textarea('notify_emails', $defaults['notify_emails'], 'id="notify_emails"'). form_error('notify_emails'));
	echo $this->table->generate();
	$this->table->clear();
	?>
<br />
<div class="tableFooter">
	<div class="tableSubmit">
		<?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));?>
	</div>
</div>	
<?php echo form_close()?>