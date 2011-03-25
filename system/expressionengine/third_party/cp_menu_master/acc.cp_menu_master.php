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

class Cp_menu_master_acc
{
	public $name;
	public $description;
	public $version = '1.0.1';
	public $id = 'cp_menu_master';
	public $sections = array();

	public function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->lang->loadfile('cp_menu_master');
		$this->name = $this->EE->lang->line('cp_menu_master_module_name');
		$this->description = $this->EE->lang->line('cp_menu_master_module_description');
	}

	public function set_sections()
	{
		// this prevents errors because our view path has been remapped
		$current_view_path = $this->EE->load->_ci_view_path;
		$this->EE->load->_ci_view_path = PATH_CP_THEME.'default/';

		// backup existing themed menu views
		$menu_views = array(
			'menu_parent' => $this->EE->menu->menu_parent,
			'menu_item' => $this->EE->menu->menu_item,
			'menu_divider' => $this->EE->menu->menu_divider
		);

		// whip up a new menu array
		$menu = $this->EE->menu->generate_menu();

		// restore menu view files & view path
		$this->EE->menu->menu_parent = $menu_views['menu_parent'];
		$this->EE->menu->menu_item = $menu_views['menu_item'];
		$this->EE->menu->menu_divider = $menu_views['menu_divider'];

		// redraw the menu as we see fit
		$settings = $this->load_module_settings();

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
					$content_edit['xxCPMMxx_'.$title] = str_ireplace('C=content_publish&amp;M=entry_form', 'C=content_edit', $link);
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

		// turn the menu array back into html
		$menu_string  = $this->EE->menu->_process_menu($menu);
		$menu_string .= $this->EE->menu->_process_menu($this->EE->menu->_fetch_quick_tabs(), 0, FALSE);
		$menu_string .= $this->EE->menu->_process_menu(array('help' => $this->EE->menu->generate_help_link()));
		$menu_string .= $this->EE->menu->_process_menu($this->EE->menu->_fetch_site_list(), 0, FALSE, 'msm_sites');

		// stupid EE prepends 'nav_' in front of menu items when it tries to localize them
		$menu_string = str_replace('nav_xxCPMMxx_', '', $menu_string);

		// save our new menu html
		$this->EE->load->vars('menu_string', $menu_string);

		// do we want to show CP menus on hover instead of click?
		if ( ! empty($settings['hover_menus']))
		{
			$this->EE->cp->add_to_head('<style type="text/css">
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
			</style>');
		}
		// remove the unused accessory tab
		$this->EE->javascript->output('$("#accessoryTabs > ul > li > a.cp_menu_master").parent("li").remove();');
		$this->EE->javascript->compile();

		// be polite and restore the old view path
		$this->EE->load->_ci_view_path = $current_view_path;
	}

	public function load_module_settings()
	{
		$this->EE->db->where('module_name', 'Cp_menu_master');
		$row = $this->EE->db->get('modules')->row();
		if (empty($row->settings)) $settings = array();
		else $settings = unserialize(base64_decode($row->settings));

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
}

/* End of file ./system/expressionengine/third_party/cp_menu_master/acc.cp_menu_master.php */