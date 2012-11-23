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
<h3  class="accordion"><?=lang('general')?></h3>
<div>
	<?php 
	
	//set form defaults
	$defaults = array();
	$defaults['name'] = (isset($profile['name']) ? $profile['name'] : FALSE);
	$defaults['active'] = (isset($profile['active']) ? $profile['active'] : '1');
	$defaults['type'] = (isset($profile['type']) ? $profile['type'] : 'url');
	$defaults['auto_close_threshold'] = (isset($profile['auto_close_threshold']) ? $profile['auto_close_threshold'] : FALSE);

	$this->table->set_heading(lang('setting'), lang('value'));
	$this->table->add_row('<label for="name">'.lang('name').'</label><div class="subtext">'.lang('name_instructions').'</div>', form_input('name', $defaults['name'], 'id="name"'). form_error('name'));
	$this->table->add_row('<label for="status">'.lang('status').'</label><div class="subtext">'.lang('status_instructions').'</div>', form_dropdown('active', $statuses, $defaults['active'], 'id="active"'). form_error('status'));
	$this->table->add_row('<label for="type">'.lang('type').'</label><div class="subtext">'.lang('type_instructions').'</div>', form_dropdown('type', $profile_types, $defaults['type'], 'id="type"'). form_error('type'));	
	$this->table->add_row('<label for="auto_close_threshold">'.lang('auto_close_threshold').'</label><div class="subtext">'.lang('auto_close_threshold_instructions').'</div>', form_input('auto_close_threshold', $defaults['auto_close_threshold'], 'id="auto_close_threshold"'). form_error('auto_close_threshold'));
	echo $this->table->generate();
	$this->table->clear();
	?>
</div>

<h3  class="accordion"><?=lang('notification_prefs')?></h3>
<div>
	<?php 
	
	//set form defaults
	$defaults = array();
	$defaults['notify_emails'] = (isset($profile['notify_emails']) ? $profile['notify_emails'] : FALSE);
	$defaults['notify_email_multiplier'] = (isset($profile['notify_email_multiplier']) ? $profile['notify_email_multiplier'] : 5);
	
	$defaults['notify_email_subject'] = (isset($profile['notify_email_subject']) ? $profile['notify_email_subject'] : lang('notify_email_subject_copy'));
	$defaults['notify_email_message'] = (isset($profile['notify_email_message']) ? $profile['notify_email_message'] : lang('notify_email_message_copy'));
	$defaults['notify_email_mailtype'] = (isset($profile['notify_email_mailtype']) ? $profile['notify_email_mailtype'] : lang('notify_email_mailtype_copy'));

	$this->table->set_heading(lang('setting'), lang('value'));
	$this->table->add_row('<label for="notify_email_multiplier">'.lang('notify_email_multiplier').'</label><div class="subtext">'.lang('notify_email_multiplier_instructions').'</div>', form_input('notify_email_multiplier', $defaults['notify_email_multiplier'], 'id="notify_email_multiplier"'));
	$this->table->add_row('<label for="notify_email_subject">'.lang('notify_email_subject').'</label><div class="subtext">'.lang('notify_email_subject_instructions').'</div>', form_input('notify_email_subject', $defaults['notify_email_subject'], 'id="notify_email_subject"'));
	$this->table->add_row('<label for="notify_email_message">'.lang('notify_email_message').'</label><div class="subtext">'.lang('notify_email_message_instructions').'</div>', form_textarea('notify_email_message', $defaults['notify_email_message'], 'cols="90" rows="6" id="notify_email_message"'));
	$this->table->add_row('<label for="notify_email_mailtype">'.lang('notify_email_mailtype').'</label><div class="subtext">'.lang('notify_email_mailtype_instructions').'</div>', form_dropdown('notify_email_mailtype', $email_format_options, $defaults['notify_email_mailtype'], 'id="notify_email_mailtype"'));
	
	$this->table->add_row('<label for="notify_emails">'.lang('notify_emails').'</label><div class="subtext">'.lang('notify_emails_instructions').'</div>', form_textarea('notify_emails', $defaults['notify_emails'], 'id="notify_emails"'). form_error('notify_emails'));
	echo $this->table->generate();
	$this->table->clear();
	?>
</div>
<div class="tableFooter">
	<div class="tableSubmit">
		<?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));?>
	</div>
</div>	
<?php echo form_close()?>