<?php
/*
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author:Eddy
 * Date:2012年12月10日 
 * 命令行模式运行此php脚本 
 * Example：php -f caijicmd.php http://bj.h2h.cn/f2660gp1.html
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * *
 */
date_default_timezone_set ( 'PRC' );
error_reporting ( 0 );
set_time_limit ( 0 );

ob_end_clean ();
ob_start ();
$path = dirname(__FILE__);

require $path.'\Snoopy.class.php';
if ($argc==2) {
	$url = $argv[1];
	// 验证url合法性
	if (! preg_match ( '/^https?:\/\/[a-zA-Z0-9\-\.]+/i', $url )) {
		echo "执行失败!URL不合法!\r\n";
		exit ();
	}
	// 取页面分页的最后一页
	$snoopy = new Snoopy ();
	$snoopy->fetch ( $url );
	if (preg_match_all ( '%(?<=<a href=\'/).*?(?=\' class=\'pagerlink\')%', $snoopy->results, $matches )) {
		if (strpos ( $snoopy->results, '尾页' )) {
			$lastUrl = end ( $matches [0] );
		} elseif (strpos ( $snoopy->results, '下一页' )) {
			$len = count ( $matches [0] );
			$lastUrl = $matches [0] [$len - 2];
		} else {
			$lastUrl = end ( $matches [0] );
		}
	} else {
		$lastUrl = $url;
	}
	$snoopy = null;
	
	// 生成待循环读取页面
	if ($lastUrl != '') {
		$sp = explode ( '.', $lastUrl );
		$spLen = count ( $sp );
		// 判断是否只有1页
		if ($spLen == 2) {
			$spUrl = $sp [0];
		} else {
			$spUrl = $sp [$spLen - 2];
		}
		$furl = '';
		for($i = - 4; $i <= - 1; ++ $i) {
			$num = substr ( $spUrl, $i );
			if (is_numeric ( $num )) {
				$sum = $num;
				$furl = substr ( $spUrl, 0, strlen ( $spUrl ) + $i );
				break;
			}
		}
		$yUrl = array ();
		// 判断是否只有1页
		if ($sum > 1) {
			$s = substr ( $url, 0, strrpos ( $url, '/' ) );
			for($i = 1; $i <= $sum; ++ $i) {
				$yUrl [] = $s . '/' . $furl . $i . '.html'; // 拼接url
			}
		} else {
			$yUrl [] = $url;
		}
	}
	
	echo '本次共需处理', count ( $yUrl ), "页\r\n";
	ob_flush ();
	flush ();
	
	$biaotou = '网址' . "\t" . '标题' . "\t" . '电话' . "\t" . 'Email' . "\t" . '详细信息' . "\t" . '发布时间' . "\t" . "\n";
	$fh = fopen ( $path.'\jilu.txt', 'a+' );
	fwrite ( $fh, $biaotou );
	fclose ( $fh );
	
	foreach ( $yUrl as $url ) {
		// 取当前页面的所有待采集页面的有效链接
		$snoopy = new Snoopy ();
		$snoopy->fetchlinks ( $url );
		$myurl = array ();
		
		echo "分析页面：", $url,"\r\n";
		ob_flush ();
		flush ();
		
		foreach ( $snoopy->results as $v ) {
			if (strpos ( $v, 'qiuzhitie' )) {
				$myurl [] = $v;
			}
		}
		$snoopy = null;
		
		echo '当前页面需处理链接数：', count ( $myurl ) - 4, "\r\n";
		ob_flush ();
		flush ();
		
		$snoopy = new Snoopy ();
		// 前4条数据无效
		for($i = 0; $i < 4; ++ $i) {
			unset ( $myurl [$i] );
		}
		
		// 采集当前页面
		$savestr = '';
		foreach ( $myurl as $v ) {
			$snoopy->fetch ( $v );
			echo "采集页面：", $v, "\r\n";
			ob_flush ();
			flush ();
			// echo '地址：',$v,"\r\n";
			$savestr .= $v . "\t";
			
			if (preg_match ( '/(?<=<h1>).*(?=<\/h1>)/', $snoopy->results, $matches )) {
				// echo '标题：',$matches[0],"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<="tel STYLE1">).*(?=<\/strong>)/', $snoopy->results, $matches )) {
				// echo '电话：',$matches[0],"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '%(?<=EMail：</em>).*?(?=</li>)%', $snoopy->results, $matches )) {
				// Email
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<=p" >)\s*.*\s*(?=<\/p>)/', $snoopy->results, $matches )) {
				// echo '详细信息：',trim($matches[0]),"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<=发布时间：).*?(?=&nbsp)/', $snoopy->results, $matches )) {
				// 发布时间
				$savestr .= trim ( $matches [0] );
			} else {
				$savestr .= 'NULL';
			}
			// echo '<hr/>';
			$savestr .= "\r\n";
		}
		$snoopy = null;
		
		$fh = fopen ( $path.'\jilu.txt', 'a+' );
		fwrite ( $fh, $savestr );
		fclose ( $fh );
		echo '数据存盘', "\r\n";
		ob_flush ();
		flush ();
	}
	echo '采集完毕！',"\r\n";
}
?>



