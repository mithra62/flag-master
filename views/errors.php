<?php
if(count($errors) >= 1)
{		
	foreach($errors AS $error)
	{
		echo '<div id="m62_system_error">';
		$replace = array('#config_url#');
		$paths = $url_base.'settings';
		$str = str_replace($replace, $paths, lang($error));
		echo $str;
		if(count($errors) > 1)
		{
			echo '<br />';
		}
		echo '</div>';
	}
}
?>