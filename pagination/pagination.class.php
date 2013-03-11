<?php
/**
 * 分页类，表现层基于bootstrap
 *
 * @author Eddy
 **/
class pagination
{
	private $pageSize;		//每页显示记录数
	private $totalCount;	//总记录数
	private $pageUrl;		//页面链接
	private $pageNavNum;	//导航条条目数
	public  $pageNum;		//总页数
	public  $curPageNum;	//当前页面

	//构造函数
	public function __construct($totalCount,$pageSize,$pageUrl,$pageNavNum){
		$this->totalCount = $totalCount;
		$this->pageSize = $pageSize;
		$this->pageUrl = $pageUrl;
		$this->pageNavNum = $pageNavNum > 3 ? $pageNavNum : 3;
		$this->pageNum = ceil($totalCount/$pageSize);
	}

	//获取分页导航链接数组
	private function getNavUrlArr(){
		$navUrl = array();
		if ($this->pageNum > $this->pageNavNum) {
			if ($this->pageNum - $this->curPageNum < $this->pageNavNum) {
				$start = $this->pageNum - $this->pageNavNum + 1;
			} else {
				$start = $this->curPageNum;
			}
			
			for ($i=$start; $i < min($this->pageNavNum + $this->curPageNum,$this->pageNum + 1); $i++) { 
				if($this->curPageNum == $i){
					$navUrl[] = '<li class="active"><a href="' . $this->pageUrl . $i . '">' . $i . '</a></li>';
				}else{
					$navUrl[] = '<li><a href="' . $this->pageUrl . $i . '">' . $i . '</a></li>';
				}
			}
		} else {
			for ($i=0; $i < $this->pageNum; $i++) { 
				if($this->curPageNum == $i+1){
					$navUrl[] = '<li class="active"><a href="' . $this->pageUrl . ($i+1) . '">' . ($i+1) . '</a></li>';
				}else{
					$navUrl[] = '<li><a href="' . $this->pageUrl . ($i+1) . '">' . ($i+1) . '</a></li>';
				}
			}
		}
		
		return $navUrl;
	}

	//生成分页导航条
	public function generatePageNav(){
		$navStr = '';
		$midStr = '';
		$navStr .= '<div class="pagination"><ul>';
		if ($this->totalCount <= 0) {
			return null;
		}
		$navUrl = $this->getNavUrlArr();
		foreach ($navUrl as $v) {
			$midStr .= $v;
		}
		if ($this->curPageNum == 1) {
			$navStr .= $midStr . '<li><a href="' . $this->pageUrl . ($this->curPageNum+1) . '">下一页&gt;</a></li>' .
				'<li><a href="' . $this->pageUrl . $this->pageNum . '">末页</a></li>';
		} else if ($this->curPageNum == $this->pageNum) {
			$navStr .= '<li><a href="' . $this->pageUrl . '1">首页</a></li>' . 
				'<li><a href="' . $this->pageUrl . ($this->curPageNum-1) . '">&lt;上一页</a></li>' . $midStr;
		} else {
			$navStr .= '<li><a href="' . $this->pageUrl . '1">首页</a></li>' .
				'<li><a href="' . $this->pageUrl . ($this->curPageNum-1) . '">&lt;上一页</a></li>' . $midStr . 
				'<li><a href="' . $this->pageUrl . ($this->curPageNum+1) . '">下一页&gt;</a></li>' .
				'<li><a href="' . $this->pageUrl . $this->pageNum . '">末页</a></li>';
		}
		$navStr .= '</ul></div>';
		return $navStr;
	}
} // END class pagination
?>