<?php

$artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode($ThisFileInfo['comments_html']['artist']) : '');
$title = (!empty($ThisFileInfo['comments_html']['title']) ? implode($ThisFileInfo['comments_html']['title']) : '');
$length = (!empty($ThisFileInfo['playtime_string']) ? $ThisFileInfo['playtime_string'] : '');
$bitrate = (!empty($ThisFileInfo['bitrate']) ? round($ThisFileInfo['bitrate'] / 1000) : '');
$frequence = (!empty($ThisFileInfo['audio']['sample_rate']) ? $ThisFileInfo['audio']['sample_rate'] : '');
$album = (!empty($ThisFileInfo['comments_html']['album']) ? implode($ThisFileInfo['comments_html']['album']) : '');
$track = (!empty($ThisFileInfo['comments_html']['track']) ? implode($ThisFileInfo['comments_html']['track']) : '');
$year = (!empty($ThisFileInfo['comments_html']['year']) ? implode($ThisFileInfo['comments_html']['year']) : '');
$addinfo = (!empty($ThisFileInfo['comments_html']['comment']) ? implode($ThisFileInfo['comments_html']['comment']) : '');
$mimetyplink = (!empty($ThisFileInfo['mime_type']) ? $ThisFileInfo['mime_type'] : '');
?>