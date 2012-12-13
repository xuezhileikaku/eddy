<?php
/*
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author:Eddy
 * Date:2012��12��10�� 
 * ������ģʽ���д�php�ű� 
 * Example��php -f caijicmd.php http://bj.h2h.cn/f2660gp1.html
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
	// ��֤url�Ϸ���
	if (! preg_match ( '/^https?:\/\/[a-zA-Z0-9\-\.]+/i', $url )) {
		echo "ִ��ʧ��!URL���Ϸ�!\r\n";
		exit ();
	}
	// ȡҳ���ҳ�����һҳ
	$snoopy = new Snoopy ();
	$snoopy->fetch ( $url );
	if (preg_match_all ( '%(?<=<a href=\'/).*?(?=\' class=\'pagerlink\')%', $snoopy->results, $matches )) {
		if (strpos ( $snoopy->results, 'βҳ' )) {
			$lastUrl = end ( $matches [0] );
		} elseif (strpos ( $snoopy->results, '��һҳ' )) {
			$len = count ( $matches [0] );
			$lastUrl = $matches [0] [$len - 2];
		} else {
			$lastUrl = end ( $matches [0] );
		}
	} else {
		$lastUrl = $url;
	}
	$snoopy = null;
	
	// ���ɴ�ѭ����ȡҳ��
	if ($lastUrl != '') {
		$sp = explode ( '.', $lastUrl );
		$spLen = count ( $sp );
		// �ж��Ƿ�ֻ��1ҳ
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
		// �ж��Ƿ�ֻ��1ҳ
		if ($sum > 1) {
			$s = substr ( $url, 0, strrpos ( $url, '/' ) );
			for($i = 1; $i <= $sum; ++ $i) {
				$yUrl [] = $s . '/' . $furl . $i . '.html'; // ƴ��url
			}
		} else {
			$yUrl [] = $url;
		}
	}
	
	echo '���ι��账��', count ( $yUrl ), "ҳ\r\n";
	ob_flush ();
	flush ();
	
	$biaotou = '��ַ' . "\t" . '����' . "\t" . '�绰' . "\t" . 'Email' . "\t" . '��ϸ��Ϣ' . "\t" . '����ʱ��' . "\t" . "\n";
	$fh = fopen ( $path.'\jilu.txt', 'a+' );
	fwrite ( $fh, $biaotou );
	fclose ( $fh );
	
	foreach ( $yUrl as $url ) {
		// ȡ��ǰҳ������д��ɼ�ҳ�����Ч����
		$snoopy = new Snoopy ();
		$snoopy->fetchlinks ( $url );
		$myurl = array ();
		
		echo "����ҳ�棺", $url,"\r\n";
		ob_flush ();
		flush ();
		
		foreach ( $snoopy->results as $v ) {
			if (strpos ( $v, 'qiuzhitie' )) {
				$myurl [] = $v;
			}
		}
		$snoopy = null;
		
		echo '��ǰҳ���账����������', count ( $myurl ) - 4, "\r\n";
		ob_flush ();
		flush ();
		
		$snoopy = new Snoopy ();
		// ǰ4��������Ч
		for($i = 0; $i < 4; ++ $i) {
			unset ( $myurl [$i] );
		}
		
		// �ɼ���ǰҳ��
		$savestr = '';
		foreach ( $myurl as $v ) {
			$snoopy->fetch ( $v );
			echo "�ɼ�ҳ�棺", $v, "\r\n";
			ob_flush ();
			flush ();
			// echo '��ַ��',$v,"\r\n";
			$savestr .= $v . "\t";
			
			if (preg_match ( '/(?<=<h1>).*(?=<\/h1>)/', $snoopy->results, $matches )) {
				// echo '���⣺',$matches[0],"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<="tel STYLE1">).*(?=<\/strong>)/', $snoopy->results, $matches )) {
				// echo '�绰��',$matches[0],"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '%(?<=EMail��</em>).*?(?=</li>)%', $snoopy->results, $matches )) {
				// Email
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<=p" >)\s*.*\s*(?=<\/p>)/', $snoopy->results, $matches )) {
				// echo '��ϸ��Ϣ��',trim($matches[0]),"\r\n";
				$savestr .= trim ( $matches [0] ) . "\t";
			} else {
				$savestr .= 'NULL' . "\t";
			}
			
			if (preg_match ( '/(?<=����ʱ�䣺).*?(?=&nbsp)/', $snoopy->results, $matches )) {
				// ����ʱ��
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
		echo '���ݴ���', "\r\n";
		ob_flush ();
		flush ();
	}
	echo '�ɼ���ϣ�',"\r\n";
}
?>



