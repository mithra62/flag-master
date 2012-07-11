<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_profile&profile_id='.$profile_id; ?>" ><?php echo lang('back_to_profile')?></a>
		</span>	
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'add_profile'; ?>" ><?php echo lang('add_profile')?></a>
		</span>						
	</div>
</div>
<?php
$this->load->view('partials/form_profile',array('profile' => $profile_data, 'form_action' => 'edit_profile'.AMP.'profile_id='.$profile_data['id']));