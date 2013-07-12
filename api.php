<html>
<head>
<style>
body { font-family: Verdana, Geneva, sans-serif; background-color: #FFF; }
h1 { font-size: 14px; font-weight: bold; color: #000; }
a { color:#000; }
#insert_box_img { float:left; width:200px; }
#insert_box_button { float:left; width:400px; }
</style>

</head>
<body>

<?
	$api_key = "c5f42c106a19a485cac10cf3d518d0d9";
	$file = getcwd() . '/' . basename( $_FILES['userfile']['name']);
	move_uploaded_file($_FILES['userfile']['tmp_name'], $file);
	list($width, $height, $file_type) = getimagesize($file);
	
	if ($file_type == 3) {
		$image = imagecreatefrompng($file);
		imagealphablending($image, false);
		imagesavealpha($image, true);
		ob_start();
		imagepng($image);
		$data =  ob_get_contents();
		ob_end_clean();
	}
	
	if ($file_type == 2) {
		$image = imagecreatefromjpeg($file);
		imagealphablending($image, false);
		imagesavealpha($image, true);
		ob_start();
		imagejpeg($image);
		$data =  ob_get_contents();
		ob_end_clean();
	}
	
	if ($file_type == 1) {
		$image = imagecreatefromgif($file);
		imagealphablending($image, false);
		imagesavealpha($image, true);
		ob_start();
		imagegif($image);
		$data =  ob_get_contents();
		ob_end_clean();
	}
	
    $pvars   = array('image' => base64_encode($data), 'key' => $api_key);
    $timeout = 30;
    $curl    = curl_init();
    $post    = http_build_query($pvars);

    curl_setopt($curl, CURLOPT_URL, 'http://imgur.com/api/upload.xml');
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $xml_raw = curl_exec($curl);
    curl_close ($curl);
    unlink($file);
	
    $xml = new SimpleXMLElement($xml_raw);
	
	if ($xml->error_code != '') {
		$imgur_error_code = $xml->error_code;
		$imgur_error_msg = $xml->error_msg;
		
		settype($imgur_error_code, "string");
		settype($imgur_error_msg, "string");
		
		echo "<h1>Error #" . $imgur_error_code . ", " . $imgur_error_msg . "</h1>";
	}
	else {
		imagedestroy($image);
		$imgur_original = $xml->original_image;
		$imgur_large_tbn = $xml->large_thumbnail;
		$imgur_small_tbn = $xml->small_thumbnail;
		$imgur_image_hash = $xml->image_hash;
		$imgur_delete_hash = $xml->delete_hash;
		$imgur_page = $xml->imgur_page;
		$img_delete_page = $xml->delete_page;
		
		settype($imgur_original, "string");
		settype($imgur_large_tbn, "string");
		settype($imgur_small_tbn, "string");
		
		?>
		
		<script language="javascript">
		
		function insert_original()
		{
			var postCode = '<img src="<?php echo $imgur_original; ?>">';
			top.send_to_editor(postCode);
			return;
		}
		
		function insert_large_tbn()
		{
			var postCode = '<a href="<?php echo $imgur_original; ?>"><img src="<?php echo $imgur_large_tbn; ?>"></a>';
			top.send_to_editor(postCode);
			return;
		}
		
		function insert_small_tbn()
		{
			var postCode = '<a href="<?php echo $imgur_original; ?>"><img src="<?php echo $imgur_small_tbn; ?>"></a>';
			top.send_to_editor(postCode);
			return;
		}
		
		</script>
		
		<div align="center">
		
			<div id="insert_box_img">
				<h1>Small thumbnail :</h1>
				<img src="<?php echo $imgur_small_tbn ?>" border="0" />

				<h1>Large thumbnail :</h1>
				<img src="<?php echo $imgur_large_tbn ?>" border="0" />
			</div>
			
			<div id="insert_box_button">
			<h1>One-click insert into post...</h1>

			<input name="insert1" type="button" onclick="insert_original()" value="Original image" />
			<br/><br/>
			<input name="insert2" type="button" onclick="insert_large_tbn()" value="Large thumbnail w/ link to original" />
			<br/><br/>
			<input name="insert3" type="button" onclick="insert_small_tbn()" value="Small thumbnail w/ link to original" />

			</div>
		  
			<div style="clear:both;"></div>
			
		</div>
		<?php
		}
		?>
</body>
</html>
