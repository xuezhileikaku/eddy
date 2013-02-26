<?php
error_reporting(0);
$filePath = './1.png';
list($width,$height) = getimagesize($filePath);
$rs = imagecreatefrompng($filePath);
$sourceData =array();

for ($i=0; $i < $height; $i++) { 
	for ($j=0; $j < $width; $j++) { 
		$index = imagecolorat($rs, $j, $i);
		$rgb = imagecolorsforindex($rs, $index);
		//print_r($rgb);
		if ($rgb['red']<220 && $rgb['red']<220 && $rgb['red']<220) {
			//echo '1';
			$sourceData[$i][$j]=1;	
		}else{
			//echo '0';
			$sourceData[$i][$j]=0;
		}
	}
}

//去噪点 阀值2
function clear($sourceData){
	$desData = array();
	$h =count($sourceData,0);
	$w =count($sourceData[0]);

	for ($i=0; $i < $h; $i++) { 
		for ($j=0; $j < $w; $j++) { 
			$value = $sourceData[$i-1][$j]+$sourceData[$i+1][$j]+$sourceData[$i][$j-1]+$sourceData[$i][$j+1];
			if ($value>=3) {
				$desData[$i][$j] = 1;
			}else{
				$desData[$i][$j] = 0;
			}
		}
	}
	return $desData;
}

$desData = clear($sourceData);
foreach ($desData as $value) {
	foreach ($value as $v) {
		//echo $v;
	}
	//echo "<br />";
}

$im = imagecreate($width, $height);
$white = imagecolorallocate($im, 0, 0, 0);
header("Content-type: image/jpeg");
imagejpeg($im);
?>