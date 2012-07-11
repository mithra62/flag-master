<?=form_open($form_action)?>
<?php foreach($damned as $id):?>
	<?=form_hidden('delete[]', $id)?>
<?php endforeach;?>

<p class="notice"><?=lang('action_can_not_be_undone')?></p>

<h3><?=lang($delete_profiles_question); ?></h3>
<p>
<?php foreach($data AS $item): ?>
	(#<?php echo $item['id'];?>) <?php echo $item['name'];?><br />
<?php endforeach; ?>
</p>

<p>
	<?=form_submit(array('name' => 'submit', 'value' => lang('delete'), 'class' => 'submit'))?>
</p>

<?=form_close()?>