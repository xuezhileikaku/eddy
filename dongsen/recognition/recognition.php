<?php
define('WORD_SPACING',9);//字符间距
define('LEFT',10);
define('TOP',9);
define('HEIGHT',15);
define('WIDTH',10);
define('COLOR', 215);

$filePath = './code.png';
list($width,$height) = getimagesize($filePath);
$rs = imagecreatefrompng($filePath);
$sourceData =array();

//取特征值
for ($i=0; $i < $height; $i++) { 
	for ($j=0; $j < $width; $j++) { 
		$index = imagecolorat($rs, $j, $i);
		$rgb = imagecolorsforindex($rs, $index);
		//print_r($rgb);
		if ($rgb['red']>COLOR && $rgb['blue']>COLOR && $rgb['green']>COLOR) {
			//echo '1';
			$sourceData[$i][$j]=0;	
		}else{
			//echo '0';
			$sourceData[$i][$j]=1;
		}
	}
}

//去噪点 阀值5
function clear($sourceData){
	$desData = array();
	$h =count($sourceData,0);
	$w =count($sourceData[0]);

	for ($i=1; $i < $h-1; $i++) { 
		for ($j=1; $j < $w-1; $j++) { 
			$value = $sourceData[$i-1][$j]+$sourceData[$i+1][$j]+$sourceData[$i][$j-1]+$sourceData[$i][$j+1]
                +$sourceData[$i-1][$j-1]+$sourceData[$i+1][$j+1]+$sourceData[$i-1][$j+1]+$sourceData[$i+1][$j-1];
			if ($value>=5) {
				$desData[$i-1][$j-1] = 1;
			}else{
				$desData[$i-1][$j-1] = 0;
			}
		}
	}
	return $desData;
}

//字符分割
function getCH($data){

	//第一个左上角坐标
	$x = LEFT -1;
	$y = TOP -1;

	$ch=0;
	for ($i=$y; $i < $y + HEIGHT; $i++) {
		for ($j=$x; $j < $x + WIDTH; $j++) { 
			$chData[0][$ch] = $data[$i][$j];
			$ch++;
		}
	}

	$ch=0;
	for ($i=$y; $i < $y + HEIGHT; $i++) {
		for ($j=$x + WIDTH + WORD_SPACING; $j < $x + 2 * WIDTH + WORD_SPACING; $j++) { 
			$chData[1][$ch] = $data[$i][$j];
			$ch++;
		}
	}

	$ch=0;
	for ($i=$y; $i < $y + HEIGHT; $i++) {
		for ($j=$x + 2*(WIDTH + WORD_SPACING); $j < $x + 3 * WIDTH + 2 * WORD_SPACING; $j++) { 
			$chData[2][$ch] = $data[$i][$j];
			$ch++;
		}
	}

	$ch=0;
	for ($i=$y; $i < $y + HEIGHT; $i++) {
		for ($j=$x + 3*(WIDTH + WORD_SPACING); $j < $x + 4 * WIDTH + 3 * WORD_SPACING; $j++) { 
			$chData[3][$ch] = $data[$i][$j];
			$ch++;
		}
	}

	return $chData;
}

/*显示
foreach ($desData as $value) {
	foreach ($value as $v) {
		if($v==1){
			echo '■';
		} else{
			echo '□';
		}//echo $v;
	}
	echo "<br />";
}*/

/*学习功能
foreach ($chData as $value) {
	echo "'";
	foreach ($value as $v) {
		echo $v;
	}
	echo "',<br />";
}*/

//识别验证码
function vertifyCode($chData){

	//字模
$typehead = require './recognition/zimo.php';

	$ch = array();
	$w = count($chData[0]);
	/*单个字符识别
	for ($i=0; $i < 10; $i++) { 
		$mount=0;
		for ($j=0; $j < $w; $j++) { 
			if ($chData[0][$j]==$typehead[$i][$j]) {
				$mount++;
			}
		}
		if ($w-$mount<15) {
			$ch[0]=$i;
			break;
		}
	}*/
	
	for ($i=0; $i < 4; $i++) { 
		$result = array();
		for ($k=0; $k < 10; $k++) { 
			$mount = 0;
		if (!is_array($typehead[$k])) {
			for ($j=0; $j < $w; $j++) {
				/*
				if (is_array($typehead[$k])) {
					$subMount = 0;
					foreach ($typehead[$k] as $v) {
						for ($m=0; $m < $w; $w++) { 
							if ($chData[$i][$m] == $v[$m]) {
								$subMount++;
							}
						}
						$sub[]=$subMount;
					}
					$mount = max($sub);

				} else {*/
					if ($chData[$i][$j]==$typehead[$k][$j]) {
						$mount++;
					}
				//}
			}
		}else{
			foreach ($typehead[$k] as $v) {
				$subMount = 0;
				$sub = array();
				for ($m=0; $m < $w; $m++) { 
					if ($chData[$i][$m] == $v[$m]) {
						$subMount++;
					}
				}
				$sub[]=$subMount;
			}
			$mount = max($sub);
		}


			if ($w-$mount<3) {
				$ch[$i]=$k;
				break;
			}else{
				$result[$k]=$w-$mount;
			}
		}
		//未匹配成功则取最优结果
		if (!isset($ch[$i])) {
			$ch[$i]=current(array_keys($result,min($result)));
		}
	}
	
	return $ch;
}

$desData = clear($sourceData);
$chData = getCH($desData);
$digtal = vertifyCode($chData);

$resul = '';
foreach ($digtal as $v) {
	$resul .= $v;
}

//$_SESSION['vertify_code'] = $resul;

/*test
foreach ($desData as $value) {
	foreach ($value as $v) {
		echo $v;
	}
	echo "<br />";
}
*/

/*test
$im = imagecreate($width-OFFSET_X-RIGHT, $height - OFFSET_Y - BOTTOM);
$black = imagecolorallocate($im, 0, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);
$h = count($desData,0);
$w = count($desData[0]);

for ($i=0; $i < $h; $i++) { 
	for ($j=0; $j < $w; $j++) { 
		if ($desData[$i][$j]==1) {
			imagesetpixel($im, $j, $i, $white);
		}
	}
}
header("Content-type: image/jpeg");
imagejpeg($im);
*/


?>