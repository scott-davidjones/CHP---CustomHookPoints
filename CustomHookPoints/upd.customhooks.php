<?php  
/**
*This file is part of Custom Hooks.
*
*Custom Hooks is free software: you can redistribute it and/or modify
*it under the terms of the GNU General Public License as published by
*the Free Software Foundation, either version 3 of the License, or
*(at your option) any later version.
*
*Custom Hooks is distributed in the hope that it will be useful,
*but WITHOUT ANY WARRANTY; without even the implied warranty of
*MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*GNU General Public License for more details.
*
*You should have received a copy of the GNU General Public License
*along with Custom Hooks.  If not, see <http://www.gnu.org/licenses/>
*
*Author: Scott-David Jones 
*Website: http://www.autumndev.co.uk
*
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customhooks_upd {

    var $version = '1.0';

    function __construct()
    {
        // Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance();
    }
	
	function install()
	{
		$this->EE->load->dbforge();

		$data = array(
			'module_name' => 'Customhooks' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);
		
		$fields = array(
		'hook_id'   => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		'hook_name' => array('type' => 'varchar', 'constraint' => '100'),
		'dir'    => array('type' => 'varchar', 'constraint'  => '250'),
		'function'    => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL)
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('hook_id', TRUE);

		$this->EE->dbforge->create_table('customhooks_existing_hooks');
		//create a unique index on hook_name via SQL as DB forge wont do this.
		$this->EE->db->query('CREATE UNIQUE INDEX hook_name_idx ON '. $this->EE->db->dbprefix.'customhooks_existing_hooks (hook_name); ');

		unset($fields);
		
		return TRUE;
	}

	function uninstall()
	{
		$this->EE->load->dbforge();

		$this->EE->db->select('module_id');
		$query = $this->EE->db->get_where('modules', array('module_name' => 'Customhooks'));

		$this->EE->db->where('module_id', $query->row('module_id'));
		$this->EE->db->delete('module_member_groups');

		$this->EE->db->where('module_name', 'Customhooks');
		$this->EE->db->delete('modules');

		$this->EE->dbforge->drop_table('customhooks_existing_hooks');

		return TRUE;
	}
	
	function update($current = '')
	{
		return FALSE;
	}
	
}