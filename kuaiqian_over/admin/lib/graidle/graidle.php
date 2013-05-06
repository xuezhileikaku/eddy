<?php
/*
+----------------------------------------------------------------------+
| Copyright (C) 2011 Alessio Glorioso
|
| This program is free software. You can redistribute it
| and/or modify it under the terms of the GNU General Public License
| as published by the Free Software Foundation; either version 2
| of the License, or (at your option) any later version.
|
| This program is distributed in the hope that it will be useful, but
| WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details.
+----------------------------------------------------------------------+
| Graidle v0.6 RC	http://graidle.sourceforge.net
+----------------------------------------------------------------------+
*/
include_once("graidle_color.ext.php");	#Colours Class Management

class Graidle{
	protected $legend = FALSE;			#TRUE -> show legend - FALSE -> hide legend
	protected $dim_quad = 12;			#dimensione quadrato di riferimento legenda
	protected $spacing = 6;				#spaziatura voci legenda

	protected $font_color = "#000000";	#Colore del Carattere
	protected $bg_color = "transparent";#Colore dello Sfondo
	protected $axis_color = "#696969";	#Colore degli Assi

	protected $value = Array();
	protected $type  = Array();

	protected $cvl = 0;					# variabile Current Value
	protected $mx = 0;					# variabile del massimo
	protected $mn = 0;					# variabile del minimo
	protected $cn = 0;					# variabile del numero massimo di valori in una serie

	protected $im;						# Image pointer
	protected $fontMono = FALSE;		# flag to set font type monospaced
	protected $LegendAlign;				# Legend align
	protected $BarOffset;				# Offset to make bar graph closer or further
	protected $AA;						# Anti-aliasing

	protected $LegendStrLen;			# Longest Legend String Lenght 

	protected $colours = Array();		#Colours Array

	public function __construct($title=NULL,$mass=NULL,$mnvs=NULL){
		$this->title=$title;
		$this->mass=$mass;
		$this->mnvs=$mnvs;
		/*
		graidle::setFont("./Vera.ttf");
		graidle::setFontBD("./VeraBd.ttf");
		graidle::setFontLegend("./Vera.ttf");*/
		graidle::setFont("./MSYH.TTF");
		graidle::setFontBD("./MSYH.TTF");
		graidle::setFontLegend("./MSYH.TTF");
		graidle::setLegMaxLen(64);
		graidle::setBarOffset(0);
		$this->colours = Colours::getColours();
		$this->width=NULL;
		$this->height=NULL;
		$this->xAxis=NULL;
		$this->yAxis=NULL;
		$this->vlx=NULL;
		$this->legend=NULL;
		$this->filled=NULL;
		$this->sx=0;
		$this->sy=0;
		$this->mx=0;
		$this->mn=0;
		$this->larg=20;
		$this->cnt=0;
		isset($this->title) ? $this->a=$this->font_big*2 : $this->a=10;
		$this->b=10;
		$this->s=10;
		$this->d=10;
		$this->LegendAlign="right";
	}
	public function create(){
		if(in_array("b",$this->type)||in_array("l",$this->type))
		{
			for($bar=$i=0;$i < count($this->type);$i++)	if($this->type[$i]=='b')	$bar+=1;

			$this->disbar=($this->larg*$bar)-(($this->BarOffset*$this->larg)*($bar-1));
			$this->ld=$this->larg+$this->disbar;	# variabile di comodo #

			if((in_array("l",$this->type))&&($bar==0)){
				$this->disbar=2*$this->larg;
				$this->ld=$this->disbar;			# variabile di comodo #
			}

			if(!isset($this->mass))	$this->mass=$this->mx;
			if(!isset($this->mnvs))	$this->mnvs=$this->mn;
			if(isset($this->name))	graidle::setLegend($this->name);

			if(!isset($this->dvx)){
				if($this->mass<=1)							$this->dvx=round($this->mass/5,1);
				else if(($this->mass>1)&&($this->mass<10))	$this->dvx=1;
				else										$this->dvx=(int) ($this->mass/10);
			}

			if($this->mx>0){
				if($this->mass==$this->mx)		$this->scarmax=$this->dvx+1;						#considerare se mettere zero o un valore o rimettere dvx
				else							$this->scarmax=$this->mass-$this->mx;
			}
			$this->scarmin=$this->mn;

			if($this->mn<0){
				if($this->mnvs>0 || !isset($this->mnvs))				$this->scarmin=0;
				else if($this->mnvs>$this->mn||$this->mnvs<$this->mn)	$this->scarmin=$this->mnvs-$this->mn;
				else 													$this->scarmin=-1;
			}

			if(strlen($this->mn)>strlen($this->mx))	$this->y_flag=strlen($this->mn);
			else									$this->y_flag=strlen($this->mx);

			if(!isset($this->w))	graidle::setWidth(600);

			graidle::setFontBaseSize((int)(($this->w/100)*(96/72)));

			$this->s += ($this->font_small*(graidle::stringLen($this->mass)+1));

			$actualWidth = ($this->ld*$this->cnt)+$this->s+$this->d;
			$precision = 0.05;

			if($actualWidth >= $this->w)	{ $eval = "return \$actualWidth >= \$this->w;"; $precision*=-1; }
			else							{ $eval = "return \$actualWidth  < \$this->w;"; }

			eval($eval) ? $cond = TRUE : $cond = FALSE;

			while($cond) {
				$this->larg += $precision;
				$this->disbar=($this->larg*$bar)-(($this->BarOffset*$this->larg)*($bar-1));
				$this->ld = $this->larg + $this->disbar;
				$actualWidth = ($this->ld*$this->cnt)+$this->s+$this->d;
				eval($eval) ? $cond = TRUE : $cond = FALSE;
			}

			if(!isset($this->h))	$this->h= (int) ((10/16)*$this->w);

			$this->b += 3*$this->font_small;
			$this->a  = 2*$this->font_big;

			if($this->mnvs>0&&$this->mass>0)	$this->mul=($this->h-$this->a-$this->b)/($this->mass-$this->mnvs);
			else								$this->mul=($this->h-$this->a-$this->b)/(($this->mass+$this->scarmax)+(abs($this->mn)-$this->scarmin));

			$this->div=$this->dvx*$this->mul;

			$this->im=imagecreatetruecolor($this->w,$this->h);

			graidle::allocateColours();
			graidle::drawBackground();
			graidle::drawTitle($this->title,$this->xAxis,$this->yAxis);
			graidle::gradAxis($this->sx,$this->sy);

			if(isset($this->legend) || isset($this->name))	graidle::legend();

			if(in_array("b",$this->type)){
				include_once("graidle_histo.ext.php");
				histogram::drawHisto();
			}
			graidle::drawAxis();
			if(in_array("l",$this->type)){
				include_once("graidle_line.ext.php");
				if(!isset($this->AA))	graidle::setAA(2);
				line::drawLine();
			}
		}
		else if(in_array("hb",$this->type))
		{
			for($bar=$i=0;$i < count($this->type);$i++)		if($this->type[$i]=='hb')	$bar+=1;

			$this->disbar=$this->larg*$bar;

			if(isset($this->name))	graidle::setLegend($this->name);

			if(!isset($this->mass))	$this->mass=$this->mx;
			if(!isset($this->mnvs))	$this->mnvs=$this->mn;

			if(!isset($this->dvx)){
				if($this->mass<=1)							$this->dvx=round($this->mass/5,1);
				else if(($this->mass>1)&&($this->mass<10))	$this->dvx=1;
				else										$this->dvx=(int)($this->mass/10);
			}

			if(!isset($this->h))	graidle::setHeight(500);
			if(!isset($this->w))	graidle::setWidth((int)($this->h*(16/10)));

			graidle::setFontBaseSize((int)(($this->w/100)*(96/72)));

			$this->b += 2*$this->font_small;
			$this->d += (int) (graidle::StringLen($this->mass)*($this->font_small/4));

			if(isset($this->vlx)){
				for($maxlen=$i=0;$i<=count($this->vlx);$i++){
					if(isset($this->vlx[$i])){
						$curlen=((graidle::stringlen($this->vlx[$i])+1)*$this->font_small);
						if($maxlen<$curlen)
							$maxlen=$curlen;
					}
				}
				$this->s+=$maxlen;
			}
			else	$this->s+=$this->font_small*2;

			if(isset($this->yAxis))	$this->s += $this->font_small;
			if(isset($this->xAxis))	$this->b += $this->font_small*2;
			if(isset($this->title))	$this->a  = 2*$this->font_big;

			$this->ld=$this->larg+$this->disbar;	# variabile di comodo #

			$actualHeight = ($this->ld*$this->cnt)+$this->a+$this->b;
			$precision = 0.05;

			if($actualHeight >= $this->h)	{ $eval = "return \$actualHeight >= \$this->h;"; $precision*=-1; }
			else							{ $eval = "return \$actualHeight  < \$this->h;"; }

			eval($eval) ? $cond = TRUE : $cond = FALSE;

			while($cond) {
				$this->larg += $precision;
				$this->disbar=($this->larg*$bar)-(($this->BarOffset*$this->larg)*($bar-1));
				$this->ld = $this->larg + $this->disbar;
				$actualHeight = ($this->ld*$this->cnt)+$this->a+$this->b;
				eval($eval) ? $cond = TRUE : $cond = FALSE;
			}

			if($this->mnvs>0&&$this->mass>0)	$this->mul=($this->w-$this->s-$this->d)/($this->mass-$this->mnvs);
			else								$this->mul=($this->w-$this->s-$this->d)/(($this->mass)+abs($this->mnvs));

			$this->im=imagecreatetruecolor($this->w,$this->h);
			graidle::allocateColours();
			graidle::drawBackground();
			graidle::drawTitle($this->title,$this->xAxis,$this->yAxis);

			if(isset($this->legend) || isset($this->name))	graidle::legend();

			include_once("graidle_horizhisto.ext.php");
			HorizHistogram::gradAxis($this->sx,$this->sy);
			HorizHistogram::drawHorizHisto();
			HorizHistogram::drawAxis();
		}
		else if(in_array("p",$this->type))
		{
			include_once("graidle_pie.ext.php");

			for($this->pie=$i=0;$i < count($this->type);$i++)	if($this->type[$i]=='p')	$this->pie+=1;

			if(!isset($this->incl))	graidle::setInclination(55);
			if(!isset($this->AA))	graidle::setAA(4);
			if(!isset($this->w))	graidle::setWidth(500);
			if(!isset($this->h))	graidle::setHeight($this->w*(4/5));

			graidle::setFontBaseSize((int)(($this->w/100)*(96/72)));

			$this->tre_d=0;
			if($this->incl<90)		$this->tre_d=(int)(($this->incl)/5);

			$this->radius=$this->w;

			$e=sin(deg2rad($this->incl));
			$rapp=pow($e,2);
			$a=$this->radius;
			$b=$a*$rapp;

			while( $a >= ($this->w-$this->s-$this->d)){
				$a-=1;
				$this->radius=$a;
				$b=$a*$rapp;
			}

			while( ($b*$this->pie) > $this->h-($this->a)-($this->pie*$this->b)-($this->pie*$this->tre_d)){
				$b-=1;
				$a=$b/$rapp;
				$this->radius=$a;
			}

			$this->im=imagecreatetruecolor($this->w,$this->h);	#<----CREO L'IMMAGINE PER IL GRAFICO A TORTA

			graidle::allocateColours();
			graidle::drawBackground();
			graidle::drawTitle($this->title);

			if(isset($this->legend))	graidle::legend();

			pie::drawPie($a,$b);
		}
		else if(in_array("s",$this->type))
		{
			include_once("graidle_spider.ext.php");

			if(!isset($this->mass))		$this->mass=$this->mx;
			if(!isset($this->filled))	graidle::setFilled(TRUE);
			if(!isset($this->AA))		graidle::setAA(4);
			if(!isset($this->w))
				if(isset($this->h))	$this->w=(int)($this->h*(5/4));
					else			$this->w=500;
			if(!isset($this->h))		$this->h=(int)($this->w*(4/5));
			if(isset($this->name))		graidle::setLegend($this->name);

			graidle::setFontBaseSize((int)(($this->w/100)*(96/72)));

			if(!isset($this->dvx)){
				if(($this->mass/10)<1)	$this->dvx=round($this->mass/5,1);
				else					$this->dvx=(int) ($this->mass/10);
			}

			$this->radius=$this->w-$this->s-$this->d;

			while($this->radius >= ($this->h-$this->a-$this->b))	$this->radius-=1;

			$this->radius=(int)($this->radius/2);

			$this->im=imagecreatetruecolor($this->w,$this->h);	#<----CREO L'IMMAGINE PER IL GRAFICO A TORTA

			graidle::allocateColours();
			graidle::drawBackground();
			graidle::drawTitle($this->title);

			if(isset($this->legend))	graidle::legend();

			spider::drawSpider();
		} 
	}
	public function carry(){
		header("Content-type: image/png");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		imagepng($this->im);
		imagedestroy($this->im);
	}
	public function carry2file($patch=NULL,$fname=NULL){
		
		if(!isset($fname))	$fname="graidle_".substr(base64_encode(rand(1000000000,9999999999)),0,10);
		if(!isset($patch))	$patch="./tmp/";

		is_dir($patch)		or die("<pre><b>GRAIDLE ERROR:</b> Patch ($patch) not is a Directory.</pre>");
		is_writable($patch)	or die("<pre><b>GRAIDLE ERROR:</b> Directory ($patch) not is Writable.</pre>");

		$fname=trim($fname).".png";
		$patch=trim($patch);
		if($patch{strlen($patch)-1}!="/")	$patch.="/";
		imagepng($this->im,$patch.$fname);
		imagedestroy($this->im);
		return "$patch$fname";
	}
	private function allocateColours(){
		
		if(isset($this->axis_color))	{ $rgb=Colours::hex2rgb($this->axis_color); $this->axis_color=imagecolorallocate($this->im,$rgb[0],$rgb[1],$rgb[2]); }
		if(isset($this->font_color))	{ $rgb=Colours::hex2rgb($this->font_color); $this->font_color=imagecolorallocate($this->im,$rgb[0],$rgb[1],$rgb[2]); }
		
	}
	private function drawTitle($title=NULL,$xAxis=NULL,$yAxis=NULL){
		if($xAxis!="" || $xAxis!=NULL)	imagefttext($this->im , $this->font_small , 0 , ($this->w-$this->d)-(graidle::stringlen($xAxis)*$this->font_small) , $this->h-$this->font_small , $this->font_color , $this->fontBd , $xAxis);
		if($yAxis!="" || $yAxis!=NULL)	imagefttext($this->im , $this->font_small , 90 , $this->s-(2+graidle::stringLen($this->y_flag)*$this->font_small)-$this->font_small-50 , $this->a+(graidle::stringlen($yAxis)*$this->font_small) , $this->font_color , $this->fontBd , $yAxis);
		if($title!="" || $title!=NULL)	imagefttext($this->im , $this->font_big , 0 , ($this->w/2)-((graidle::stringlen($title)*($this->font_big))/2) , $this->font_big+5 , $this->font_color , $this->fontBd , $title);
	}
	protected function drawBackground($image=NULL){
		if(!isset($image))	$image = $this->im;

		if($this->bg_color=="transparent"){
			imagealphablending($image,TRUE);
			imagesavealpha($image,TRUE);
			$colour=imagecolorallocatealpha($image,0,0,0,127);
		}
		else{
			$rgb=Colours::hex2rgb($this->bg_color);
			$colour=imagecolorallocate($image,$rgb[0],$rgb[1],$rgb[2]);
		}
		imagefilltoborder($image,0,0,1,$colour);
	}
	protected function drawAxis(){
		Graidle::imagelinethick($this->im , $this->s , $this->a , $this->s , $this->h-$this->b , $this->axis_color,2);

		$n=1;

		if(!isset($this->vlx))	for($i=1;$i<=$this->cnt;$i++)	$this->vlx[$i]=$i;
		else	$n=0;

		for($i=$this->s ; $i<=($this->w-$this->d) ; $i+=$this->disbar+$this->larg , $n++ ){
			if(isset($this->vlx[$n])){
				imageline($this->im , $i , $this->h-$this->b+2 , $i , $this->h-$this->b , $this->axis_color);
				imagefttext($this->im , $this->font_small , 0 , $i+(($this->larg+$this->disbar)/2)-(($this->font_small*strlen($this->vlx[$n]))/2) , $this->h-$this->b+$this->font_small+5 , $this->font_color , $this->font , $this->vlx[$n]);
			}
		}

		if($this->mnvs<=0){
			Graidle::imagelinethick($this->im , $this->s , $lu=$this->h-$this->b-(abs($this->mn+$this->scarmin)*$this->mul) , $this->w-$this->d,$lu,$this->axis_color,2);
		}
	}
	public function gradAxis($sy=NULL,$sx=NULL){
		$c=imagecolorallocatealpha($this->im,255,255,255,127);
		$style=array($c,$this->axis_color);
		imagesetstyle ($this->im, $style);

		if(strlen($this->mn)>strlen($this->mx))	$y_flag=strlen($this->mn);
		else									$y_flag=strlen($this->mx);
		#Asse x griglia secondaria e tacche
		if($sx)
			for($i=$this->s;$i<=($this->w-$this->d);$i+=$this->disbar+$this->larg)
				imageline($this->im, $i , $this->a , $i , $this->h-$this->b , IMG_COLOR_STYLED);
		#Asse Y
		if($this->mnvs<0)	$zero=(int)($this->h-$this->b-abs(($this->mn+$this->scarmin)*$this->mul));
		else				$zero=$this->h-$this->b;

		#Up zero
		imagefttext($this->im,$this->font_small,0,$this->s-(imagefontwidth($this->font_small)*strlen(0)),($zero)+($this->font_small/2),$this->font_color,$this->font,0);

		$n=($this->dvx*$this->mul);

		while($zero-$n >= $this->a)
		{
			imageline($this->im, $this->s , $zero-$n , $this->s-2 , $zero-$n ,$this->axis_color);
			if($sy)	imageline($this->im, $this->s , $zero-$n , $this->w-$this->d , $zero-$n , IMG_COLOR_STYLED);
			$v=($n/$this->mul);
			imagefttext($this->im,$this->font_small,0,$this->s-(($this->font_small)*graidle::stringlen($v)),($zero-$n)+($this->font_small/2),$this->font_color,$this->font,$v);
			$n+=($this->dvx*$this->mul);
		}
		#Under zero
		
		for($n=(int)($this->dvx*$this->mul) ; $zero+$n <= $this->h-$this->b ; $n+=$this->dvx*$this->mul)
		{
			imageline($this->im, $this->s-2 , $zero+$n , $this->s , $zero+$n ,$this->axis_color);
			if($sy)	imageline($this->im, $this->s , $zero+$n , $this->w-$this->d , $zero+$n , IMG_COLOR_STYLED);
			$v=(int)($n/$this->mul);
			imagefttext($this->im,$this->font_small,0,$this->s-(($this->font_small)*(graidle::stringlen($v))+5),($zero+$n)+($this->font_small/2),$this->font_color,$this->font,-$v);
		}
	}
	protected function legend(){
		$cla1 =	imagecolorallocatealpha($this->im,0,0,0,70);
		$cla2 =	imagecolorallocatealpha($this->im,0,0,0,100);
		$cla  =	imagecolorallocatealpha($this->im,0,0,0,110);
		$black=	imagecolorallocatealpha($this->im,0,0,0,0);

		$sp_mez=$this->spacing/2;

		if(($this->LegendAlign=="right")||($this->LegendAlign=="left"))
		{
			$x1=$this->w-$this->spacing-$this->dim_quad-$this->spch;
			$x2=$this->w-1;
			$y1=$this->a;
			$y2=($this->a)+($this->dim_quad+$this->spacing)*(count($this->legend));

			if($this->LegendAlign=="left")
			{
				$x1=0;
				$x2=$this->spacing+$this->dim_quad+$this->spch;
				$y1=$this->a;
				$y2=($this->a)+($this->dim_quad+$this->spacing)*(count($this->legend));
			}
			imagefilledrectangle($this->im, $x1 , $y1 , $x2 , $y2 , $cla);
			imagerectangle($this->im, $x1 , $y1 , $x2 , $y2 , $cla);

			for($x1+=$sp_mez,$y1+=$sp_mez,$s=1,$i=0;$i < count($this->legend);$i++,$s++,$y1+=$this->spacing)
			{
				$c=$this->colours[$i];
				list($name,$red,$green,$blue)=explode(',',$c);
				$rgb=imagecolorallocatealpha($this->im,$red,$green,$blue,12);
				imagefilledrectangle($this->im , $x1 , $y1 , $x1+$this->dim_quad , $y1+=$this->dim_quad , $rgb);
				$rgb=imagecolorallocatealpha($this->im,$red/2,$green/2,$blue/2,80);
				imagerectangle($this->im , $x1 , $y1-$this->dim_quad , $x1+$this->dim_quad , $y1 , $rgb);
				$str=(string)($this->legend[$i]);
				imagefttext($this->im , $this->font_legend , 0 , $x1+$this->dim_quad+4 , $y1-($this->dim_quad/2)+($this->font_legend/2), $this->font_color , $this->fontLeg , $str);
				imageline($this->im,$x1-($sp_mez),$y1+$sp_mez,$x2,$y1+$sp_mez,$cla);
			}
		}
		else if(($this->LegendAlign=="top")||($this->LegendAlign=="bottom"))
		{
			$CellSpace=ceil($this->dim_quad+$this->spch)*1.15;
			if($this->nrow!=count($this->legend))
			{
				for($s=1,$wleg=$CellSpace ; $this->w-$this->d-$this->s > $CellSpace*$s ; $s++)
					$wleg=(int)($CellSpace*$s);
				
				if($wleg>$CellSpace*count($this->legend))	$wleg=$CellSpace*count($this->legend);
			}
			else	$wleg=$CellSpace;

			$padding=ceil(($this->w-$this->d-$this->s)-$wleg)/2;

			$sx=(int)($this->s+$padding);
			$dx=(int)($this->w-$this->d-$padding);
			$up=$this->h-$this->spacerow-5;
			$down=$this->h-5;

			if($this->LegendAlign=="top"){
				$up=$this->a-$this->spacerow-5;
				$down=$this->a-5;
			}

			$rowsize=(int)($this->spacerow/$this->nrow);

			imagefilledrectangle($this->im, $sx , $up , $dx , $down , $cla);
			imagerectangle($this->im, $sx-1 , $up-1 , $dx+1 , $down+1 , $cla1);
			imagerectangle($this->im, $sx-2 , $up-2 , $dx+2 , $down+2 , $cla2);

			for($row=1,$s=0;$s < count($this->legend);$s++)
			{
				$c=$this->colours[$s];
				list($name,$red,$green,$blue)=explode(',',$c);
				$rgb=imagecolorallocate($this->im,$red,$green,$blue);
				$rgbA=imagecolorallocatealpha($this->im,$red/2,$green/2,$blue/2,80);
				$str=(string)($this->legend[$s]);

				if(!$s){
					$x1=$sx;
					$y1=$up+(int)($rowsize*$s);
					$x2=$x1+$CellSpace;
					$y2=$y1+$rowsize;
				}

				imagefilledrectangle($this->im, $x1 , $y1 , $x2 , $y2 , $rgbA);
				imagerectangle($this->im, $x1 , $y1 , $x2 , $y2 , $rgbA);

				imagefttext($this->im , $this->font_legend , 0 , $x1+$this->dim_quad+$this->spacing , $y1+($rowsize/2)+($this->font_small/2), $this->font_color , $this->fontLeg , $str);

				imagefilledrectangle($this->im , $x1+$sp_mez , $y1+$sp_mez , $x1+$this->dim_quad+$sp_mez , $y1+$this->dim_quad+$sp_mez , $rgb);
				imagerectangle($this->im , $x1+$sp_mez , $y1+$sp_mez , $x1+$this->dim_quad+$sp_mez , $y1+$this->dim_quad+$sp_mez , $rgbA);

				$x1=$x2;
				$x2+=$CellSpace;

				if($x1>=($dx)){
					$row+=1;
					$x1=$sx;
					$x2=$x1+$CellSpace;
					$y1=$y2;
					$y2+=$rowsize;
				}
			}
		}
	}
	protected function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1){
		if ($thick == 1) {
			return imageline($image, $x1, $y1, $x2, $y2, $color);
		}
		$t = $thick / 2 - 0.5;
		if ($x1 == $x2 || $y1 == $y2) {
			return imagefilledrectangle($image, (int)(min($x1, $x2) - $t), (int)(min($y1, $y2) - $t), (int)(max($x1, $x2) + $t), (int)(max($y1, $y2) + $t), $color);
		}
		$k = ($y2 - $y1) / ($x2 - $x1);
		$a = $t / sqrt(1 + pow($k, 2));
		$points = array(
			(int)($x1 - (1+$k)*$a), (int)($y1 + (1-$k)*$a),
			(int)($x1 - (1-$k)*$a), (int)($y1 - (1+$k)*$a),
			(int)($x2 + (1+$k)*$a), (int)($y2 - (1-$k)*$a),
			(int)($x2 + (1-$k)*$a), (int)($y2 + (1+$k)*$a),
		);
		imagefilledpolygon($image, $points, 4, $color);
		return imagepolygon($image, $points, 4, $color);
	}
	protected function stringLen($str){
		$str=(string)($str);

		if($this->fontMono==FALSE){
			for($len=$s=0;$s<strlen($str);$s++){
				$ascii=ord($str{$s});
				if($ascii==33||$ascii==39||$ascii==44||$ascii==46)		$len+=(0.5);
				else if(($ascii<126&&$ascii>96)||($ascii<48&&$ascii>31))$len+=(0.85);
				else													$len+=1;
			}
			return floor($len);
		}
		else	return strlen($str);
	}
	public function setValue($value,$type,$name=NULL,$color=NULL){
		
		if(graidle::is_multi($value)){
			while($curr = current($value)){
				if(!is_array($curr))		$curr = Array($curr);

				if(is_string(key($value)))	graidle::setValue($curr,$type,key($value));
				else						graidle::setValue($curr,$type);

				next($value);
			}
		}
		else{
			array_push($this->value,$value);
			array_push($this->type,$type);

			if(isset($name))
			{
				if($type=="p"){
					if(!isset($this->PieTitle))	$this->PieTitle=array();
					array_push($this->PieTitle,$name);
				}
				else{
					if(!isset($this->name))	$this->name=array();
					array_push($this->name,$name);
				}
			}
			if(isset($color))
			{
				$color=trim((string)($color));

				while(current($this->colours))
				{
					$currcl=(string)(current($this->colours));

					if(preg_match("/".$color."/i",$currcl))
					{
						$tmp=$this->colours[$this->cvl];
						$this->colours[$this->cvl]=current($this->colours);
						$this->colours[key($this->colours)]=$tmp;
						end($this->colours);
					}
					else	next($this->colours);
				}
				reset($this->colours);
			}

			if(max($this->value[$this->cvl])>$this->mx)		$this->mx=max($this->value[$this->cvl]);
			if(min($this->value[$this->cvl])<$this->mn)		$this->mn=min($this->value[$this->cvl]);
			if(count($this->value[$this->cvl])>$this->cnt)	$this->cnt=count($this->value[$this->cvl]);

			$this->cvl+=1;
		}
	}
	public function setHeight($height){
		if(is_numeric($height))	$this->h=$height;
	}
	public function setWidth($width){
		if(is_numeric($width))	$this->w=$width;
	}
	public function setFont($font,$size=8){
		$this->font=$font;
		graidle::setFontSmallSize($size);
		graidle::setFontBigSize($size*2);
	}
	public function setFontBD($fontbd,$size=8){
		$this->fontBd=$fontbd;
		graidle::setFontSmallSize($size);
		graidle::setFontBigSize($size*2);
	}
	public function setFontLegend($fontleg,$size=8){
		$this->fontLeg=$fontleg;
		graidle::setFontLegSize($size);
	}
	public function setFontSmallSize($size){
		if($size>0&&$size<72)	$this->font_small=(int)$size;
		else					die("<b>GRAIDLE ERROR:</b> setFontSmallSize(int) size value must be between 1 and 72</br>");
	}
	public function setFontBigSize($size){
		if($size>0&&$size<72)	$this->font_big=(int)$size;
		else					die("<b>GRAIDLE ERROR:</b> setFontBigSize(int) size value must be between 1 and 72</br>");
	}
	public function setFontLegSize($size){
		if($size>0&&$size<72)	$this->font_legend=(int)$size;
		else					die("<b>GRAIDLE ERROR:</b> setFontLegSize(int) size value must be between 1 and 72</br>");
	}
	public function setFontBaseSize($size){
		if($size>0&&$size<72){
			graidle::setFontSmallSize((int)$size);
			graidle::setFontBigSize((int)($size*2));
		}
		else					die("<b>GRAIDLE ERROR:</b> setFontBaseSize(int) size value must be between 1 and 72</br>");
	}
	public function setFontMono(){
		$this->fontMono=TRUE;
	}
	public function setBgCl($HEXcolor){
		$this->bg_color=$HEXcolor;
	}
	public function setFontCl($HEXcolor){
		$this->font_color=$HEXcolor;
	}
	public function setAxisCl($HEXcolor){
		$this->axis_color=$HEXcolor;
	}
	public function setSecondaryAxis($sx,$sy){
		if($sx)	$this->sx=1;
		if($sy)	$this->sy=1;
	}
	public function setXtitle($xAxis){
		$this->xAxis=$xAxis;
	}
	public function setYtitle($yAxis){
		$this->yAxis=$yAxis;
	}
	public function setXValue($vlx){
		$this->vlx=$vlx;
	}
	public function setInclination($incl){
		$this->incl=$incl;
	}
	public function setAA($AA){
		if($AA>8)		$this->AA = 8;
		else if($AA<2)	$this->AA = 1;
		else			$this->AA=abs((int)($AA));
	}
	public function setLegend($legend,$align=NULL){
		if(!isset($this->legend))	$this->legend = Array();
		if(!is_array($legend))		$legend = Array($legend);

		$this->legend=array_merge($this->legend,$legend);

		$spch=$this->font_legend;	#spazio per i caratteri della legenda

		for($i=0;$i < count($this->legend);$i++)
		{
			if(strlen($this->legend[$i])>$this->LegStrLen)	$this->legend[$i]=substr($this->legend[$i],0,$this->LegStrLen)."...";

			$tmpsp=graidle::stringlen($this->legend[$i])*1.10*($this->font_legend);

			if($spch<$tmpsp){
				$spch=$tmpsp;
				$this->LegendStrLen = graidle::stringlen($this->legend[$i]);
			}
		}

		if(isset($this->w))	$this->nrow=ceil((($spch+$this->dim_quad+$this->spacing)*count($this->legend))/($this->w-$this->s-$this->d));
		else				$this->nrow=count($this->legend);

		$this->spacerow=ceil($this->nrow*($this->dim_quad+$this->spacing));
		$this->spch=$spch;

		if(!is_null($align))	$this->LegendAlign=strtolower($align);

		switch($this->LegendAlign){
			case "left":	$this->s=$this->spacing+$this->dim_quad+$this->spch;break;
			case "top":		$this->a=$this->spacerow;break;
			case "bottom":	$this->b=$this->nrow*($this->dim_quad+$this->spacing);break;break;
			default:		$this->LegendAlign="right";$this->d=$this->spacing+$this->dim_quad+$this->spch;break;
		}
	}
	public function setLegMaxLen($strlen){
		$this->LegStrLen= abs((int)$strlen);
	}
	public function setExtLegend($type=0){
		switch($type){
			case 1 :	$this->ExtLeg=1;break;	#Only Percent;
			case 2 :	$this->ExtLeg=2;break;	#Both Value and Percent;
			default:	$this->ExtLeg=0;break;	#Only Value;
		}
	}
	public function setFilled($filled=1){
		if($filled==1)	$this->filled=1;
		else			$this->filled=0;
	}
	public function setFontSmall($font_small){
		if(is_numeric($font_small))	$this->font_small=$font_small;
	}
	public function setFontBig($font_big){
		if(is_numeric($font_big))	$this->font_big=$font_big;
	}
	public function setDivision($div){
		if(is_numeric($div))	$this->dvx=$div;
	}	
	public function setColor($color,$position=NULL){
		if(!is_array($color))	$color=array($color);

		if(!isset($position))			$color=array_reverse($color);
		elseif(!is_array($position))	$position=array($position);

		while($cl=current($color))
		{
			$strcl=NULL;

			if($cl{0}=="#"){
				$rgb=color::hex2rgb($cl);
				$strcl="colore,".$rgb[0].",".$rgb[1].",".$rgb[2];
			}
			elseif(strpos($cl,",")){
				$strcl="colore,".$cl;
			}

			if($strcl){
				if(isset($position) && (current($position)+1))	$this->colours[current($position)]=$strcl;
				else											array_unshift($this->colours,$strcl);
			}
			next($position);
			next($color);
		}
	}
	public function setMulticolor(){
		$this->multicolor=1;
	}
	public function setBarOffset($offset){
		$this->BarOffset = round($offset/100,2);
	}
	private function is_multi($array) {
		return (count($array) != count($array,1));
	}
}
?>