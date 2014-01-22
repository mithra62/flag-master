<?php $this->load->view('errors'); ?>

<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'index'; ?>" ><?php echo lang('back_to_dashboard')?></a>
		</span>		
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_comment_flags&comment_id='.$comment_data['comment_id']; ?>" ><?php echo lang('back_to_comment')?></a>
		</span>							
	</div>
</div>

<br clear="all" />
<div>
<?php 
$this->table->set_heading(lang('entry_title'), lang('profile'), lang('total_flags'), lang('first_flag'), lang('last_flag'));
$data = array(
		'<a href="'.$entry_view_url.'">'.$comment_data['entry_title'].'</a>', 
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

<div style="padding:0 20px 15px 20px;">
<?php echo $comment_data['comment']; ?> <a href="<?php echo $comment_view_url; ?>"><small>View Details...</small></a>
</div>

<div id="my_accordion">
<h3  class="accordion">Flags</h3>
<div id="all_flags">
	<?php 

	if(count($entry_flags) > 0): 
		$template = m62_table_template();
		echo form_open($query_base.'delete_flags_confirm', array('id'=>'flag_options'));
		$this->table->clear();
		$this->table->set_template($template);
		
		$heading = array(lang('platform'));
		if($option_data['option_user_defined'] == '1')
		{
			$heading[] = lang('user_defined');
 		}
		$heading[] = lang('user_name');
		$heading[] = lang('ip_address');
		$heading[] = lang('created_date');
		$heading[] = form_checkbox('select_all', 'true', FALSE, 'class="toggle_all_files" id="select_all"').NBS.lang('delete', 'select_all');
		
		$this->table->set_heading($heading);
		
		foreach($entry_flags as $flag)
		{
			$toggle = array(
					  'name'		=> 'toggle[]',
					  'id'		=> 'edit_box_'.$flag['flag_id'],
					  'value'		=> $flag['flag_id'],
					  'class'		=>'toggle_files'
					  );
		
			$option_row = array(m62_format_user_agent($flag['user_agent']));
			if($option_data['option_user_defined'] == '1')
			{
				$row = $flag['user_defined'];
				if(strlen($row) >= '40')
				{
					$row = substr($row, 0, 40).'...';
				}
								
				$row = '<a href="javascript:;" id="user_defined_'.$flag['id'].'_opener">'.$row.'<img src="'.$theme_folder_url.'/cp_themes/default/images/external_link.png" /></a>';
				$option_row[] = $row;
			}
			
			if(isset($flag['username']))
			{
				$username = $flag['username'];
				if(strlen($flag['username']) >= '10')
				{
					$username = substr($username, 0, 10).'...';
				}
				$option_row[] = '<a href="'.BASE.'&C=myaccount&id='.$flag['member_id'].'">'.$username.'</a>';
			}
			else
			{
				$option_row[] = 'N/A';
			}
			 
			$option_row[] = '<a href="http://whatismyipaddress.com/ip/'.$flag['ip_address'].'" target="_blank">'.$flag['ip_address'].'</a>';
			$option_row[] = m62_convert_timestamp($flag['created_date']);
			$option_row[] = form_checkbox($toggle);
			$this->table->add_row($option_row);
		}
		
		echo $this->table->generate();	
	else: 
		echo lang('no_flags'); 
	endif; ?>
	<div class="tableFooter">
		<div class="tableSubmit">
			<?php echo form_submit(array('name' => 'submit', 'value' => lang('delete_selected'), 'class' => 'submit'));?>
		</div>
	</div>	
	<?php echo form_close()?>		
</div>
</div>

<?php foreach($entry_flags as $flag): ?>
		<div id="user_defined_<?php echo $flag['id']; ?>" title="<?php echo m62_convert_timestamp($flag['created_date']); ?>"><?php echo $flag['user_defined']; ?></div>
<?php endforeach; ?>