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
$this->table->set_heading(lang('entry_title'), lang('total_flags'), lang('first_flag'), lang('last_flag'));
$data = array(
		'<a href="'.$entry_view_url.'">'.$entry_data['title'].'</a>', 
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
<h3  class="accordion">Comments</h3>
<div id="all_flags">
	<?php 
	
	if(count($entry_flags) > 0): 

		echo form_open($query_base.'delete_flags_confirm', array('id'=>'flag_options'));
	$this->load->view('partials/flagged_comments_list', array('flagged_comments' => $flagged_comments));
	else: 
		echo lang('no_comments'); 
	endif; ?>
	<?php echo form_close()?>		
</div>
</div>