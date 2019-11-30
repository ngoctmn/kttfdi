<?php
set_time_limit(0);
error_reporting(0);
define('HASHTAG_NAMESPACE', '#kttfdi_');/* Sửa hashtag tại đây , chữ thường không viết hoa và có dấu # trước kí tự hashtag*/
$token = 'EAAjkDM4rGjEBAMrYvLNY7mpwTn8yIczPZCqfKEmWqWCM9SkMG0568Ym2rBsKdnrZAuIbM8F5ai2ozwST2PYqi3eCb3KrR4QapjPAvVm1qwBuFiUkVZCxEZA7KsoQmiRZBrGAcQp5BITKHZAssfXnFwz8TAZAN9ZAdaJ9PAJrd4aidgZBmAiFjEav4ZBr8xcDMRMaGtozvEoBKUoAZDZD'; /* điền token full quyền ở đây hoặc lấy token từ death click */
$idgroup = ''; /* Id Group */
$post = json_decode(request('https://graph.facebook.com/v2.9/' .$idgroup. '/feed?fields=id,message,created_time,from&limit=100&access_token=' . $token), true); /* Get Data Post*/
$timelocpost = date('Y-m-d');
$logpost     = file_get_contents("log.txt");

for ($i = 0; $i < 100; $i++) {
    $idpost      = $post['data'][$i]['id'];
    $messagepost = $post['data'][$i]['message'];
    $time        = $post['data'][$i]['created_time'];
	/* Check time Post */
    if (strpos($time, $timelocpost) !== false) {
		/* Check hashtag */
        if (strpos(strtolower($messagepost), HASHTAG_NAMESPACE) === FALSE) {
			/* Check trùng  */
            if (strpos($logpost, $idpost) === FALSE) {
				/* Send Comment  */
                $comment = $post['data'][$i]['from']['name'] . '!' . "\n\n" .  'Vui lòng xem danh sách tại: https://github.com/ngoctmn/kttfdi/blob/master/README.md' . "\n\n" .'https://scontent.fhan5-5.fna.fbcdn.net/v/t39.1997-6/p160x160/10333116_298592900320909_356690604_n.png?_nc_cat=1&_nc_ohc=T_yIAJfYYm8AQnG22ouW4Ruxa8gy8ClKvqh7LfJJLL7UGlDVgfwAvtalA&_nc_ht=scontent.fhan5-5.fna&oh=c1a4778d19435d72f7d1aa4ca7422c3d&oe=5E89562B';
				request('https://graph.facebook.com/' . urlencode($idpost) . '/comments?method=post&message=' . urlencode($comment) . '&access_token=' . $token);
                $luulog = fopen("log.txt", "a");
                fwrite($luulog, $idpost . "\n");
                fclose($luulog);
            } else {
                echo 'https://scontent.fhan5-5.fna.fbcdn.net/v/t39.1997-6/p160x160/10333116_298592900320909_356690604_n.png?_nc_cat=1&_nc_ohc=T_yIAJfYYm8AQnG22ouW4Ruxa8gy8ClKvqh7LfJJLL7UGlDVgfwAvtalA&_nc_ht=scontent.fhan5-5.fna&oh=c1a4778d19435d72f7d1aa4ca7422c3d&oe=5E89562B';
            }
        }
    
    }
}
function request($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return FALSE;
    }
    
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
        CURLOPT_AUTOREFERER => TRUE,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 0
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset($options);
    return $http_code === 200 ? $response : FALSE;
}
?>
