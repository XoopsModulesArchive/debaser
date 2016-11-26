<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

	include_once '../../mainfile.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

	if (isset($_GET['op']) && $_GET['op'] == 'playlist') {
		$filename = DEBASER_PLAY.'/playlist_'.$_GET['uid'].'_.'.$_GET['playlistformat'].'';
		$newfilename = DEBASER_PLAY.'/playlist_'.$_GET['uid'].'_.'.$_GET['playlistformat'].'';
		if ($_GET['playlistformat'] == 'wpl') $contenttype = 'application/vnd.ms-wpl';
		else $contenttype = 'audio/mpeg';
	} else {

		include_once XOOPS_ROOT_PATH.'/class/module.textsanitizer.php';
		$myts =& MyTextSanitizer::getInstance();

		$module_handler = &xoops_gethandler('module');
		$module =& $module_handler->getByDirname('debaser');
		$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$module_id = $module->getVar('mid');
		$config_handler =& xoops_gethandler('config');
		$moduleConfig =& $config_handler->getConfigsByCat(0, $module_id);

		$fileid = ($_GET['id']) ? $_GET['id'] : 1;
		$contenttype = 'application/force-download';

	$sql = "SELECT filename, title, artist, fileext, uid FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid=".intval($fileid)."";

	$newfilename = '';
	$result = $xoopsDB->query($sql);

		if ($result) {

		list($downfile, $downtitle, $downartist, $fileext, $uid) = $xoopsDB->fetchRow($result);

			if (@array_intersect($moduleConfig['owndir'], $groups)) $extrapath = 'user_'.$uid.'_/';
			else $extrapath = '';

		$newfilename = $myts -> undoHtmlSpecialChars($downartist).' - '.$myts -> undoHtmlSpecialChars($downtitle).'.'.$fileext;
		$newfilename = str_replace(' ', '', $newfilename);
		$filename = DEBASER_RUP.'/'.$extrapath.$downfile;
		$fileresult = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_files')." SET hits = hits+1 WHERE xfid = ".intval($fileid)."");

		}
		else {
		redirect_header('index.php',2,_MD_DEBASER_FILENOTFOUND);
		}
	}

	function output_file($file, $name, $mimetype) {

		if(!is_readable($file)) die('File not found or inaccessible!');

		$size = filesize($file);

		@ob_end_clean();

		if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');

		header('Content-Type: ' . $mimetype);
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Content-Transfer-Encoding: binary");
		header('Accept-Ranges: bytes');
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		if (isset($_SERVER['HTTP_RANGE'])) {
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
			list($range) = explode(",", $range, 2);
			list($range, $range_end) = explode("-", $range);
			$range = intval($range);

			if (!$range_end) $range_end = $size-1;
			else $range_end = intval($range_end);

			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");

		} else {
			$new_length = $size;
			header("Content-Length: ".$size);
		}

		$chunksize = 1*(1024*1024);
		$bytes_send = 0;

		if ($file = fopen($file, 'r')) {

			if (isset($_SERVER['HTTP_RANGE'])) fseek($file, $range);

			while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length)) {
				$buffer = fread($file, $chunksize);
				print($buffer);
				flush();
				$bytes_send += strlen($buffer);
			}

			fclose($file);
		} else {
			die('Error - can not open file.');
		}

		die();
	}

	set_time_limit(0);
	output_file($filename, $newfilename, $contenttype);

?>