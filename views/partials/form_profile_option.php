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
	$form_action = 'add_option'.AMP.'profile_id='.$profile_id;
}
echo form_open($query_base.$form_action, array('id'=>'my_accordion'));

$defaults = array();
$defaults['title'] = (isset($option['title']) ? $option['title'] : FALSE);
$defaults['description'] = (isset($option['description']) ? $option['description'] : FALSE);
$defaults['user_defined'] = (isset($option['user_defined']) ? $option['user_defined'] : FALSE);
$defaults['member_only'] = (isset($option['member_only']) ? $option['member_only'] : FALSE);


$this->table->set_heading('&nbsp;',' ');
$this->table->add_row('<label for="title">'.lang('title').'</label><div class="subtext">'.lang('title_instructions').'</div>', form_input('title', $defaults['title'], 'id="title"'). form_error('title'));
$this->table->add_row('<label for="description">'.lang('description').'</label><div class="subtext">'.lang('description_instructions').'</div>', form_textarea('description', $defaults['description'], 'id="description"'). form_error('description'));
$this->table->add_row('<label for="user_defined">'.lang('user_defined').'</label><div class="subtext">'.lang('user_defined_instructions').'</div>', form_checkbox('user_defined', '1', ($defaults['user_defined'] == '1' ? TRUE : FALSE), 'id="user_defined"'). form_error('user_defined'));
$this->table->add_row('<label for="member_only">'.lang('member_only').'</label><div class="subtext">'.lang('member_only_instructions').'</div>', form_checkbox('member_only', '1', ($defaults['member_only'] == '1' ? TRUE : FALSE), 'id="member_only"'). form_error('member_only'));

echo $this->table->generate();
$this->table->clear();
?>
<br />
<div class="tableFooter">
	<div class="tableSubmit">
		<?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'));?>
	</div>
</div>	
<?php 

if(isset($profile_id))
{
	echo form_hidden('profile_id', $profile_id, FALSE); 
}
elseif(isset($option_data['profile_id']))
{
	echo form_hidden('profile_id', $option_data['profile_id'], FALSE);
	echo form_hidden('option_id', $option_data['id'], FALSE);
}

?>
<?php echo form_close()?>