<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Bitauth Example: <?php echo ( ! empty($group) ? ( $edit == TRUE ? 'Edit' : 'View' ) : 'Add').' Group'; ?></title>
	<style type="text/css">
		body { font-family: Arial, sans-serif; font-size: 12px; }
		h2 { margin: 0 0 8px 0; }
		p { margin-top: 0; }
		form { width: 300px; margin: 0 auto 10px auto; padding: 18px; border: 1px solid #262626; }
		label, input, textarea { margin: 0; }
		label { display: block; font-weight: bold; }
		input[type=text], input[type=password], input[type=submit], textarea { margin-bottom: 12px; }
		input[type=checkbox] { margin-bottom: 4px; position: relative; top: 2px; }
		input[type=text], input[type=password], textarea { width: 100%; display: block; }
		.error { font-weight: bold; color: #F00; }
		.logininfo { width: 300px; margin: 4% auto 0 auto; }
		.creds { width: 600px; margin: 0 auto; padding: 0; }
	</style>
</head>
<body>
    <?php
		echo '<div class="logininfo"><strong>'.$bitauth->fullname.'</strong><span style="float: right;">'.anchor('bitauth_example/logout', 'Logout').'</span></div>';
		echo form_open(current_url());

		echo '<h2>BitAuth Example: '.( ! empty($group) ? ( $edit == TRUE ? 'Edit' : 'View' ) : 'Add').' Group</h2>';

		echo form_label('Group Name', 'name');
		if($edit == TRUE)
		{
			echo form_input('name', set_value('name', ( ! empty($group) ? $group->name : '')));
		}
		else
		{
			echo '<p>'.( ! empty($group) ? $group->name : 'N/A').'</p>';
		}

		echo form_label('Description', 'description');
		if($edit == TRUE)
		{
			echo form_textarea('description', set_value('description', ( ! empty($group) ? $group->description : '')));
		}
		else
		{
			echo '<p>'.( ! empty($group) ? $group->description : 'N/A').'</p>';
		}

		$permissions = $bitauth->get_permissions();
		$slugs = array_keys($permissions);
		$submitted = $this->input->post('permissions');

		echo form_label('Permissions', 'permissions');
		foreach(array_values($permissions) as $_index => $_desc)
		{
			if($edit == TRUE)
			{
				echo '<div>'.form_checkbox('permissions[]', $slugs[$_index], (isset($submitted[$_index]) || ( ! empty($group) && $bitauth->has_perm($slugs[$_index], $group->permissions)) ? TRUE : FALSE )).' '.$_desc.'</div>';
			}
			else if($bitauth->has_perm($slugs[$_index], $group->permissions))
			{
				echo '<div>'.$_desc.'</div>';
			}
		}

		if($edit != TRUE && $group->permissions == 0)
		{
			echo '<div>None</div>';
		}

		$submitted = $this->input->post('members');
		if($submitted === FALSE)
			$submitted = array();

		echo '<div style="margin-top: 12px;">'.form_label('Members', 'members').'</div>';
		foreach($bitauth->get_users() as $_user)
		{
			if($edit == TRUE)
			{
				if( in_array($_user->user_id, $submitted)
				   || ( isset($group) && in_array($_user->user_id, $group->members)))
				{
					$checked = TRUE;
				}
				else
				{
					$checked = FALSE;
				}

				echo '<div>'.form_checkbox('members[]', $_user->user_id, $checked).' '.$_user->username.' ('.$_user->fullname.')</div>';
			}
			else if(in_array($_user->user_id, $group->members))
			{
				echo '<div>'.$_user->username.' ('.$_user->fullname.')</div>';
			}
		}

		if($edit == TRUE)
		{
			echo form_submit('submit', ( ! empty($group) ? 'Save Changes' : 'Add Group' ), 'style="margin-top: 12px;"').' or '.anchor('bitauth_example/groups', 'Cancel');
		}
		else
		{
			echo '<div style="margin-top: 12px;">'.anchor('bitauth_example/groups', 'Back to Groups').'</div>';
		}

		echo ( ! empty($error) ? $error : '' );

		echo form_close();
		echo '<p class="creds">
			This example uses two sample permissions: <strong>can_edit</strong> and <strong>can_change_pw</strong> to showcase the ease of use of Bitauth.
			When logged in as adminstrator, you have full access. When logged in as the default user, you can only view user and group information, and reset user passwords.
		</p>';

	?>
</body>
</html>