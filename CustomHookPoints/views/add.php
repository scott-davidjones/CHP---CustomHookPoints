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

echo form_open($action_url, '', $form_hidden); ?>

<?php
    $this->table->set_template($cp_table_template);
    $this->table->set_heading(
         lang('hook_header'),
         lang('file_header'),
		 lang('function_header'));
		
		$data = array(
              'name'        => 'hook_name',
              'id'          => 'hook_name',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
			  'style' => 'width:55%;'
            );

	$this->table->add_row(
				form_input($data),
                form_dropdown('dirlist',$dir,'', "id='dirlist'"),
				'<span id="fn_holder"></span>'
            );

			echo $this->table->generate();

?>
<div class="tableFooter">
    <div class="tableSubmit">
        <?php echo form_submit(array('name' => 'submit', 'value' => 'Create', 'class' => 'submit'));?>
    </div>

</div>

<?php echo form_close()?>

