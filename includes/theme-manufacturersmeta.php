<?php

/*-----------------------------------------------------------------------------------

	Metaboxes for Manufacturers

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$prefix = 'my_';
 
$meta_box_manufacturers = array(
	'id' => 'my-meta-box-manufacturers',
	'title' =>  __('Personal Options', 'theme1843'),
	'page' => 'manufacturers',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
	array(
    	   'name' => __('URL', 'theme1843'),
    	   'desc' => __('Input URL.', 'theme1843'),
    	   'id' => $prefix . 'manufacturers_url',
    	   'type' => 'text',
    	   'std' => ''
    	)
	)
);

add_action('admin_menu', 'my_add_box_manufacturers');


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function my_add_box_manufacturers() {
	global $meta_box_manufacturers;
	
	add_meta_box($meta_box_manufacturers['id'], $meta_box_manufacturers['title'], 'my_show_box_manufacturers', $meta_box_manufacturers['page'], $meta_box_manufacturers['context'], $meta_box_manufacturers['priority']);

}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function my_show_box_manufacturers() {
	global $meta_box_manufacturers, $post;
 	
	echo '<p style="padding:10px 0 0 0;">'.__('Please fill additional fields for post. ', 'theme1843').'</p>';
	// Use nonce for verification
	echo '<input type="hidden" name="my_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_manufacturers['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr style="border-top:1px solid #eeeeee;">',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr style="border-top:1px solid #eeeeee;">',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" rows="8" cols="5" style="width:100%; margin-right: 20px; float:left;">', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '</textarea>';
			
			break;
			
			//If Select	
			case 'select':
			
				echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			
				echo'<select id="' . $field['id'] . '" name="'.$field['id'].'">';
			
				foreach ($field['options'] as $option) {
					
					echo'<option';
					if ($meta == $option ) { 
						echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				
				} 
				
				echo'</select>';
			
			break; 
			
		}

	}
 
	echo '</table>';
}

 
add_action('save_post', 'my_save_data_manufacturers');


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function my_save_data_manufacturers($post_id) {
	global $meta_box_manufacturers;
 
	// verify nonce
	if (!wp_verify_nonce($_POST['my_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_manufacturers', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
 
	foreach ($meta_box_manufacturers['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
}