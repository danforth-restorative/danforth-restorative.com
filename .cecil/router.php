<?php
/**
@ligny





*/




date_default_timezone_set('UTC');
define('SERVER_TMP_DIR', '.cecil');
define('DIRECTORY_INDEX', 'index.html');
define('DEBUG', false);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);


if ($path == '/watcher') {
header("Content-Type: text/event-stream\n\n");
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *');
$flagFile = $_SERVER['DOCUMENT_ROOT'].'/../'.SERVER_TMP_DIR.'/changes.flag';
if (file_exists($flagFile)) {
echo "event: reload\n";
printf('data: %s', file_get_contents($flagFile));
}
echo "\n\n";
exit();
}



$pathname = $path;


if (substr($path, -1) == '/') {
$pathname = rtrim($path, '/').'/'.DIRECTORY_INDEX;
}


if (file_exists($filename = $_SERVER['DOCUMENT_ROOT'].$pathname)) {
$mimesHtml = ['xhtml+xml', 'html'];
$extHtml = ['htm', 'html'];
$mimesText = ['json', 'xml', 'css', 'csv', 'javascript', 'plain', 'text'];
$mimesAudio = ['mpeg', 'x-m4a'];

$ext = pathinfo($pathname, PATHINFO_EXTENSION);
$mimeType = mime_content_type($filename);
$mimeSubtype = explode('/', $mimeType)[1];


 if (in_array($mimeSubtype, $mimesHtml) || in_array($mimeSubtype, $mimesText)) {
$content = file_get_contents($filename);

 if (in_array($mimeSubtype, $mimesHtml) && in_array($ext, $extHtml)) {

 if (file_exists(__DIR__.'/livereload.js')) {
$script = file_get_contents(__DIR__.'/livereload.js');
$content = str_ireplace('</body>', "$script\n  </body>", $content);
if (stristr($content, '</body>') === false) {
$content .= "\n$script";
}
}
}

 $baseurls = trim(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../'.SERVER_TMP_DIR.'/baseurl'));
$baseurls = explode(';', $baseurls);
$baseurl = $baseurls[0];
$baseurlLocal = $baseurls[1];
if (false !== strstr($baseurl, 'http') || $baseurl != '/') {
$content = str_replace($baseurl, $baseurlLocal, $content);
}

 header('Etag: '.md5_file($filename));
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
switch ($ext) {
case 'css':
header('Content-Type: text/css');
break;
case 'js':
header('Content-Type: application/javascript');
break;
case 'svg':
header('Content-Type: image/svg+xml');
break;
default:
header('Content-Type: '.$mimeType);
break;
}
echo $content;

return true;
}


 if (in_array($mimeSubtype, $mimesAudio)) {
header("Content-Type: $mimeType");
header('Cache-Control: no-cache');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.filesize($filename));
header('Accept-Ranges: bytes');
echo file_get_contents($filename);

return true;
}

return false;
}


http_response_code(404);
echo '404, page not found';
