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

	include 'header.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		exit();
	}

	$added = time();

	if (isset($_POST['platform']) && $_POST['platform'] != 0) {
		$platformresult = $xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($_POST['platform'])."");
		list($fileext) = $xoopsDB->fetchRow($platformresult);
		$fileext = strtolower($fileext);
	} else {

	// try to read remote file with fopen or cURL
	$remotefilename = $myts->htmlspecialchars($_POST['link']);

	switch($xoopsModuleConfig['remotereader']) {
		case 'fopen':
		$isreadable = 1;
		if ($fp_remote = fopen($remotefilename, 'rb')) {
			$localtempfilename = tempnam(DEBASER_ROOT.'/tmp', 'getID3');

			if ($fp_local = fopen($localtempfilename, 'wb')) {
				$buffer = fread($fp_remote, 10240);
				fwrite($fp_local, $buffer);
				fclose($fp_local);
			}
		fclose($fp_remote);
		} else {
			$isreadable = 0;
		}
		break;

		case 'curl':
		$isreadable = 1;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $remotefilename);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RANGE, "0-10240");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
		$content = curl_exec($curl);
		curl_close($curl);
		$localtempfilename = tempnam(DEBASER_ROOT.'/tmp', 'getID3');
			if ($fp_local = fopen($localtempfilename, 'wb')) {
				fwrite($fp_local, $content);
				fclose($fp_local);
			} else {
				$isreadable = 0;
			}
		break;

		case 'none':
		$isreadable = 0;
		break;
	}

	if ($isreadable != 0) {
		require_once DEBASER_CLASS.'/getid3/getid3.php';
		$getID3 = new getID3;
		$ThisFileInfo = $getID3->Analyze($localtempfilename);
		getid3_lib::CopyTagsToComments($ThisFileInfo);
		$mimetyplink = (!empty($ThisFileInfo['mime_type']) ? $ThisFileInfo['mime_type'] : '');

			$fileext = (!empty($ThisFileInfo['fileformat']) ? $ThisFileInfo['fileformat'] : '');
			$length = (!empty($ThisFileInfo['playtime_string']) ? $ThisFileInfo['playtime_string'] : '');
			$bitrate = (!empty($ThisFileInfo['bitrate']) ? round($ThisFileInfo['bitrate'] / 1000) : '');
			$artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode($ThisFileInfo['comments_html']['artist']) : '');
			$title = (!empty($ThisFileInfo['comments_html']['title']) ? implode($ThisFileInfo['comments_html']['title']) : '');
			$frequence = (!empty($ThisFileInfo['audio']['sample_rate']) ? $ThisFileInfo['audio']['sample_rate'] : '');
			$album = (!empty($ThisFileInfo['comments_html']['album']) ? implode($ThisFileInfo['comments_html']['album']) : '');
			$track = (!empty($ThisFileInfo['comments_html']['track']) ? implode($ThisFileInfo['comments_html']['track']) : '');
			$year = (!empty($ThisFileInfo['comments_html']['year']) ? implode($ThisFileInfo['comments_html']['year']) : '');

		@unlink($localtempfilename);
	}
	}

	if ($_POST['artist'] != '') $artist = $myts->htmlSpecialChars(utf8_decode($_POST['artist']));
	else $artist = '';

	if ($_POST['title'] != '') $title = $myts->htmlSpecialChars(utf8_decode($_POST['title']));
	else $title = '';

	if ($_POST['album'] != '') $album = $myts->htmlSpecialChars($_POST['album']);
	else $album = '';

	if ($_POST['year'] != '') $year = $_POST['year'];
	else $year = '';

	if ($_POST['track'] != '') $track = $_POST['track'];
	else $track = '';

	if ($_POST['bitrate'] != '') $bitrate = $_POST['bitrate'];
	else $bitrate = '';

	if ($_POST['frequence'] != '') $frequence = $_POST['frequence'];
	else $frequence = '';

	if ($_POST['length'] != '') $length = $_POST['length'];
	else $length = '';

	if ($artist == '' && $title == '') $artist = $myts->htmlspecialchars($_POST['link']);

	if ($xoopsModuleConfig['autoapprove'] == 1) $approved = 1;
	else $approved = 0;

	$genreid = $_POST['genrefrom'];

	if (isset($_POST['platform']) && $_POST['platform'] != 0) $platform = $_POST['platform'];
	else $platform = 'link';

		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_files')." (added, title, artist, album, year, track, genreid, length, bitrate, frequence, approved, fileext, uid, language, linktype, linkcode) VALUES (".intval($added).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($artist).", ".$xoopsDB->quoteString($album).", ".intval($year).", ".intval($track).", ".intval($genreid).", ".$xoopsDB->quoteString($length).", ".intval($bitrate).", ".intval($frequence).", ".intval($approved).", ".$xoopsDB->quoteString($fileext).", ".intval($current_userid).", ".$xoopsDB->quoteString($langb).", ".$xoopsDB->quoteString($platform).", ".$xoopsDB->quoteString($_POST['link']).")");

		$newid = $xoopsDB->getInsertId();

		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total+1 WHERE genreid = ".intval($genreid)."");

		if ($xoopsModuleConfig['multilang'] == 0) {
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST['description']).", ".$xoopsDB->quoteString($langb).")");
		} else {

			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
			$i = 0;

			foreach ($langlist as $langcontent) {
				$postdescription = $bb[$i].'_description';
				$language = $bb[$i];

				if ($_POST[$postdescription] != '') {
					$result3 = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");

				}
				$i++;

				unset($postdescription);
				unset($language);
			}
		}

		$member_handler = &xoops_gethandler('member');
		$poster = &$member_handler->getUser($current_userid);
		$member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);

		redirect_header('uploadinglink.php', 2, _MD_DEBASER_LINKADD);

?>