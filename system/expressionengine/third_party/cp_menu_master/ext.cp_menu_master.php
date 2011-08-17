<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CP Menu Master by Crescendo (support@crescendo.net.nz)
 *
 * Copyright (c) 2011 Crescendo Multimedia Ltd
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Cp_menu_master_ext
{
	public $name = 'CP Menu Master';
	public $version = '2.1';
	public $settings_exist = 'y';
	public $docs_url = 'http://github.com/crescendo/cp_menu_master';

	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public function activate_extension()
	{
		// check for old version settings
		$this->EE->db->where('module_name', 'Cp_menu_master');
		$row = $this->EE->db->get('modules')->row();
		if (empty($row->settings))
		{
			$this->settings = array();
		}
		else
		{
			$this->settings = unserialize(base64_decode($row->settings));
		}

		// remove old version module and accessory
		$this->EE->db->where('module_name', 'Cp_menu_master');
		$this->EE->db->delete('modules');

		$this->EE->db->where('class', 'Cp_menu_master_acc');
		$this->EE->db->delete('accessories');

		// install extension
		$data = array(
			'class' => __CLASS__,
			'method' => 'cp_menu_array',
			'hook' => 'cp_menu_array',
			'settings' => serialize($this->settings),
			'priority' => 10,
			'version' => $this->version,
			'enabled' => 'y'
		);

		$this->EE->db->insert('extensions', $data);
	}

	public function update_extension($current = '')
	{
		if ($current == $this->version) return FALSE;

		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('extensions', array('version' => $this->version));
		return TRUE;
	}

	public function settings_form()
	{
		$data = array(
			'post_url' => 'C=addons_extensions'.AMP.'M=save_extension_settings'.AMP.'file=cp_menu_master',
			'settings' => $this->_load_settings()
		);

		// get channel list
		$data['channels'] = array();
		$channels_query = $this->EE->channel_model->get_channels()->result();
		foreach ($channels_query as $channel)
		{
			$data['channels'][$channel->channel_id] = $channel->channel_title;
		}

		return $this->EE->load->view('index', $data, TRUE);
	}

	public function save_settings()
	{
		if (empty($_POST))
		{
			show_error($this->EE->lang->line('unauthorized_access'));
		}

		$settings = serialize($this->EE->input->post('settings', TRUE));

		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('extensions', array('settings' => $settings));

		$this->EE->session->set_flashdata('message_success', lang('preferences_updated'));
	}

	/**
	 * Why isn't this built in?
	 */
	private function _load_settings()
	{
		$this->EE->db->where('class', __CLASS__);
		$row = $this->EE->db->get('extensions')->row();
		if (empty($row->settings)) $settings = array();
		else $settings = unserialize($row->settings);

		if (empty($settings['hidden_channels']) OR ! is_array($settings['hidden_channels']))
		{
			$settings['hidden_channels'] = array();
		}

		if (empty($settings['hidden_edit']) OR ! is_array($settings['hidden_edit']))
		{
			$settings['hidden_edit'] = array();
		}

		return $settings;
	}

	/**
	 * CP Menu Array extension function
	 *
	 * Called by EE right before the top menu is generated, so we can edit it.
	 */
	public function cp_menu_array($menu)
	{
		$settings = $this->_load_settings();

		if (empty($settings['hidden_channels']) OR ! is_array($settings['hidden_channels']))
		{
			$settings['hidden_channels'] = array();
		}

		// alter the menu as per settings
		if (isset($menu['content']['publish']) AND is_array($menu['content']['publish']))
		{
			$content_edit = array();

			foreach ($menu['content']['publish'] as $title => $link)
			{
				$channel_id = (int)substr($link, stripos($link, 'channel_id=')+11);

				// remove the channel from publish menu if necessary
				if (in_array($channel_id, $settings['hidden_channels']))
				{
					unset($menu['content']['publish'][$title]);
				}

				// add channel to edit menu if necessary
				if ( ! in_array($channel_id, $settings['hidden_edit']))
				{
					$content_edit['cpmm_'.$title] = str_ireplace('C=content_publish&amp;M=entry_form', 'C=content_edit', $link);
					$this->EE->lang->language['nav_cpmm_'.$title] = $title;
				}
			}

			// display publish menu
			if (empty($menu['content']['publish']))
			{
				unset($menu['content']['publish']);
			}
			elseif (count($menu['content']['publish']) == 1)
			{
				$menu['content']['publish'] = reset($menu['content']['publish']);
			}

			// display edit menu
			if ( ! empty($settings['edit_submenu']))
			{
				if (empty($content_edit))
				{
					unset($menu['content']['edit']);
				}
				elseif (count($content_edit) == 1)
				{
					$menu['content']['edit'] = reset($content_edit);
				}
				else
				{
					$menu['content']['edit'] = $content_edit;
				}
			}
		}

		$extra_css = '';

		// do we want to show CP menus on hover instead of click?
		if ( ! empty($settings['hover_menus']))
		{
			$extra_css .= '
				#navigationTabs li.parent:hover ul,
				#navigationTabs li.parent:hover li:hover ul,
				#navigationTabs li.parent:hover li:hover ul li:hover ul,
				#navigationTabs li.parent:hover li:hover ul li:hover ul li:hover ul {
					display:block;
				}

				#navigationTabs li.parent:hover ul ul,
				#navigationTabs li.parent:hover li:hover ul ul,
				#navigationTabs li.parent:hover li:hover ul li:hover ul ul {
					display: none;
				}
			';
		}

		// do we want to hide the "Filter by Channel" drop-down?
		if ( ! empty($settings['hide_filter_by_channel']))
		{
			$extra_css .= '
				#filterMenu #f_channel_id { display: none; }
				#filterMenu #f_cat_id { margin-left: -0.85em; }
			';
		}

		if ( ! empty($extra_css))
		{
			$this->EE->cp->add_to_head('<style type="text/css">'.$extra_css.'</style>');
		}

		return $menu;
	}
}

/* End of file ./system/expressionengine/third_party/cp_menu_master/ext.cp_menu_master.php */