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


$parts = parse_url($_SERVER['HTTP_HOST']);
$url = 'http://';
if(isset($parts['scheme']) && $parts['scheme'] == 'https')
{
	$url = 'https://';
}	
$url .= 'mithra62.com';
?>
<style>
#m62_system_error
{
	border:1px solid #bf0012;
	background:#ffbc9f;
	padding:15px 45px 15px 15px;
	font-family: Arial, Helvetica, sans-serif;
	color:#18362D;
	font-size:18px;
	margin:0 0 10px 0;
}
#m62_system_error a{
	color:#a10a0a;
	font-size:14px;
}

.fm_nav {
	//padding:			0 25px;
	float: left;
}

.fm_nav span.button {
	float:				  left;
	margin-bottom:  10px;
    margin-right: 6px;
}
 .fm_nav .nav_button {
	font-weight:	normal;
	background:		#d0d9e1;
	color:			#34424b;
}

.fm_nav span.button a.nav_button{
	color: #34424b;
    font: 12px/12px Arial, 'Hevlvetica Neue', sans-serif;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
}
.fm_nav span.button a.nav_button:hover {
	background-color:#e11842;
	text-decoration:none;
	color: #fff;
}
.fm_nav span.button a.current {
	background-color:#e11842;
	text-decoration:none;
	color: #fff;
}
</style>
<link href="<?php echo $theme_folder_url; ?>third_party/cartthrob/css/cartthrob.css" rel="stylesheet" type="text/css" />