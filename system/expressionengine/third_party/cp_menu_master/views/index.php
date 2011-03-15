<?= form_open($post_url) ?>

<fieldset>
	<legend><?= lang('cp_menu_options') ?></legend>
	<p><label>
		<?= form_checkbox('settings[edit_submenu]', 'y', ! empty($settings['edit_submenu'])) ?>
		<?= lang('display_content_edit_submenu') ?>
	</label></p>
	<p><label>
		<?= form_checkbox('settings[hover_menus]', 'y', ! empty($settings['hover_menus'])) ?>
		<?= lang('display_menus_on_hover') ?>
	</label></p>
</fieldset>

<br />
<fieldset>
	<legend><?= lang('cp_hidden_publish_channels') ?></legend>

	<?php if (empty($channels)): ?>
		<p><em><?= lang('no_publish_channels') ?></em></p>
	<?php endif; ?>
	
	<?php foreach ($channels as $channel_id => $channel_title): ?>
		<p><label>
			<?= form_checkbox('settings[hidden_channels][]', $channel_id, in_array($channel_id, $settings['hidden_channels'])) ?>
			<?= $channel_title ?>
		</label></p>
	<?php endforeach; ?>
	
</fieldset>

<br />
<div style="text-align: right;">
	<?= form_submit(array('name' => 'submit', 'value' => lang('update'), 'class' => 'submit')) ?>
</div>

<?= form_close() ?>