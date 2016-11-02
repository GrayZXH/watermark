<?php 
	/**
	* 图片添加文字/图片水印

	*/
	class ImageWatermark
	{	

	private $image;//图片
	private $type;//图片类型的文件后缀
	private $image_info;//储存背景图片的文件信息


	function __construct($dst_src){
		$this->image_info=getimagesize($dst_src);
		$this->type=image_type_to_extension($this->image_info[2],false);
		$createfrom='imagecreatefrom'.$this->type;
		$this->image=$createfrom($dst_src);
	}


	/*生成图片水印*/
	function fontMark($text,$fontfile,$alpha=0,$fontsize=28,$pos=0){//$text水印内容，$fontfile水印字体路径，$alpha水印透明度（0-127），$fontsize水印文字大小，$pos水印位置
	$text_info=imagettfbbox($fontsize, 0, $fontfile, $text);
	switch ($pos) {
		case '1'://上左
			$loc['x']=5;
			$loc['y']=5-$text_info[7];
			break;
		case '2'://上中
			$loc['x']=(($this->image_info[0]/2)-($text_info[4]/2));
			$loc['y']=5-$text_info[5];
			break;
		case '3'://上右
			$loc['x']=$this->image_info[0]-$text_info[4]-5;
			$loc['y']=5-$text_info[5];
			break;
		case '4'://正中
			$loc['x']=(($this->image_info[0]/2)-($text_info[4]/2));
			$loc['y']=(($this->image_info[1]/2)-($text_info[7]/2));
			break;
		case '5'://下左
			$loc['x']=5;
			$loc['y']=$this->image_info[1]-5;
			break;
		case '6'://下中
			$loc['x']=(($this->image_info[0]/2)-($text_info[4]/2));
			$loc['y']=$this->image_info[1]-5;
			break;
		default://下右
			$loc['x']=$this->image_info[0]-$text_info[2]-10;
			$loc['y']=$this->image_info[1]-10;
			break;
	}
	$color=imagecolorallocatealpha($this->image, 255, 255, 255, $alpha);//水印的颜色，这里选择的是白色
	imagettftext($this->image, $fontsize, 0, $loc['x'],$loc['y'], $color, $fontfile, $text);//写入水印内容
	$create='image'.$this->type;
	header("content-type:image/".$this->type);//头文件（必须 如果没有的话 有些浏览器就显示不出来）
	$create($this->image);//生成图片
	}


/*	生成图片水印 */
/*这里不能调节图片的透明度 如果用imagecopymerge函数的话PNG图片不能正常显示 这个问题待解决*/
	function picMark($src_im,$dst_x=30,$dst_y=30){// $src_im 水印图片路径  $dst_x开始显示水印的坐标X,$dst_y开始显示水印的坐标Y
		$src_info=getimagesize($src_im);
		$src_x=0;
		$src_y=0;
		$src_w=$src_info[0];
		$src_h=$src_info[1];
		$src_type=image_type_to_extension($src_info[2],false);
		$func="imagecreatefrom".$src_type;
		$src_im=$func($src_im);
		imagecopy($this->image, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);//水印的起始结束位置默认设置为了整张图片 需要的话可以手动调节
		$create='image'.$this->type;
		header("content-type:image/".$this->type);
		$create($this->image);
		imagedestroy($src_im);//销毁图片资源

	}

	function __destruct(){
		imagedestroy($this->image);
	}


	}


 ?>