<?php
// $Id: player.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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

	include '../../mainfile.php';
	include XOOPS_ROOT_PATH.'/header.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';

	require_once XOOPS_ROOT_PATH.'/class/template.php';

	$xoopsTpl = new XoopsTpl();

	/* establish file id */
	$fileid = ($_GET['id']) ? $_GET['id'] : 1;
	
	$playerid = ($_GET['player']) ? $_GET['player'] : 1;

	/* get the userid */
		if (is_object($xoopsUser)) {
		$uid = $xoopsUser->getVar('uid');
		}
		else {
		$uid = 0;
		}
	/* -- */

	$playsql = "SELECT useplayer FROM ".$xoopsDB->prefix('debaser_user')." WHERE uid = ".intval($uid)." ";
	$playresult = $xoopsDB->query($playsql);

	list($playervar) = $xoopsDB->fetchRow($playresult);

	/* load current song */
	$filename = '';
	$sql = "SELECT xfid, filename, artist, title, link, fileext FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($fileid)." ";

	$result = $xoopsDB->query($sql);

	list($songid, $filename, $artist, $title, $link, $fileext) = $xoopsDB->fetchRow($result);

		if ($link == '') {
		$mp3file = 'upload/'.$filename;
		}
		else {
		$mp3file = $link;
		}

	$xoopsTpl->assign('songid', $songid);
	$xoopsTpl->assign('artist', $artist);
	$xoopsTpl->assign('title', $title);

	preg_match("/".$fileext."\/([0-9]+)/", $playervar, $whichplayer);

	$whichplayer = $whichplayer[1];

	/* player */
	$player = '';

		if (is_object($xoopsUser)) {
		$sql = "SELECT html_code, height, width, autostart FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = $whichplayer ";

		$result = $xoopsDB->query($sql);
		list($player, $height, $width, $autostart) = $xoopsDB->fetchRow($result);
		
			if ($result) {
			//$row = mysql_fetch_object($result);
 				if ($autostart == 1) {
	$autostart = 'true';
	}
	else {
	$autostart = 'false';
	}

	/* generate output */
	$player1 = str_replace('<@height@>',$height,$player);
	$player2 = str_replace('<@width@>',$width,$player1);
	$player3 = str_replace('<@autostart@>',$autostart,$player2);
			}
			else {
		$player3 .= preselected_player($fileid);
			}
		}
		else {
		$player3 .= preselected_player($fileid);
		}
	
	$player4 = str_replace('<@mp3file@>',$mp3file,$player3);
	$xoopsTpl->assign("player", $player4);

	$allowyes = 1;

	/* determine if downloads are allowed or if download is a link */
		if ($xoopsModuleConfig['debaserallowdown'] == 1) {
		$xoopsTpl->assign("allowyes", $allowyes);

			if ($link != '') {
			$xoopsTpl->assign('linkyes', true);
			$xoopsTpl->assign('downlink', $link);
			}
		}

	$xoopsDB->queryF("
	UPDATE ".$xoopsDB->prefix('debaser_files')." 
	SET views = views+1 
	WHERE xfid = ".$songid."");

	$xoopsTpl->assign("maintheme", xoops_getcss($xoopsConfig['theme_set']));

	$xoopsTpl->display('db:debaser_player.html');

?>