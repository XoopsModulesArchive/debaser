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
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$xoopsOption['template_main'] = 'debaser_mydebaser.html';

	if ($current_userid == 'guest') redirect_header('index.php', 2, _NOPERM);

	global $xoopsConfig;

	// direct access without a function is not allowed
	function goaway() {
		redirect_header('index.php', 2, _NOPERM);
		exit();
	}

	// Function for defining personal settings if allowed in permissions
	function mysettings() {

		global $current_userid, $xoopsDB, $groups, $module_id, $xoopsModuleConfig, $gperm_handler, $xoopsConfig, $xoTheme;

		include XOOPS_ROOT_PATH.'/header.php';

		$result = $xoopsDB->query("SELECT publicplaylist, flashupload FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		$hasresult = $xoopsDB->getRowsNum($result);

		if ($hasresult != 1) {
			$publicplaylist = 0;
			$flashupload = 0;
		} else {
			list($publicplaylist, $flashupload) = $xoopsDB->fetchRow($result);
		}

		if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups) || @array_intersect($xoopsModuleConfig['allowflashupload'], $groups)) {
		$mysetting = new XoopsThemeForm(_MD_DEBASER_MYSETTINGS, 'mydebasersettings', 'mysetting.php', 'post', true);

		if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups))
			$mysetting->addElement(new XoopsFormRadioYN(_MD_DEBASER_PUBLICPLAYLIST, 'publicplaylist', $publicplaylist));

		if (@array_intersect($xoopsModuleConfig['allowflashupload'], $groups))
			$mysetting->addElement(new XoopsFormRadioYN(_MD_DEBASER_NOFLASHUPLOAD, 'flashupload', $flashupload));

		$mysetting->addElement(new XoopsFormHidden('op', 'savemysettings'));
		$mysetting->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$xoopsTpl->assign('mysettings', $mysetting->render());

		} else {
			echo _MD_DEBASER_NOSETTINGAVAIL;
		}

		$xoTheme->addScript(DEBASER_UJS.'/plugins.js', array('type' => 'text/javascript'), null);
		$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

		if (isset($_GET['op']) && $_GET['op'] == 'mysettings') $xoopsTpl->assign('tplmysettings', true);
		$xoopsTpl->assign('myplaylist', false);

	}

	// Function for saving personal settings
	function savemysettings() {

		global $current_userid, $xoopsDB, $groups, $module_id, $xoopsModuleConfig, $gperm_handler, $xoopsConfig;

		if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

		$result = $xoopsDB->query("SELECT publicplaylist, flashupload FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		$hasresult = $xoopsDB->getRowsNum($result);

		if (@array_intersect($xoopsModuleConfig['allowflashupload'], $groups)) $flashupload = $_POST['flashupload'];
		else $flashupload = 0;

		if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups)) $publicplaylist = $_POST['publicplaylist'];
		else $publicplaylist = 0;

		if ($hasresult != 1) {
			$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_user')." (debuid, publicplaylist, flashupload) VALUES (".intval($current_userid).", ".intval($publicplaylist).", ".intval($flashupload).")");
		} else {
			$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_user')." SET publicplaylist = ".intval($publicplaylist).", flashupload = ".intval($flashupload)." WHERE debuid = ".intval($current_userid)."");
		}
		redirect_header('mysetting.php?op=mysettings', 2, _MD_DEBASER_DBUPDATED);
	}

	// Function for displaying the playlist
	function playlist() {

		global $current_userid, $xoopsDB, $groups, $module_id, $gperm_handler, $xoopsConfig, $xoopsModuleConfig, $xoTheme;

		if (!@array_intersect($xoopsModuleConfig['allowplaylist'], $groups)) redirect_header('index.php', 2, _NOPERM);

		include XOOPS_ROOT_PATH.'/header.php';

		$getplaylist = $xoopsDB->query("SELECT playlist FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		list($playlist) = $xoopsDB->fetchRow($getplaylist);

		$playarray = explode(" ", $playlist);
		$corrorder = str_replace(' ', ',', $playlist);
		$result = $xoopsDB->query("SELECT xfid, title, artist FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid IN (".implode(', ', array_map('intval', $playarray)).") ORDER BY FIND_IN_SET(xfid, '$corrorder')");

		$myfavfiles = array();

		$i = 1;
		while($fetch = $xoopsDB->fetchArray($result)) {
			$i++;
			$myfavfiles['id'] = $fetch['xfid'];
			$myfavfiles['artist'] = $fetch['artist'];
			$myfavfiles['title'] = $fetch['title'];
			$xoopsTpl->append('myfavfiles', $myfavfiles);

		}

		if ($i == 1) $xoopsTpl->assign('anyfiles', false);
		else $xoopsTpl->assign('anyfiles', true);

		$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function() { $(".warning").remove(); });');

		$getxspf = "SELECT xpid, name, html_code, xspf, playericon FROM ".$xoopsDB->prefix('debaser_player')." WHERE xspf != '0' AND isactive = 1";
		$resultxspf = $xoopsDB->query($getxspf);
		$hasresult = $xoopsDB->getRowsNum($resultxspf);
		if ($hasresult > 0) {
		while($getplayers = $xoopsDB->fetchArray($resultxspf)) {
			$playlistplayer['id'] = $getplayers['xpid'];
			$playlistplayer['name'] = $getplayers['name'];
			$playlistplayer['playericon'] = $getplayers['playericon'];
			$playlistplayer['playlistformat'] = $getplayers['xspf'];

			if ($getplayers['html_code'] == 'external') $playlistplayer['external'] = true;
			else $playlistplayer['external'] = false;

			$xoopsTpl->append('getplayers', $playlistplayer);
		}
		$xoopsTpl->assign('hasplaylistplayer', true);
		}

		if (isset($_GET['op']) && $_GET['op'] == 'playlist') $xoopsTpl->assign('myplaylist', true);
		else $xoopsTpl->assign('myplaylist', false);

		if ($i > 1) {
			$xoTheme->addScript(DEBASER_UJS.'/dragsort-0.3.7.min.js', array('type' => 'text/javascript'), null);
			$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function() { $.get("debasertoken.php",function(txt){ $(".jsecure").append("<input type=\"hidden\" id=\"ts\" name=\"ts\" value=\""+txt+"\" />"); }); $("#debaserreorder").dragsort({ dragSelector: "li div.diffi", dragEnd: saveOrder }); function saveOrder() { var serialStr = ""; var getHidden = $("#ts").val(); $("#debaserreorder li").each(function(i, elm) { serialStr += (i > 0 ? " " : "") + $(elm).attr("title"); }); $.post("'.DEBASER_URL.'/ajaxed.php", { action : "reorderplaylist", ids : serialStr, ts : getHidden }); }; $("img.removefromplaylist").live("click", function () { var getHidden = $("#ts").val(); $("#debaserreorder").load("'.DEBASER_URL.'/ajaxed.php", { action : "removefrompl", id : this.id, ts : getHidden }); setTimeout(function() { location.reload(); }, 500); }); });');
		}

		$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));
		$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
		$xoTheme->addScript(DEBASER_UJS.'/plugins.js', array('type' => 'text/javascript'), null);
		$xoopsTpl->assign('tplmysettings', false);
	}


	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'mysettings':
		mysettings();
		break;

		case 'playlist':
		playlist();
		break;

		case 'savemysettings':
		savemysettings();
		break;

		case 'default':
		default:
		goaway();
		break;
	}

include XOOPS_ROOT_PATH.'/footer.php';

?>