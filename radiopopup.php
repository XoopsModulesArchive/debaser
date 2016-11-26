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

	if (isset($_GET['radio']) && !empty($_GET['radio']) && isset($_GET['player']) && !empty($_GET['player'])) {
	$radio_id = $_GET['radio'];
	$player_id = $_GET['player'];

	$result = $xoopsDB->query("SELECT a.radio_id, a.radio_name, a.radio_stream, a.radio_url, a.radio_picture, b.xpid, b.html_code, b.height, b.width, b.autostart, b.urltoscript FROM ".$xoopsDB->prefix('debaserradio')." a, ".$xoopsDB->prefix('debaser_player')." b WHERE a.radio_id = ".intval($radio_id)." AND b.xpid = ".intval($player_id)."");

	list($radio_id, $radio_name, $radio_stream, $radio_url, $radio_picture, $xpid, $playercode, $height, $width, $autostart, $urltoscript) = $xoopsDB->fetchRow($result);

	$xoopsTpl->assign('radio_name', $radio_name);
	$xoopsTpl->assign('radio_url', $radio_url);

		if ($radio_url != '') {
			$xoopsTpl->assign('urlavail', true);
			$xoopsTpl->assign('radio_url', $radio_url);
		}

		if ($radio_picture != '') {
			$xoopsTpl->assign('pictureavail', true);
			$xoopsTpl->assign('radio_picture', $radio_picture);
		}

		// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $radio_stream, $urltoscript, $radio_id);

		$playercode = str_replace($searcharray, $replacearray, $playercode);

		$xoopsTpl->assign('radioplayer', $playercode);

		} else {
			$xoopsTpl->assign('noparam', true);
		}

	$xoopsTpl->display('db:debaser_radiopopup.html');

?>