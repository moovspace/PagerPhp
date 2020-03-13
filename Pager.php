<?php
class Pager
{
	public $MinPerpage = 1;

	/**
	 * Get links
	 *
	 * $page Current page
	 * $records Records in table
	 * $perpage Rows per page
	 * $subpage Show sub links 2 3 4 5/66 6 7 8
	 * $subpage_nr Number of sub links
	 * $attributes Array with $_GET['attribute'] in links ?perpage=1&page=4&search=hohoho
	 * $next Next page button html
	 * $back Back page button html
	 */
	final function Links(int $page = 1, int $records = 11, int $perpage = 5, bool $subpage = true, int $subpage_nr = 3, array $attributes = ['page', 'perpage','search','edit','delete','id'], $next = 'next <i class="fas fa-chevron-right"></i>', $back = '<i class="fas fa-chevron-left"></i> back ') : string
	{
		if($page < 1)
		{
			$page = 1;
		}

		if($perpage < $this->MinPerpage)
		{
			$perpage = $this->MinPerpage;
		}

		// Count max page number
		$this->MaxPage($records, $perpage);

		// Attr
		$this->Attributes = $attributes;

		// Links
		$link = '<div class="pager">';
		$link .= $this->BackLink($page, $back);
		if($subpage){
			$link .= $this->PageLinkLeft($page, $subpage_nr);
		}
		$link .= $this->CurrPageLink($page, $this->MaxPage);
		if($subpage){
			$link .= $this->PageLinkRight($page, $subpage_nr);
		}
		$link .= $this->NextLink($page, $next);
		$link .= '</div>';

		return $link;
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $records
	 * @param integer $perpage
	 * @return void
	 */
	protected function MaxPage(int $records, int $perpage)
	{
		if($records > $perpage)
		{
			$this->MaxPage = (int) ($records / $perpage);

			if(($records % $perpage) > 0)
			{
				$this->MaxPage = $this->MaxPage + 1;
			}
		}
		else
		{
			$this->MaxPage = 1;
		}
	}

	protected function NextLink($page = 1, $icon = 'next')
	{
		if($page >= $this->MaxPage)
		{
			$page = $this->MaxPage;
		}

		$next = $page + 1;

		if($page < $this->MaxPage)
		{
			return '<a href="'.$this->Attributes().'page='.$next.'" class="nav-link next-link"> '.$icon.' </a>';
		}
		else
		{
			return '';
		}
	}

	protected function BackLink($page = 1, $icon = 'back')
	{
		if($page < 1)
		{
			$page = 1;
		}

		$back = $page - 1;

		if($page > 1)
		{
			return '<a href="'.$this->Attributes().'page='.$back.'" class="nav-link back-link"> '.$icon.' </a>';
		}
		else
		{
			return '';
		}
	}

	protected function CurrPageLink($page = 1, $max_page = 1)
	{
		return '<a href="'.$this->Attributes().'page='.$page.'" class="nav-link curr-link"> '.$page.' / '.$max_page.'</a>';
	}

	protected function PageLinkLeft($page = 0, $nr)
	{
		$l = '';
		for ($i = $nr; $i > 0; $i--)
		{
			$p = $page - $i;
			if($p >= 1)
			{
				$l .= '<a href="'.$this->Attributes().'page='.$p.'" class="nav-link left-link"> '.$p.' </a>';
			}
		}
		return $l;
	}

	protected function PageLinkRight($page = 0, $nr)
	{
		$l = '';
		for ($i = 1; $i < $nr + 1; $i++)
		{
			$p = $page + $i;
			if($p <= $this->MaxPage)
			{
				$l .= '<a href="'.$this->Attributes().'page='.$p.'" class="nav-link right-link"> '.$p.' </a>';
			}
		}
		return $l;
	}

	protected function Attributes()
	{
		$url = '?';
		foreach($this->Attributes as $k)
		{
			if(!empty($_GET[$k]) && $k != 'page')
			{
				$url .= $k.'='.$_GET[$k].'&';
			}
		}
		return $url;
	}

	public function Style()
	{
		?>
		<style>
			.pager{
				display: block;
				float: left;
				width: 100%;
				overflow: hidden;
				text-align: center;
				padding: 10px;
			}
			.nav-link{
				font-size: 14px;
				font-weight: 900;
				line-height: 25px;
				padding: 10px;
				background: #63d600;
				color: #fff;
				margin: 5px;
				border-radius: 5px;
				transition: all .6s;
			}
			.nav-link:hover{
				background: #52b100;
			}
			.next-link, .back-link, .curr-link
			{
				background: #f60
			}
			.next-link:hover, .back-link:hover, .curr-link:hover
			{
				background: #ff6600aa
			}
		</style>
		<?php
	}
}

/*
require('Pager.php');
$o = new Pager();
echo $o->Links((int) $_GET['page'], 123, $_GET['perpage']);
echo $o->Style();
*/
?>
