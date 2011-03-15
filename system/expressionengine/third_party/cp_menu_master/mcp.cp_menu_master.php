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

class Cp_menu_master_mcp {
	
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->ACC = $this->_get_acc_instance();
	}

	public function index()
	{
		$this->EE->cp->set_variable('cp_page_title', lang('cp_menu_master_module_name'));
		
		$data = array(
			'post_url' => 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=cp_menu_master',
			'settings' => $this->ACC->load_module_settings()
		);
		
		if ($this->EE->input->post('submit'))
		{
			$update = array('settings' => base64_encode(serialize($this->EE->input->post('settings', TRUE))));
			$this->EE->db->where('module_name', 'Cp_menu_master');
			$this->EE->db->update('modules', $update);
			
			$this->EE->session->set_flashdata('message_success', lang('settings_updated'));
			$this->EE->functions->redirect(BASE.AMP.$data['post_url']);
		}
		
		// get channel list
		$data['channels'] = array();
		$channels_query = $this->EE->channel_model->get_channels()->result();
		foreach ($channels_query as $channel)
		{
			$data['channels'][$channel->channel_id] = $channel->channel_title;
		}
		
		return $this->EE->load->view('index', $data, TRUE);
	}
	
	private function _get_acc_instance()
	{
		// keepin' it DRY
		if ( ! class_exists('Cp_menu_master_acc'))
		{
			require_once(PATH_THIRD.'cp_menu_master/acc.cp_menu_master'.EXT);
		}
		
		return new Cp_menu_master_acc();
	}
}

/* End of file ./system/expressionengine/third_party/cp_menu_master/mcp.cp_menu_master.php */