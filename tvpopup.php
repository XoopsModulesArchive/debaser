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

	$xoopsTpl->assign("maintheme", xoops_getcss($xoopsConfig['theme_set']));

	if (isset($_GET['tv']) && !empty($_GET['tv']) && isset($_GET['player']) && !empty($_GET['player'])) {
	$tv_id = $_GET['tv'];
	$player_id = $_GET['player'];

	$result = $xoopsDB->query("SELECT a.tv_id, a.tv_name, a.tv_stream, a.tv_url, a.tv_picture, b.xpid, b.html_code, b.height, b.width, b.autostart, b.urltoscript FROM ".$xoopsDB->prefix('debaser_tv')." a, ".$xoopsDB->prefix('debaser_player')." b WHERE a.tv_id = ".intval($tv_id)." AND b.xpid = ".intval($player_id)."");

	list($tv_id, $tv_name, $tv_stream, $tv_url, $tv_picture, $xpid, $playercode, $height, $width, $autostart, $urltoscript) = $xoopsDB->fetchRow($result);

	$xoopsTpl->assign('tv_name', $tv_name);
	$xoopsTpl->assign('tv_url', $tv_url);

		if ($tv_url != '') {
			$xoopsTpl->assign('urlavail', true);
			$xoopsTpl->assign('tv_url', $tv_url);
		}

		if ($tv_picture != '') {
			$xoopsTpl->assign('pictureavail', true);
			$xoopsTpl->assign('tv_picture', $tv_picture);
		}

		// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $tv_stream, $urltoscript, $tv_id);

		$playercode = str_replace($searcharray, $replacearray, $playercode);
		$xoopsTpl->assign('tvplayer', $playercode);

		} else {
			$xoopsTpl->assign('noparam', true);
		}

	$xoopsTpl->display('db:debaser_tvpopup.html');

?>