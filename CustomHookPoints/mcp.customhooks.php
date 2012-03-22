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

class Customhooks_mcp {

	var $perpage = 20;
	function __construct()
	{
		$this->EE =& get_instance();

		$this->EE->cp->set_right_nav(array(
				$this->EE->lang->line('customhooks_add')  => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'
					.AMP.'module=customhooks'.AMP.'method=add_hooks'
				));
			define('AJAX_URL', html_entity_decode(BASE).'&C=addons_modules&M=show_module_cp&module=customhooks'); 
	}

	function index()
	{
		$this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->load->helper('form');

		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('customhooks_module_name'));
		//$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks',$this->EE->lang->line('customhooks_module_name'));

		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks'.AMP.'method=edit_hooks';
		$vars['form_hidden'] = NULL;
		$vars['hooks'] = array();

		$vars['options'] = array(
					'edit'  => lang('edit_selected'),
					'delete'    => lang('delete_selected')
					);
	
		if ( ! $rownum = $this->EE->input->get_post('rownum'))
		{
			$rownum = 0;
		}

		$this->EE->db->order_by("hook_id", "desc");
		$query = $this->EE->db->get('customhooks_existing_hooks', $this->perpage, $rownum);
		
		foreach($query->result_array() as $row)
		{
			$vars['hooks'][$row['hook_id']]['hook_name'] = $row['hook_name'];
			$vars['hooks'][$row['hook_id']]['restore_link'] = BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=customhooks'.AMP.'method=restore_hooks'.AMP.'file_id='.$row['hook_id'];
			$vars['hooks'][$row['hook_id']]['delete_link'] = BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=customhooks'.AMP.'method=delete_hooks'.AMP.'file_id='.$row['hook_id'];
			$vars['hooks'][$row['hook_id']]['dir'] = $row['dir'];
			$vars['hooks'][$row['hook_id']]['function'] = $row['function'];


			// Toggle checkbox
			$vars['hooks'][$row['hook_id']]['toggle'] = array(
									'name'      => 'toggle[]',
									'id'        => 'edit_box_'.$row['hook_id'],
									'value'     => $row['hook_id'],
									'class'     =>'toggle'
									);
		}
		
		 //  Check for pagination
		$total = $this->EE->db->count_all('customhooks_existing_hooks');

		// Pass the relevant data to the paginate class so it can display the "next page" links
		$this->EE->load->library('pagination');
		$p_config = $this->pagination_config('index', $total);

		$this->EE->pagination->initialize($p_config);

		$vars['pagination'] = $this->EE->pagination->create_links();
		
		$this->EE->javascript->output(array(
				'$(".toggle_all").toggle(
					function(){
						$("input.toggle").each(function() {
							this.checked = true;
						});
					}, function (){
						var checked_status = this.checked;
						$("input.toggle").each(function() {
							this.checked = false;
						});
					}
				);'
			)
			);
		$this->EE->cp->add_js_script(array('plugin' => 'dataTables'));
		//$this->EE->javascript->output($this->ajax_filters('edit_items_ajax_filter', 4));
		$this->EE->javascript->compile();

		return $this->EE->load->view('index', $vars, TRUE);
	}
	
	function add_hooks()
	{
		$this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->load->helper('form');
	
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('customhooks_add'));
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks',$this->EE->lang->line('customhooks_module_name'));

		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks'.AMP.'method=save_hooks';
		$vars['form_hidden'] = NULL;
		//set array of allowed folders
		$vars['dir'] = $this->get_subdir_files(APPPATH);
		
		$this->EE->javascript->output(array(
				'$("#dirlist").change(function(){
					input = $("#dirlist option:selected").val();
					$.ajax({
						type:"POST",
						url: "'.AJAX_URL.'&method=get_functions",
						data: { XID:"'.XID_SECURE_HASH.'", uri:input },
						dataType: "html",
						success: function(msg){
							r = msg.split("|");
							re = "<select id=functionlist name=functionlist><option>Please Select...</option>";
							$.each(  r, function(i, l){
								if(l.length > 1)
								{
									re = re+"<option value="+l+">"+l+"</option>";
								}
							 });
							 re = re+"</select>";
							$("#fn_holder").html(re);
					}
					});
				
				})'
			)
			);
		$this->EE->cp->add_js_script(array('plugin' => 'dataTables'));
		//$this->EE->javascript->output($this->ajax_filters('edit_items_ajax_filter', 4));
		$this->EE->javascript->compile();

		return $this->EE->load->view('add', $vars, TRUE);
		
	}
	
	function pagination_config($method, $total_rows)
	{
		// Pass the relevant data to the paginate class
		$config['base_url'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks'.AMP.'method='.$method;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $this->perpage;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'rownum';
		$config['full_tag_open'] = '<p id="paginationLinks">';
		$config['full_tag_close'] = '</p>';
		$config['prev_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_prev_button.gif" width="13" height="13" alt="<" />';
		$config['next_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_next_button.gif" width="13" height="13" alt=">" />';
		$config['first_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_first_button.gif" width="13" height="13" alt="< <" />';
		$config['last_link'] = '<img src="'.$this->EE->cp->cp_theme_url.'images/pagination_last_button.gif" width="13" height="13" alt="> >" />';

		return $config;
	}
	/*
	*
	*Function takes the users input and saves and creates the custom hooks
	*
	*/
	function save_hooks()
	{
		$hook_name = $this->EE->input->post('hook_name');
		$file = $this->EE->input->post('dirlist');
		$function = $this->EE->input->post('functionlist');
		//if hook name contains any non-alphanumeric chars return error
		if(!ctype_alnum($hook_name))
		{
			show_error($this->EE->lang->line('invalid_hook_point_name'));
		}
		
		//build hook string ready for insertion into file
		$hook_str = '// -------------------------------------------
// CUSTOM HOOKS Hook
//
	$CH =& get_instance();
	
    if ($CH->extensions->active_hook("'.$hook_name.'") === TRUE)
    {
        $str = $CH->extensions->call("'.$hook_name.'");
		if ($CH->extensions->end_script === TRUE) return;
    }
	unset($CH);
//
// END CUSTOM HOOKS HOOK
// -------------------------------------------
';
		//set pattern
		$pattern = "/function.*".$function.".*\s*{/";
		//get file contents
		$function_string =  file_get_contents($file);
		//make backup
		file_put_contents($file.'.'.date("Y_m_d_H_i_s").'_BACKUP', $function_string);
		//enter hook into data base
		$data = array('hook_name' => $hook_name, 'dir' => $file, 'function' => $function);
		$sql = $this->EE->db->insert_string('exp_customhooks_existing_hooks', $data);
		$this->EE->db->query($sql);
		
		if(preg_match_all($pattern,$function_string,$replaced))
		{
			$str = $replaced[0][0];
			$function_string = preg_replace($pattern, $str.$hook_str, $function_string);
			file_put_contents($file, $function_string);
		}
		else
		{
			show_error($this->EE->lang->line('No_Function_Found'));
		}
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks');
	
	}
	
	/*
	*Function to remove hooks
	*
	*/
	function delete_hooks()
	{	
		//grab hook id
		if ( ! $hook_id =  $this->EE->input->get_post('file_id'))
		{
			show_error( $this->EE->lang->line('No_Id_Passed'));
		}
		//get hook data from DB
		$query = $this->EE->db->query("SELECT * FROM exp_customhooks_existing_hooks WHERE hook_id = $hook_id");

		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$dir = $row['dir'];
				$hook_name = $row['hook_name'];
				$function = $row['function'];
				
				//get file contents
				$file = file_get_contents($dir);
		//build hook string ready for removal
		$hook_str = '// -------------------------------------------
// CUSTOM HOOKS Hook
//
	$CH =& get_instance();
	
    if ($CH->extensions->active_hook("'.$hook_name.'") === TRUE)
    {
        $str = $CH->extensions->call("'.$hook_name.'");
		if ($CH->extensions->end_script === TRUE) return;
    }
	unset($CH);
//
// END CUSTOM HOOKS HOOK
// -------------------------------------------';
				//remove and re-save
				$function_string = str_replace($hook_str, '', $file);
				file_put_contents($dir, $function_string);
				//remove from database
				$query = $this->EE->db->query("DELETE FROM exp_customhooks_existing_hooks WHERE hook_id = $hook_id");
				if($this->EE->db->affected_rows() < 1)
				{
					show_error($this->EE->lang->line('db_delete_error'));
				}
				
			}
		}
		else
		{
			show_error( $this->EE->lang->line('No_Id_DB'));
		}
		//redirect to module home
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=customhooks');
	
	}
	
	/*
	* Function Loads files and returns the function requested as a string
	*
	* @params string, string
	*/
	
	function get_function_contents($file, $pfunction)
	{
		$source = file_get_contents($file);
		$tokens = token_get_all($source);

		$function = '';

		foreach ($tokens as $token) {
		   if (is_string($token)) {
			   // simple 1-character token
			   $function .= $token;
		   } else {
			   // token array
			   list($id, $text) = $token;

			   switch ($id) { 
					case T_LINE:
						$function .= $text;
				   case T_FUNCTION: 
					   // no action on comments
					   $function .= '*|*'.$text;
					   break;
				   default:
					   // anything else -> output "as is"
					   $function .= $text;
					   break;
			   }
		   }
		}
		//turn the above token string into an array
		$function_array = explode('*|*', $function);
		$function_string=''; //holder
		//search the array values for the function we want.
		for($i=0; $i<count($function_array); $i++)
		{
			if(preg_match("/function ".$pfunction."/i", $function_array[$i]))//function found
			{
				$function_string = $function_array[$i];
				$i=999999999; //make sure we break the loop
			}
		}
		return $function_string;
	}
	
	function get_subdir_files($main_dir) { 
		$exclude = array("language", "views");
		$dirs = array_diff(scandir($main_dir), array('..', '.'));
		$result = array();
		//$result['Pease Select']['']='Please Select...';
			foreach($dirs AS $dir):
				if(!is_file($main_dir.$dir) && !in_array($dir, $exclude))
				{	
					$files = $this->get_subdir_files($main_dir.$dir.'/');
					foreach($files AS $file):
						foreach($file AS $f):
						if(!in_array($f, $exclude))
							$result[$main_dir.$dir.'/'][$main_dir.$dir.'/'.$f] = $dir.'/'.$f;
						endforeach;
					endforeach;
				}
				else 
				{
					$fi = explode('.', $dir);
					if(isset($fi[1]) && $fi[1] != 'html' && $fi[1] != 'htaccess')
						if(isset($fi[2]) )
						{
							//do nothing
						}
						else
						{
							$result[$main_dir][$main_dir.$dir] = $dir; 
						}
				}
			endforeach;
			
			
		return $result; 
	} 
	
	function get_functions()
	{
		$file = $_POST['uri'];
		// var_dump($_POST['uri']);
		// die();
		$source = file_get_contents($file);
		$tokens = token_get_all($source);
		$names = ''; //array();
		$t = false;
			foreach ($tokens as $token) {
			   if (is_string($token)) {
				   // simple 1-character token - do nothing
			   } else {
				   // token array
				   list($id, $text) = $token;

				   switch ($id) { 
					   case T_FUNCTION: 
						   $t = true;
						   break;
						case T_WHITESPACE:
						case T_DOC_COMMENT:
						case T_COMMENT:
							//do nothing with comments
							break;
					   default:
						  if($t){
							$names .= $text.'|';
							$t = false;
						  }
						   break;
				   }
			   }
			}
			// $output = "<select id=functionList name=functionList>";
			// foreach($names as $function):
				// $output  .= "<option value=".$function.">".$function."</option>";
			// endforeach;
			// $output .="</select>";
		return $this->EE->output->send_ajax_response($names);
	}
}