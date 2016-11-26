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
	require_once XOOPS_ROOT_PATH.'/class/template.php';

	$xoopsTpl = new XoopsTpl();

	// establish fileid and playerid, which means we play a single file
	if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['player']) && !empty($_GET['player'])) {
		$fileid = $_GET['id'];
		$playerid = $_GET['player'];

		// get the fileinfo
		$sql1 = "SELECT filename, artist, title, uid, linktype, linkcode, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($fileid)." ";
		$result1 = $xoopsDB->query($sql1);
		list($filename, $artist, $title, $senderid, $linktype, $linkcode, $haslofi) = $xoopsDB->fetchRow($result1);

	if ($xoopsModuleConfig['uselame'] == 1) $getlofi = checkLofi();
	else $getlofi = 0;

		// get the playerinfo
		$sql2 = "SELECT html_code, height, width, autostart, urltoscript FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($playerid)." ";
		$result2 = $xoopsDB->query($sql2);
		list($playercode, $height, $width, $autostart, $urltoscript) = $xoopsDB->fetchRow($result2);

		if ($linktype == '' && $linkcode == '') {
		// check if file is in userdir or not
			$extrapath = getthedir($senderid);

			if ($haslofi == 1 && $getlofi == 1) $lofi = 'lofi_';
			else $lofi = '';

			$xoopsTpl->assign('songid', $_GET['id']);

			$urltofile = DEBASER_URL.'/upload/'.$extrapath.$lofi.$filename;

			// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $urltofile, $urltoscript, intval($fileid));

		$playercode = str_replace($searcharray, $replacearray, $playercode);


		} else {
			if ($linktype != 'link') {
			$playercode = str_replace('\\', '', $linkcode);
			} else {
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';
		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $linkcode, $urltoscript, intval($fileid));

		$playercode = str_replace($searcharray, $replacearray, $playercode);
			}
		}

		$xoopsTpl->assign('player', $playercode);
		$xoopsTpl->assign('artist', $artist);
		$xoopsTpl->assign('title', $title);

	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_files')." SET views = views+1 WHERE xfid = ".intval($fileid)."");
	}

	if (isset($_GET['playlist']) && !empty($_GET['playlist']) && isset($_GET['player']) && !empty($_GET['player'])) {

		$playerid = $_GET['player'];
		$playlist = $_GET['playlist'];
		// get the playerinfo
		$sql3 = "SELECT name, html_code, height, width, autostart, xspf, urltoscript FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($playerid)." ";
		$result3 = $xoopsDB->query($sql3);
		list($name, $playercode, $height, $width, $autostart, $xspf, $urltoscript) = $xoopsDB->fetchRow($result3);

		$urltofile = DEBASER_URL.'/playlist/playlist_'.$playlist.'_.'.$xspf;

		// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>');
		$replacearray = array($height, $width, $autostart, $urltofile, $urltoscript);

		$playercode = str_replace($searcharray, $replacearray, $playercode);

		$xoopsTpl->assign('player', $playercode);
		$xoopsTpl->assign('artist', '');
		$xoopsTpl->assign('title', '');
		$xoopsTpl->assign('songid', '');
	}

	if (isset($_POST['playlist']) && !empty($_POST['playlist']) && isset($_POST['player']) && !empty($_POST['player'])) {

		$playerid = $_POST['player'];
		$playlist = $_POST['playlist'];
		// get the playerinfo
		$sql3 = "SELECT html_code, height, width, autostart, xspf, urltoscript FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($playerid)." ";
		$result3 = $xoopsDB->query($sql3);
		list($playercode, $height, $width, $autostart, $urltoscript, $xspf) = $xoopsDB->fetchRow($result3);

		$urltofile = DEBASER_URL.'/playlist/playlist_'.$playlist.'_.'.$xspf.'';

		// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>');
		$replacearray = array($height, $width, $autostart, $urltofile, $urltoscript);

		$playercode = str_replace($searcharray, $replacearray, $playercode);

		$xoopsTpl->assign('player', $playercode);
		$xoopsTpl->assign('artist', $playerid);
		$xoopsTpl->assign('title', $playlist);
		$xoopsTpl->assign('songid', '');
	}


	if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups)) {
		if ((isset($_GET['playlist']) && $_GET['playlist'] != '') || (isset($_POST['playlist']) && $_POST['playlist'] != '') || (isset($linkcode) && $linkcode != ''))
			$xoopsTpl->assign('allowyes', 0);
		else
			$xoopsTpl->assign('allowyes', 1);

	} else {
		$xoopsTpl->assign('allowyes', 0);
	}

	$xoopsTpl->assign("maintheme", xoops_getcss($xoopsConfig['theme_set']));

	$xoopsTpl->display('db:debaser_player.html');

?>