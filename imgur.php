<?php
/*
Plugin Name: imgur
Plugin URI: https://github.com/paulomcnally/imgur-wordpress-plugin
Description: Permite subir imagenes a imgur.com
Version: 1.0
Author: Paulo McNally
Author URI: http://www.mcnallydevelopers.com
*/

add_filter('media_upload_tabs', 'imgur_add_tab');
add_action('media_upload_imgur', 'imgur_get_tab');

function imgur_add_tab($tabs) {
	$tabs['imgur'] = __("Subir a imgur.com","imgur");
	return $tabs;
}

function imgur_get_tab($type = 'image') {
	$current_api_key = "c5f42c106a19a485cac10cf3d518d0d9";
	$plugin_path = plugins_url() . '/' . plugin_basename(dirname(__FILE__)) . '/';
	?>

    	<html>
    	<body>
	<style type="text/css">
	body { font-family: Verdana, Geneva, sans-serif; font-size:12px; color:#000; background:#f9f9f9; margin:0px; padding:0px; }
	a { color:#21759b }
	a:hover { color:#d54e21; }
	h1 { font-size: 18px; font-weight: bold; }
	h2 { font-size: 14px; font-weight: bold; }
	#media-upload-header ul { margin-left:21px; margin-top:11px; margin-bottom:8px; padding:0px; }
    #media-upload-header li { display:inline; margin-right:8px; }
    #media-upload-header a { text-decoration:none; }
	#content { background:#FFF; border-top:#dfdfdf solid 1px; border-bottom:#dfdfdf solid 1px; padding:25px; }
    .current { color:#d54e21; background-color:#FFF; padding:5px 10px 10px 10px; border-top:#dfdfdf solid 1px; border-left:#dfdfdf solid 1px; border-right:#dfdfdf solid 1px; }
    </style>

    <?php media_upload_header();?>
	
    	<div id="content" align="center">
        <h1>Choose image to upload :</h1>
        <form enctype="multipart/form-data" action="<?php echo $plugin_path; ?>api.php?api=<?php echo $current_api_key; ?>" method="POST">
        <input name="userfile" type="file" /><br /><br />
        <input type="submit" value="Upload to imgur.com" />
        </form>
        </div>

    </body>
    </html>

    <?php } ?>
