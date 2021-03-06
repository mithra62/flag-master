<?php
$lang = array(

// Required for MODULES page

'flag_master_module_name'		=> 'Flag Master',
'flag_master_module_description'	=> 'Allows visitors to flag entries and inform administrators of innapropriate or special content.',

//----------------------------------------
'dashboard' => 'Dashboard',
'profiles' => 'Profiles',
'profile' => 'Profile',
'profiles_instructions' => 'Flag Master allows you to create multiple collections, or "Profiles", to better customize things to fit your needs. Below are the existing profiles.',
'no_profiles' => 'No Profiles Found',
'add_profile' => 'Add Profile',
'view_profile' => 'View Profile',
'missing_license_number' => 'Please enter your license number. <a href="#config_url#">Enter License</a> or <a href="https://www.mithra62.com/projects/view/flag-master">Buy A License</a>',		
'invalid_license_number' => 'Your license number is invalid. Please <a href="#config_url#">enter your valid license</a> or <a href="https://mithra62.com/projects/view/flag-master">buy a license</a>.',
'edit_profile' => 'Edit Profile',
'profile_not_found' => 'Profile Not Found',	
'back_to_profile' => 'Back To Profile',	
'profile_updated' => 'Profile Updated',
'profile_not_updated' => 'Profile Couldn\'t Be Updated',
'delete_profiles_confirm' => 'Are you sure you want to delete these profiles?',
'profiles_deleted' => 'Profiles Deleted',
'profile_options_deleted' => 'Profile Option(s) Deleted',
'profiles_delete_failure' => 'Profiles Couldn\'t Be Deleted',
'profile_id_required' => 'profile_id is required!',
'invalid_profile_id' => 'Invalid profile_id',
'notify_emails' => 'Notify Emails',
'profile_added' => 'Profile Added',
'no_flagged_comments' => 'No Flagged Comments',
'no_flagged_entries' => 'No Flagged Entries',
'no_entry_flags' => 'No Entry Flags',
'first_flag' => 'First Flag',
'last_flag' => 'Last Flag',
'no_flag_options' => 'No Flag Options',
'add_option' => 'Add Option',
'option_added' => 'Option Added',	
'view_option' => 'View Option',
'no_options' => 'No Options Found',	
'edit_option' => 'Edit Option',	
'add_child_option' => 'Add Child Option',
'no_child_options' => 'No Child Options',
'back_to_option' => 'Back To Option',
'user_defined' => 'User Defined',
'delete_options_confirm' => 'Are you sure you want to delete these options?',
'delete_flags_confirm' => 'Are you sure you want to delete these flags?',
'member_only' => 'Member Only',
'user_defined_instructions' => 'If checked a textarea will be provided allowing users to enter their own option.',
'member_only_instructions' => 'If checked the option will only display to logged in visitors.',
'description_instructions' => 'Optional data about the option.',
'title_instructions' => 'The text to display next to the option form field.',
'view_entry_flags' => 'View Entry Flags',
'entry_title' => 'Entry Title',
'option_title' => 'Option Title',
'no_flags' => 'No Flags',				
'delete_selected' => 'Delete Selected',
'total_flags' => 'Total Flags',
'active' => 'Active',
'inactive' => 'Inactive',
'created_date' => 'Created Date',
'user_name' => 'Username',		
'entry' => 'Entry',
'comment' => 'Comment',	
'global_config' => 'Global Configuration',
'flag_master_date_format' => 'Date Format',
'flag_master_date_format_instructions' => 'The date format you want Flag Master to use when handling dates. Note that the format should conform to the <a href="http://expressionengine.com/user_guide/templates/date_variable_formatting.html#date-formatting-codes" target="_blank">ExpressionEngine date format</a>.',
'type' => 'Type',
'flags_deleted' => 'Flags Deleted',
'back_to_entry' => 'Back To Entry',
'name_instructions' => 'What do you want to call this Profile?',
'status_instructions' => 'If a Profile isn\'t active it won\'t be available through the module tags.',
'type_instructions' => 'What type of content will this Flag Profile be used for? Accuracy is VERY important here so check your setting.',
'notify_emails_instructions' => 'Who do you want to notify when Flag Master does something? Enter 1 email address per line.',
'view_comment_flags' => 'View Comment Flags',
'back_to_dashboard' => 'Back To Dashboard',
'back_to_comment' => 'Back To Comment',
'platform' => 'Platform',
'comment_not_found' => 'Comment Not Found',
'auto_close_threshold' => 'Auto Close Threshold',
'auto_close_threshold_instructions' => 'How many flags are required before an item is automatically closed. Leave blank or enter 0 to disable.',
'entry_status_change_notification_subject' => 'Entry Status Change',
'comment_status_change_notification_subject' => 'Comment Status Change',	
'comment_status_change_notification_message' => '
Hello,

A comment on your site had it\'s status set to closed due to Flag Master. You can view your closed comment below:
{view_url}
		
Flag Master :)
		',
		
'entry_status_change_notification_message' => '

Hello,

An entry on your site had it\'s status set to closed due to Flag Master. You can view your closed entry below:
{view_url}
		
Flag Master :)
		
		',
'flag_type' => 'Flag Type',
'flag_type_instructions' => 'What type of Flags do you want displayed?',
'view_entry_comment_flags' => 'Entry Flagged Comments',
'settings' => 'Settings',
'no_flags_found' => 'No Flags Found',
'title' => 'Title',
'notification_prefs' => 'Notification Preferences',
'general' => 'General',
'setting' => 'Setting',
'value' => 'Value',
'notify_email_subject_copy' => '{site_name} - Flagged Item',
'notify_email_message_copy' => 'Hello,
		
An item has been flagged on {site_name} under {profile_name}.

--start--
{flagged_item}
--stop--
		
Details
Username: {username}
Option: {option_title}
User Defined: {user_defined}
IP: {ip_address}
Agent: {user_agent}

Flag URL:
{flag_url}
		
Profile URL:
{profile_url}
		
Entry URL:
{entry_url}
		
Copy and paste the URL in a new browser window if you can\'t click on it.
		
{site_name}
{site_url}
		
Please don\'t respond to this email; all emails are automatically deleted. 	',	

'notify_email_multiplier' => 'Flag Notifier Multiplier',
'notify_email_multiplier_instructions' => 'How often do you want to be notified of a flag? Every 3 flags? 5 flags?',
'notify_email_subject' => 'Email Subject',
'notify_email_subject_instructions' => 'The subject line for the flag notification email. You can use basic EE tags.',
'notify_email_message' => 'Email Message',
'notify_email_message_instructions' => 'The email message you want sent. You can use basic EE tags only. ',
'notify_email_mailtype' => 'Email Mailtype',
'notify_email_mailtype_instructions' => 'The format of the email being sent. ',
''=>''
);