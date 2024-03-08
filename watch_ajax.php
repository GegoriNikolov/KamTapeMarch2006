<?php
require "needed/scripts.php";
if(isset($_GET['action_get_honors'])) {
$category = $conn->prepare("SELECT category FROM videos WHERE vid = ?");
$category->execute([$_GET['v']]);
$category = $category->fetchColumn();
switch($category) {
					case '1':
						$catname = "Arts &amp; Animation";
						break;
					case '2':
						$catname = "Autos &amp; Vehicles";
						break;
					case '23':
						$catname = "Comedy";
						break;
					case '24':
						$catname = "Entertainment";
						break;
				    case '10':
						$catname = "Music";
						break;
				    case '25':
						$catname = "News &amp; Blogs";
						break;
				    case '22':
						$catname = "People";
						break;
				    case '15':
						$catname = "Pets &amp; Animals";
						break;
				    case '26':
						$catname = "Science &amp; Technology";
						break;
				    case '17':
						$catname = "Sports";
						break;
				    case '19':
						$catname = "Travel &amp; Places";
						break;
				    case '20':
						$catname = "Video Games";
						break;
					default:
						$catname = "Entertainment";
				}
// HONORS
    $video_honors = [];
    /* Honors: Recently Featured */
    $really_featured = $conn->query(
	"SELECT * FROM picks 
	LEFT JOIN videos ON videos.vid = picks.video
	WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY picks.video
	ORDER BY picks.featured DESC LIMIT 100"
	);
    $really_featured_c = $conn->prepare(
	"SELECT * FROM picks 
	LEFT JOIN videos ON videos.vid = picks.video
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.category = ? GROUP BY picks.video
	ORDER BY picks.featured DESC LIMIT 100"
	);
	$really_featured_c->execute([$category]);

    if($really_featured) {
    $really_featured = $really_featured->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($really_featured, 'vid'));
    unset($really_featured);
	if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Recently Featured - All", "url" => "t=&page=" . $pagenum . "&s=rf&c=0&l="];
    }
	}
    if($really_featured_c) {
    $really_featured_c = $really_featured_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($really_featured_c, 'vid'));
    unset($really_featured_c);
	if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Recently Featured - " . $catname . " - All", "url" => "t=&page=" . $pagenum . "&s=rf&c=" . $category . "&l="];
    }
	}

    /* Honors: Most Discussed (Today) */
    $most_discussed_t = $conn->query(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
    $most_discussed_t_c = $conn->prepare(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) AND videos.category = ?) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
	$most_discussed_t_c->execute([$category]);

    if ($most_discussed_t) {
    $most_discussed_t = $most_discussed_t->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_t, 'vid'));
    unset($most_discussed_t);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (Today) - All", "url" => "t=t&page=" . $pagenum . "&s=md&c=0&l="];
        }
    }
    if ($most_discussed_t_c) {
    $most_discussed_t_c = $most_discussed_t_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_t_c, 'vid'));
    unset($most_discussed_t_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (Today) - " . $catname . " - All", "url" => "t=t&page=" . $pagenum . "&s=md&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Discussed (This Week) */
    $most_discussed_w = $conn->query(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
    $most_discussed_w_c = $conn->prepare(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND videos.category = ?) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
	$most_discussed_w_c->execute([$category]);

    if ($most_discussed_w) {
    $most_discussed_w = $most_discussed_w->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_w, 'vid'));
    unset($most_discussed_w);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (This Week) - All", "url" => "t=w&page=" . $pagenum . "&s=md&c=0&l="];
        }
    }
    if ($most_discussed_w_c) {
    $most_discussed_w_c = $most_discussed_w_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_w_c, 'vid'));
    unset($most_discussed_w_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (This Week) - " . $catname . " - All", "url" => "t=w&page=" . $pagenum . "&s=md&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Discussed (This Month) */
    $most_discussed_m = $conn->query(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
    $most_discussed_m_c = $conn->prepare(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND videos.category = ?) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
	$most_discussed_m_c->execute([$category]);

    if ($most_discussed_m) {
    $most_discussed_m = $most_discussed_m->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_m, 'vid'));
    unset($most_discussed_m);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (This Month) - All", "url" => "t=m&page=" . $pagenum . "&s=md&c=0&l="];
        }
    }
    if ($most_discussed_m_c) {
    $most_discussed_m_c = $most_discussed_m_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_m_c, 'vid'));
    unset($most_discussed_m_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (This Month) - " . $catname . " - All", "url" => "t=m&page=" . $pagenum . "&s=md&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Discussed (All Time) */
    $most_discussed = $conn->query(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
    $most_discussed_c = $conn->prepare(
    "SELECT * FROM comments
	LEFT JOIN videos ON videos.vid = comments.vidon
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.category = ? GROUP BY comments.vidon
	ORDER BY COUNT(comments.cid) DESC LIMIT 100"
    );
	$most_discussed_c->execute([$category]);

    if ($most_discussed) {
    $most_discussed = $most_discussed->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed, 'vid'));
    unset($most_discussed);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (All Time) - All", "url" => "t=a&page=" . $pagenum . "&s=md&c=0&l="];
        }
    }
    if ($most_discussed_c) {
    $most_discussed_c = $most_discussed_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_discussed_c, 'vid'));
    unset($most_discussed_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Discussed (All Time) - " . $catname . " - All", "url" => "t=a&page=" . $pagenum . "&s=md&c=" . $category . "&l="];
        }
    }
	
    /* Honors: Most Linked (Today) */
    $most_linked_t = $conn->query(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
    $most_linked_t_c = $conn->prepare(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) AND videos.category = ?) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
	$most_linked_t_c->execute([$category]);

    if ($most_linked_t) {
    $most_linked_t = $most_linked_t->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_t, 'vid'));
    unset($most_linked_t);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (Today) - All", "url" => "t=t&page=" . $pagenum . "&s=mrd&c=0&l="];
        }
    }
    if ($most_linked_t_c) {
    $most_linked_t_c = $most_linked_t_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_t_c, 'vid'));
    unset($most_linked_t_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (Today) - " . $catname . " - All", "url" => "t=t&page=" . $pagenum . "&s=mrd&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Linked (This Week) */
    $most_linked_w = $conn->query(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
    $most_linked_w_c = $conn->prepare(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND videos.category = ?) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
	$most_linked_w_c->execute([$category]);

    if ($most_linked_w) {
    $most_linked_w = $most_linked_w->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_w, 'vid'));
    unset($most_linked_w);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (This Week) - All", "url" => "t=w&page=" . $pagenum . "&s=mrd&c=0&l="];
        }
    }
    if ($most_linked_w_c) {
    $most_linked_w_c = $most_linked_w_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_w_c, 'vid'));
    unset($most_linked_w_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (This Week) - " . $catname . " - All", "url" => "t=w&page=" . $pagenum . "&s=mrd&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Linked (This Month) */
    $most_linked_m = $conn->query(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
    $most_linked_m_c = $conn->prepare(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND (videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND videos.category = ?) GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
	$most_linked_m_c->execute([$category]);

    if ($most_linked_m) {
    $most_linked_m = $most_linked_m->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_m, 'vid'));
    unset($most_linked_m);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (This Month) - All", "url" => "t=m&page=" . $pagenum . "&s=mrd&c=0&l="];
        }
    }
    if ($most_linked_m_c) {
    $most_linked_m_c = $most_linked_m_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_m_c, 'vid'));
    unset($most_linked_m_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (This Month) - " . $catname . " - All", "url" => "t=m&page=" . $pagenum . "&s=mrd&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Linked (All Time) */
    $most_linked = $conn->query(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
    $most_linked_c = $conn->prepare(
    "SELECT * FROM views
	LEFT JOIN videos ON videos.vid = views.vid
	WHERE (videos.converted = 1 AND videos.privacy = 1) AND views.referer NOT LIKE '%kamtape.com%' AND videos.category = ? GROUP BY views.vid
	ORDER BY COUNT(views.referer) DESC LIMIT 100"
    );
	$most_linked_c->execute([$category]);

    if ($most_linked) {
    $most_linked = $most_linked->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked, 'vid'));
    unset($most_linked);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (All Time) - All", "url" => "t=a&page=" . $pagenum . "&s=mrd&c=0&l="];
        }
    }
    if ($most_linked_c) {
    $most_linked_c = $most_linked_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_linked_c, 'vid'));
    unset($most_linked_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Linked (All Time) - " . $catname . " - All", "url" => "t=a&page=" . $pagenum . "&s=mrd&c=" . $category . "&l="];
        }
    }
	
/* Honors: Most Viewed (Today) */
    $most_viewed_t = $conn->query(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
    $most_viewed_t_c = $conn->prepare(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) AND videos.category = ? GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
	$most_viewed_t_c->execute([$category]);

    if ($most_viewed_t) {
    $most_viewed_t = $most_viewed_t->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_t, 'vid'));
    unset($most_viewed_t);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (Today) - All", "url" => "t=t&page=" . $pagenum . "&s=mp&c=0&l="];
        }
    }
    if ($most_viewed_t_c) {
    $most_viewed_t_c = $most_viewed_t_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_t_c, 'vid'));
    unset($most_viewed_t_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (Today) - " . $catname . " - All", "url" => "t=t&page=" . $pagenum . "&s=mp&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Viewed (This Week) */
    $most_viewed_w = $conn->query(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
    $most_viewed_w_c = $conn->prepare(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND videos.category = ? GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
	$most_viewed_w_c->execute([$category]);

    if ($most_viewed_w) {
    $most_viewed_w = $most_viewed_w->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_w, 'vid'));
    unset($most_viewed_w);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (This Week) - All", "url" => "t=w&page=" . $pagenum . "&s=mp&c=0&l="];
        }
    }
    if ($most_viewed_w_c) {
    $most_viewed_w_c = $most_viewed_w_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_w_c, 'vid'));
    unset($most_viewed_w_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (This Week) - " . $catname . " - All", "url" => "t=w&page=" . $pagenum . "&s=mp&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Viewed (This Month) */
    $most_viewed_m = $conn->query(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
    $most_viewed_m_c = $conn->prepare(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND videos.category = ? GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
	$most_viewed_m_c->execute([$category]);

    if ($most_viewed_m) {
    $most_viewed_m = $most_viewed_m->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_m, 'vid'));
    unset($most_viewed_m);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (This Month) - All", "url" => "t=m&page=" . $pagenum . "&s=mp&c=0&l="];
        }
    }
    if ($most_viewed_m_c) {
    $most_viewed_m_c = $most_viewed_m_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_m_c, 'vid'));
    unset($most_viewed_m_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (This Month) - " . $catname . " - All", "url" => "t=m&page=" . $pagenum . "&s=mp&c=" . $category . "&l="];
        }
    }
    /* Honors: Most Viewed (All Time) */
    $most_viewed = $conn->query(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
    $most_viewed_c = $conn->prepare(
    "SELECT * FROM views
    LEFT JOIN videos ON videos.vid = views.vid
    WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.category = ? GROUP BY views.vid
    ORDER BY COUNT(views.view_id) DESC LIMIT 100"
    );
	$most_viewed_c->execute([$category]);

    if ($most_viewed) {
    $most_viewed = $most_viewed->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed, 'vid'));
    unset($most_viewed);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (All Time) - All", "url" => "t=a&page=" . $pagenum . "&s=mp&c=0&l="];
        }
    }
    if ($most_viewed_c) {
    $most_viewed_c = $most_viewed_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($most_viewed_c, 'vid'));
    unset($most_viewed_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Most Viewed (All Time) - " . $catname . " - All", "url" => "t=a&page=" . $pagenum . "&s=mp&c=" . $category . "&l="];
        }
    }
	
    /* Honors: Top Favorites (Today) */
    $top_favorite_t = $conn->query(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
    $top_favorite_t_c = $conn->prepare(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) AND videos.category = ? GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
	$top_favorite_t_c->execute([$category]);

    if ($top_favorite_t) {
    $top_favorite_t = $top_favorite_t->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_t, 'vid'));
    unset($top_favorite_t);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (Today) - All", "url" => "t=t&page=" . $pagenum . "&s=mf&c=0&l="];
        }
    }
    if ($top_favorite_t_c) {
    $top_favorite_t_c = $top_favorite_t_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_t_c, 'vid'));
    unset($top_favorite_t_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (Today) - " . $catname . " - All", "url" => "t=t&page=" . $pagenum . "&s=mf&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Favorites (This Week) */
    $top_favorite_w = $conn->query(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
    $top_favorite_w_c = $conn->prepare(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND videos.category = ? GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
	$top_favorite_w_c->execute([$category]);

    if ($top_favorite_w) {
    $top_favorite_w = $top_favorite_w->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_w, 'vid'));
    unset($top_favorite_w);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (This Week) - All", "url" => "t=w&page=" . $pagenum . "&s=mf&c=0&l="];
        }
    }
    if ($top_favorite_w_c) {
    $top_favorite_w_c = $top_favorite_w_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_w_c, 'vid'));
    unset($top_favorite_w_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (This Week) - " . $catname . " - All", "url" => "t=w&page=" . $pagenum . "&s=mf&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Favorites (This Month) */
    $top_favorite_m = $conn->query(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
    $top_favorite_m_c = $conn->prepare(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND videos.category = ? GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
	$top_favorite_m_c->execute([$category]);

    if ($top_favorite_m) {
    $top_favorite_m = $top_favorite_m->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_m, 'vid'));
    unset($top_favorite_m);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (This Month) - All", "url" => "t=m&page=" . $pagenum . "&s=mf&c=0&l="];
        }
    }
    if ($top_favorite_m_c) {
    $top_favorite_m_c = $top_favorite_m_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_m_c, 'vid'));
    unset($top_favorite_m_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (This Month) - " . $catname . " - All", "url" => "t=m&page=" . $pagenum . "&s=mf&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Favorites (All Time) */
    $top_favorite = $conn->query(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
    $top_favorite_c = $conn->prepare(
    "SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.category = ? GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT 100"
    );
	$top_favorite_c->execute([$category]);

    if ($top_favorite) {
    $top_favorite = $top_favorite->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite, 'vid'));
    unset($top_favorite);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (All Time) - All", "url" => "t=a&page=" . $pagenum . "&s=mf&c=0&l="];
        }
    }
    if ($top_favorite_c) {
    $top_favorite_c = $top_favorite_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_favorite_c, 'vid'));
    unset($top_favorite_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Favorites (All Time) - " . $catname . " - All", "url" => "t=a&page=" . $pagenum . "&s=mf&c=" . $category . "&l="];
        }
    }
	
    /* Honors: Top Rated (Today) */
    $top_rated_t = $conn->query(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
    $top_rated_t_c = $conn->prepare(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) AND videos.category = ? GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
	$top_rated_t_c->execute([$category]);

    if ($top_rated_t) {
    $top_rated_t = $top_rated_t->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_t, 'vid'));
    unset($top_rated_t);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (Today) - All", "url" => "t=t&page=" . $pagenum . "&s=tr&c=0&l="];
        }
    }
    if ($top_rated_t_c) {
    $top_rated_t_c = $top_rated_t_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_t_c, 'vid'));
    unset($top_rated_t_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (Today) - " . $catname . " - All", "url" => "t=t&page=" . $pagenum . "&s=tr&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Rated (This Week) */
    $top_rated_w = $conn->query(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
    $top_rated_w_c = $conn->prepare(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND videos.category = ? GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
	$top_rated_w_c->execute([$category]);

    if ($top_rated_w) {
    $top_rated_w = $top_rated_w->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_w, 'vid'));
    unset($top_rated_w);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (This Week) - All", "url" => "t=w&page=" . $pagenum . "&s=tr&c=0&l="];
        }
    }
    if ($top_rated_w_c) {
    $top_rated_w_c = $top_rated_w_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_w_c, 'vid'));
    unset($top_rated_w_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (This Week) - " . $catname . " - All", "url" => "t=w&page=" . $pagenum . "&s=tr&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Rated (This Month) */
    $top_rated_m = $conn->query(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
    $top_rated_m_c = $conn->prepare(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND videos.category = ? GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
	$top_rated_m_c->execute([$category]);

    if ($top_rated_m) {
    $top_rated_m = $top_rated_m->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_m, 'vid'));
    unset($top_rated_m);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (This Month) - All", "url" => "t=m&page=" . $pagenum . "&s=tr&c=0&l="];
        }
    }
    if ($top_rated_m_c) {
    $top_rated_m_c = $top_rated_m_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_m_c, 'vid'));
    unset($top_rated_m_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (This Month) - " . $catname . " - All", "url" => "t=m&page=" . $pagenum . "&s=tr&c=" . $category . "&l="];
        }
    }
    /* Honors: Top Rated (All Time) */
    $top_rated = $conn->query(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
    $top_rated_c = $conn->prepare(
    "SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			WHERE (videos.converted = 1 AND videos.privacy = 1) AND videos.category = ? GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT 100"
    );
	$top_rated_c->execute([$category]);

    if ($top_rated) {
    $top_rated = $top_rated->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated, 'vid'));
    unset($top_rated);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (All Time) - All", "url" => "t=a&page=" . $pagenum . "&s=tr&c=0&l="];
        }
    }
    if ($top_rated_c) {
    $top_rated_c = $top_rated_c->fetchAll(PDO::FETCH_ASSOC);
    $honor_find = array_search($_GET['v'], array_column($top_rated_c, 'vid'));
    unset($top_rated_c);
    if ($honor_find !== false) {
        $where_it_is = $honor_find + 1;
		$pagenum = honorsPageNum($where_it_is);
        $video_honors[] = ["honor" => "#" . $where_it_is . " - Top Rated (All Time) - " . $catname . " - All", "url" => "t=a&page=" . $pagenum . "&s=tr&c=" . $category . "&l="];
        }
    }
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<root><html_content><![CDATA[<h4>Honors for This Video:</h4>
<? foreach ($video_honors as $honor) { ?>
			<div class="statItem"><a href="/browse?<? echo htmlspecialchars($honor["url"]); ?>"><? echo htmlspecialchars($honor["honor"]); ?></a></div>
<? } ?>]]></html_content><return_code><![CDATA[0]]></return_code></root>
<? } ?>
<?php
if(isset($_GET['action_get_user_videos_component'])) {
$relatedvideos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.privacy = 1 AND videos.converted = 1
	GROUP BY videos.vid
	ORDER BY videos.uploaded DESC LIMIT 20"
);
$relatedvideos->execute([$_GET['user_id']]);
$relatedvideostotal = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.privacy = 1 AND videos.converted = 1
	GROUP BY videos.vid
	ORDER BY videos.uploaded DESC"
);
$relatedvideostotal->execute([$_GET['user_id']]);
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$_GET['user_id']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);
$relvidnum = -1;
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<root><html_content><![CDATA[<div id="exUserContentDiv">
			<table class="showingTable"><tr>
	<td class="smallText">Showing 1-<?php echo $relatedvideos->rowCount(); ?> of <?php echo $relatedvideostotal->rowCount(); ?></td>
	<td align="right" class="smallText"><a href="/profile_videos?user=<?php echo htmlspecialchars($uploader['username']); ?>">See All Videos</a></td>
	</tr></table>

				<div id="side_results" class="exploreContent" name="side_results">
		<?php foreach($relatedvideos as $relatedvideo) { $relatedvideo['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?"); $relatedvideo['views']->execute([$relatedvideo['vid']]); $relatedvideo['views'] = $relatedvideo['views']->fetchColumn(); $relvidnum = $relvidnum + 1; ?>
		<div class="vWatchEntry <? if ($_GET['video_id'] == $relatedvideo['vid']) { ?>vNowPlaying<? } ?> ">
                <table><tr>
                <td>
                        <div class="img" style="margin-top:0px;">
                                <a href="/watch?v=<?php echo htmlspecialchars($relatedvideo['vid']); ?>&mode=related&search=" onclick="_hbLink('RelatedVideo','ExploreMore');" rel="nofollow"><img class="vimgSm" src="/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?>" /></a>
                        </div>
			<div id="add_img_<?php echo htmlspecialchars($relatedvideo['vid']); ?>" class="addtoQLRelatedIE">
				<a href="#" onClick="clicked_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>', 1);print_quicklist_video('/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?>',document.getElementById('video_title_text_<? echo $relvidnum; ?>_<?php echo $relatedvideo['views']; ?>_<?php echo $relatedvideo['time']; ?>').innerHTML,'<?php echo htmlspecialchars($relatedvideo['username']); ?>','<?php echo htmlspecialchars($relatedvideo['vid']); ?>','<?php echo gmdate("i:s", $relatedvideo['time']); ?>');_hbLink('QuickList+AddTo','Watch');return false;" title="Add Video to QuickList" rel="nofollow"><img id="add_button_<?php echo htmlspecialchars($relatedvideo['vid']); ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
                        </div>
               </td>
		<td><div class="title" onclick="_hbLink('RelatedVideo','ExploreMore');"><a href="/watch?v=<?php echo htmlspecialchars($relatedvideo['vid']); ?>&mode=related&search=" id="video_title_text_<? echo $relvidnum; ?>_<?php echo $relatedvideo['views']; ?>_<?php echo $relatedvideo['time']; ?>" rel="nofollow"><?php echo htmlspecialchars($relatedvideo['title']); ?></a><br/>
			<span class="runtime"><?php echo gmdate("i:s", $relatedvideo['time']); ?></span>
			</div>
			<div class="facets">
				<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($relatedvideo['username']); ?>" class="dg" rel="nofollow"><?php echo htmlspecialchars($relatedvideo['username']); ?></a><br/>
				<span class="grayText">Views:</span> <?php echo $relatedvideo['views']; ?>
			</div><? if ($_GET['video_id'] == $relatedvideo['vid']) { ?>
				<div class="smallText">
				<b>&lt;&lt; Now Playing</b>
				</div><? } ?>
			</div></td>
		</tr></table>
		</div> <!-- end vWatchEntry --><? } ?>
	
	</div>

			<table class="showingTable"><tr>
	<td class="smallText">Showing 1-<?php echo $relatedvideos->rowCount(); ?> of <?php echo $relatedvideostotal->rowCount(); ?></td>
	<td align="right" class="smallText"><a href="/profile_videos?user=<?php echo htmlspecialchars($uploader['username']); ?>">See All Videos</a></td>
	</tr></table>

		</div>]]></html_content><return_code><![CDATA[0]]></return_code></root><? } ?>
<?php
if(isset($_GET['action_get_related_playlist_component'])) {
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?><root><html_content><![CDATA[<table class="showingTable"><tr>
	<td class="label">Playlists related to this video</td>
	</tr></table>
	<div id="plList" class="exploreContent">
	No Playlists Found
	</div>]]></html_content><return_code><![CDATA[0]]></return_code></root>';
}