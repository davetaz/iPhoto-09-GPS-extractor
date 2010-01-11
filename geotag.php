<?php

	// Change this to your username.
	$username = "username";


	// Your iPhoto Library, shouldn't need, editing.
	$iphoto_path_originals = "/Users/$username/Pictures/iPhoto Library/Originals/";
	$iphoto_path_modified = "/Users/$username/Pictures/iPhoto Library/Modified/";


	// Do not edit below this line
	if ($argv[1] == "") {
		echo "Missing Path to Originals e.g. 2009/Bath 2009";
		exit();
	}
	$event = $argv[1];	

	$iphoto_path_originals .= $event;
	$iphoto_path_modified .= $event;

	if (is_dir($iphoto_path_originals)) {
		echo "Added PATH " . $iphoto_path_originals . "\n";
		$paths[] = $iphoto_path_originals;
	}
	if (is_dir($iphoto_path_modified)) {
		echo "Added PATH " . $iphoto_path_modified . "\n";
		$paths[] = $iphoto_path_modified;
	}

	$flag = 0;
	$i=2;
	while ($flag != 1) {
		$path = $iphoto_path_originals . "_" . $i;
		if (is_dir($path)) {
			echo "Added PATH " . $path . "\n";
			$paths[] = $path;
		} else {
			$flag = 1;
		}
		$i++;
	}

	$flag = 0;
	$i=2;
	while ($flag != 1) {
		$path = $iphoto_path_modified . "_" . $i;
		if (is_dir($path)) {
			echo "Added PATH " . $path . "\n";
			$paths[] = $path;
		} else {
			$flag = 1;
		}
		$i++;
	}

	$done = 0;
	$handle = opendir(".");
	if ($handle) {
		while (false !== ($file = readdir($handle))) {
			if (strlen($file) < 4) {
				next;
			}
			if (substr($file,strlen($file)-3,strlen($file)) != "jpg") {
				next;
			} else {
				$prefix = substr($file,0,strpos($file,"."));
				echo "$prefix\n";
				for ($i=0;$i<count($paths);$i++) {
					$path = $paths[$i];
					if (file_exists($path."/".$prefix.".jpg")) {
						$return = "";
						$cmd = "exiftool -tagsfromfile $prefix.jpg -GPSLatitudeRef -GPSLongitudeRef -GPSLatitude -GPSLongitude \"$path/$prefix.jpg\"";
						exec($cmd,$return);
						for ($k=0;$k<count($return);$k++) {
							if (strpos($return[$i],"image files updated") > 0) {
								$done++;
							}
							echo $return[$i] . "\n";
						}
					}
				}
			}	
		}
	}
	echo "Successfully updated $done files \n";

?>
