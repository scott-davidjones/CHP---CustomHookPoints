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

if (count($hooks) > 0): ?>
<?php echo form_open($action_url, '', $form_hidden); ?>


<?php
    $this->table->set_template($cp_table_template);
    $this->table->set_heading(
        lang('hook_header'),
        lang('dir_header'),
		lang('function_header'),
		lang('links'));

    foreach($hooks as $hook)
    {
        $this->table->add_row(
				$hook['hook_name'],
                $hook['dir'],
				$hook['function'],
				'<a href="'.$hook['delete_link'].'">Delete</a>'
            );
    }

echo $this->table->generate();

?>

<div class="tableFooter">
    <div class="tableSubmit">
    </div>

    <span class="js_hide"><?=$pagination?></span>
    <span class="pagination" id="filter_pagination"></span>
</div>

<?php echo form_close()?>

<?php else: ?>
<?php echo lang('no_hooks_defined'); ?>
<?php endif; ?>