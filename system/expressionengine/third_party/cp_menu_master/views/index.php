<?= form_open($post_url, array('id' => 'cp_menu_master_settings')) ?>

<fieldset>
	<legend><?= lang('cp_menu_options') ?></legend>
	<p><label>
		<?= form_checkbox('settings[edit_submenu]', 'y', ! empty($settings['edit_submenu']), 'id="settings_edit_submenu"') ?>
		<?= lang('display_content_edit_submenu') ?>
	</label></p>
	<p><label>
		<?= form_checkbox('settings[hover_menus]', 'y', ! empty($settings['hover_menus'])) ?>
		<?= lang('display_menus_on_hover') ?>
	</label></p>
</fieldset>

<br />
<?php
	$this->table->clear();
	$this->table->set_template($cp_table_template);
	$this->table->set_heading(
		array('data' => lang('channel')),
		array('data' => lang('hide_from_publish'), 'width' => '15%'),
		array('data' => lang('hide_from_edit'), 'width' => '15%'));

	foreach ($channels as $channel_id => $channel_title)
	{
		$this->table->add_row(
			$channel_title,
			form_checkbox('settings[hidden_channels][]', $channel_id, in_array($channel_id, $settings['hidden_channels'])),
			form_checkbox('settings[hidden_edit][]', $channel_id, in_array($channel_id, $settings['hidden_edit']), 'class="hide_from_edit"')
		);
	}

	echo $this->table->generate();
?>

<div style="text-align: right;">
	<?= form_submit(array('name' => 'submit', 'value' => lang('update'), 'class' => 'submit')) ?>
</div>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#cp_menu_master_settings #settings_edit_submenu').change(function() {
		if ($(this).attr('checked')) {
			$('input:checkbox.hide_from_edit').attr('disabled', false);
		} else {
			$('input:checkbox.hide_from_edit').attr('disabled', true);
		}
	}).change();
});
</script>