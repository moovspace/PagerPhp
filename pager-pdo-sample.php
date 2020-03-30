<?php
// php composer vendor autoload here
require('vendor/autoload.php');

use Pager\Mysql\Db;
use Pager\Settings\Config;
use Pager\Pager;

class Blog
{
	static function Html()
	{
		echo '<div id="content">';
		self::Blog();
		echo '</div>';
	}

	static function GetPosts()
	{
		$page = 1;
		$offset = 0;
		$perpage = Config::PERPAGE;

		if(!empty($_GET['page']))
		{
			$page = (int)$_GET['page'];
		}

		if(!empty($_GET['perpage']))
		{
			$perpage = (int)$_GET['perpage'];
		}

		$offset = ($page - 1) * $perpage;
		if($offset < 0)
		{
			$offset = 0;
		}

		$db = Db::GetInstance();
		$r = $db->Pdo->prepare("SELECT * FROM post ORDER BY id DESC LIMIT :offset,:perpage");
		$r->execute([':offset' => $offset, ':perpage' => $perpage]);
		return $r->fetchAll();
	}

	static function GetMaxRows()
	{
		$db = Db::GetInstance();
		$r = $db->Pdo->prepare("SELECT COUNT(*) as cnt FROM post ORDER BY id DESC");
		$r->execute();
		return $r->fetchAll()[0]['cnt'];
	}

	static function Blog()
	{
		$posts = self::GetPosts();
		$records = self::GetMaxRows();
		?>

		<div id="center">

			<div class="posts-list">
				<?php

				foreach ($posts as $k => $v) {
					echo '
					<div class="blog-post">
						<div class="social">
							<div class="bt comments">0</div>
							<div class="bt likes">0</div>
						</div>
						<img src="/media/post/'.$v['id'].'.jpg" onerror="ErrorImage(this)">
						<div class="info">
							<a href="/post/'.$v['id'].'"> <h2>'.$v['title'].'</h2> </a>
							<p>'.$v['description'].'</p>
						</div>
					</div>
					';
				}

				if(empty($_GET['page'])){
					$_GET['page'] = 1;
				}

				if(empty($_GET['perpage'])){
					$_GET['perpage'] = Config::PERPAGE;
				}

				$pager = new Pager();
				echo $pager->Links((int) $_GET['page'], $records, (int) $_GET['perpage']);
				echo $pager->Style();
				?>
			</div>

		</div>

		<?php

	}
}


// Run it
Blog::Html();

// or change function Blog() to ShowBlog();
// Blog::ShowBlog();
?>
