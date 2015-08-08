<?php $this->load->view('errors'); ?>
<div class="fm_top_nav">
	<div class="fm_nav">
		<span class="button"> 
			<a class="nav_button" href="<?php echo $url_base.'view_profile&profile_id='.$profile_id; ?>" ><?php echo lang('back_to_profile')?></a>
		</span>					
	</div>
</div>
<?php 
$this->load->view('partials/form_profile_option',array('option_data' => $option_data, 'form_action' => 'add_option'.AMP.'profile_id='.$profile_id));