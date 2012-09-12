<div id="flag_master_ft_<?php echo $field_settings['flag_type']; ?>">
<?php 
if($field_settings['flag_type'] == 'comment')
{
	$this->load->view('partials/flagged_comments_list', array('flagged_comments' => $flagged_entries));
}
else
{
	$this->load->view('partials/flagged_entries_list');
}
?>	
</div>

