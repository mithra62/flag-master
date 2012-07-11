<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_option&option_id='.$option_id; ?>" ><?php echo lang('back_to_option')?></a>
		</span>						
	</div>
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_profile&profile_id='.$option_data['profile_id']; ?>" ><?php echo lang('back_to_profile')?></a>
		</span>						
	</div>	
</div>
<?php
$this->load->view('partials/form_profile_option', array('option' => $option_data, 'form_action' => 'edit_option'.AMP.'option_id='.$option_data['id']));