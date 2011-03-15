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

class Cp_menu_master_upd {
	
	public $version = '1.0.1';
	
	public function __construct()
	{
		$this->EE =& get_instance();
		
		if ( ! class_exists('Cp_menu_master_acc'))
		{
			require_once(PATH_THIRD.'cp_menu_master/acc.cp_menu_master'.EXT);
		}
		
		$cp_menu_master_acc = new Cp_menu_master_acc();
		$this->version = $cp_menu_master_acc->version;
	}
	
	public function install()
	{
		$this->EE->load->dbforge();
		
		// register module
		$this->EE->db->insert('modules', array(
			'module_name' => 'Cp_menu_master',
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'));
		
		// add settings column to exp_modules if it doesn't already exist
		if ( ! $this->EE->db->field_exists('settings', 'modules'))
		{
			$this->EE->dbforge->add_column('modules', array(
				'settings'	=> array('type' => 'text', 'null' => TRUE)
			));
		}
		
		return TRUE;
	}
	
	public function update($current = '')
	{
		return $current < $this->version;
	}
	
	public function uninstall()
	{
		$this->EE->db->where('module_name', 'Cp_menu_master');
		$this->EE->db->delete('modules');
		return TRUE;
	}
}

/* End of file ./system/expressionengine/third_party/cp_menu_master/upd.cp_menu_master.php */