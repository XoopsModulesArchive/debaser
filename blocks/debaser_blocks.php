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

	global $xoopsUser;

	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('debaser');
	$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$module_id = $module->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');

	if (is_object($xoopsUser)) $current_userid = $xoopsUser->getVar('uid');
	else $current_userid = 'guest';

	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

		if ($moduleConfig['uselame'] == 1) {
		if ($moduleConfig['loficondition'] == 'group') {
			$lofigroup = trim($moduleConfig['loficondcode']);
			$lofigroup = explode(' ', $lofigroup);
			if (@array_intersect($lofigroup, $groups)) $getlofi = 1;
			else $getlofi = 0;
		}
		if ($moduleConfig['loficondition'] == 'rank') {
			$lofirank = trim($moduleConfig['loficondcode']);
			$lofirank = explode(' ', $lofirank);
			if ($current_userid == 'guest') $nowrank = 0;
			else $nowrank = $xoopsUser->rank();

			if (array_intersect($nowrank, $lofirank)) $getlofi = 1;
			else $getlofi = 0;
		}
		if ($moduleConfig['loficondition'] == 'posts') {
			if ($current_userid == 'guest') $nowposts = 0;
			else $nowposts = $xoopsUser->posts();

			if ($nowposts <= $moduleConfig['loficondcode']) $getlofi = 1;
			else $getlofi = 0;
		}
	} else {
		$getlofi = 0;
	}

	$myts =& MyTextSanitizer::getInstance();



	function debaser_equalizerblock() {
		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';
		// go away, here is nothing to see
		$block = array();
		$block['equalizer'][] = 'pop';
		return $block;
		echo '';
	}

	function b_debaser_hits_show($options) {

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		global $xoopsDB, $xoopsUser, $myts, $groups, $module_id, $gperm_handler;

		if (!preg_match('/\/debaser/', $_SERVER['SCRIPT_FILENAME']) || preg_match('/\/debaser\/comment(.*)/', $_SERVER['SCRIPT_FILENAME'])) {
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname('debaser');
			$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
			$module_id = $module->getVar('mid');
			$gperm_handler = &xoops_gethandler('groupperm');
			$myts =& MyTextSanitizer::getInstance();
		}

		$block = array();
		$result = $xoopsDB->query("SELECT xfid, title, artist, album, year, track, length, bitrate, frequence, genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 AND hits > 0 ORDER BY hits DESC LIMIT ".$options[0]."");

		while ($myrow = $xoopsDB->fetchArray($result)) {
			if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'], $groups, $module_id)) {
				$hits = array();
				$hits['id'] = intval($myrow['xfid']);
				$hits['title'] = $myts->htmlSpecialChars($myrow['title']);
				$hits['artist'] = $myts->htmlSpecialChars($myrow['artist']);
				$block['debaser_fileshits'][] = $hits;
			}
		} //while ende

		return $block;
	}

	function b_debaser_hits_edit($options) {

		$form = ""._MB_DEBASER_BLOCLATE."<input type='text' size='3' maxlength='2' name='options[]' value='".$options[0]."' />&nbsp;"._MB_DEBASER_SONGS."";

		return $form;
	}

	function b_debaser_latest_show($options) {

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		global $xoopsDB, $groups, $module_id, $xoopsUser, $myts, $gperm_handler;

		if (!preg_match('/\/debaser/', $_SERVER['SCRIPT_FILENAME']) || preg_match('/\/debaser\/comment(.*)/', $_SERVER['SCRIPT_FILENAME'])) {
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname('debaser');
			$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
			$module_id = $module->getVar('mid');
			$gperm_handler = &xoops_gethandler('groupperm');
			$myts =& MyTextSanitizer::getInstance();
		}

		$block = array();
		$result = $xoopsDB->query("SELECT xfid, title, artist, album, year, track, length, bitrate, frequence, genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 ORDER BY xfid DESC LIMIT ".$options[0]."");

		while ($myrow = $xoopsDB->fetchArray($result)) {
			if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'] , $groups, $module_id)) {
				$mp3files = array();
				$mp3files['id'] = intval($myrow['xfid']);
				$mp3files['title'] = $myts->htmlSpecialChars($myrow['title']);
				$mp3files['artist'] = $myts->htmlSpecialChars($myrow['artist']);
				$block['debaser_files'][] = $mp3files;
			}
		}

		return $block;
	}

	function b_debaser_latest_edit($options) {

		$form = ""._MB_DEBASER_BLOCLATE."<input type='text' size='3' maxlength='2' name='options[]' value='".$options[0]."' />&nbsp;"._MB_DEBASER_SONGS."";

		return $form;
	}

	function debaser_playlistblock() {

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		global $xoopsDB;

		$block = array();

	$getuserwithpl = $xoopsDB->query("SELECT a.debuid, a.publicplaylist, b.uid, b.uname FROM ".$xoopsDB->prefix('debaser_user')." a, ".$xoopsDB->prefix('users')." b WHERE a.publicplaylist = 1 AND b.uid = a.debuid");
	$anythere = $xoopsDB->getRowsNum($getuserwithpl);

if ($anythere > 0) {
	$pubplaylist = array();

	$seloptions = '';
	$imagerow = '';

	$getplaylistplayers = $xoopsDB->query("SELECT xpid, html_code, xspf, playericon FROM ".$xoopsDB->prefix('debaser_player')." WHERE xspf != '0' AND isactive = 1");

	while (list($xpid, $html_code, $xspf, $playericon) = $xoopsDB->fetchRow($getplaylistplayers)) {
		if ($html_code == 'external') {
		$imagerow .= '<button type="button" name="button'.$xpid.'" id="button'.$xpid.'" value="'.$xpid.'" onclick="self.location.href=\''.DEBASER_URL.'/getfile.php?op=playlist&amp;uid=\'+document.publicpl.playlist.options[document.publicpl.playlist.selectedIndex].value+\'&amp;playlistformat='.$xspf.'\'"><img src="'.DEBASER_UIMG.'/playericons/'.$playericon.'" width="20" height="20" alt="" title="" /></button> ';
		} else {
		$imagerow .= '<button type="button" name="button'.$xpid.'" id="button'.$xpid.'" value="'.$xpid.'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/player.php?playlist=\'+document.publicpl.playlist.options[document.publicpl.playlist.selectedIndex].value+\'&amp;player=\'+this.form.button'.$xpid.'.value,\'player\')"><img src="'.DEBASER_UIMG.'/playericons/'.$playericon.'" width="20" height="20" alt="" title="" /></button> ';
		}

	}

	while (list($debuid, $publ, $uid, $uname) = $xoopsDB->fetchRow($getuserwithpl)) {
		$seloptions .= '<option value="'.$debuid.'">'.$uname.'</option>';
			}
	$pubplaylist['options'] = $seloptions;
	$pubplaylist['imagerow'] = $imagerow;
	$block['playlist'] = $anythere;


	$block['publicplaylist'][] = $pubplaylist;
}

		return $block;

	}

	function b_debaser_rated_show($options) {

	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

	global $xoopsDB, $xoopsUser, $groups, $module_id, $myts, $gperm_handler;

	if (!preg_match('/\/debaser/', $_SERVER['SCRIPT_FILENAME']) || preg_match('/\/debaser\/comment(.*)/', $_SERVER['SCRIPT_FILENAME'])) {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('debaser');
		$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$module_id = $module->getVar('mid');
		$gperm_handler = &xoops_gethandler('groupperm');
		$myts =& MyTextSanitizer::getInstance();
	}

	$block = array();
	$sql = "SELECT xfid, title, artist, album, year, track, length, bitrate, frequence, genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 AND rating > 0 ORDER BY rating DESC LIMIT ".$options[0]."";
	$result = $xoopsDB->query($sql);

		while ($myrow = $xoopsDB->fetchArray($result)) {
if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'] , $groups, $module_id)) {
		$ratefiles = array();
		$ratefiles['id'] = intval($myrow['xfid']);
		$ratefiles['title'] = $myts->htmlSpecialChars($myrow['title']);
		$ratefiles['artist'] = $myts->htmlSpecialChars($myrow['artist']);

		$block['debaser_ratefiles'][] = $ratefiles;
}
	}
	return $block;
}

	function b_debaser_rated_edit($options) {

	$form = ""._MB_DEBASER_BLOCLATE."<input type='text' size='3' maxlength='2' name='options[]' value='".$options[0]."' />&nbsp;"._MB_DEBASER_SONGS."";

	return $form;
	}

	function b_debaser_views_show($options) {

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

	global $xoopsDB, $groups, $module_id, $xoopsUser, $gperm_handler, $myts;

	if (!preg_match('/\/debaser/', $_SERVER['SCRIPT_FILENAME']) || preg_match('/\/debaser\/comment(.*)/', $_SERVER['SCRIPT_FILENAME'])) {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('debaser');
		$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$module_id = $module->getVar('mid');
		$gperm_handler = &xoops_gethandler('groupperm');
		$myts =& MyTextSanitizer::getInstance();
	}

	$block = array();
	$result = $xoopsDB->query("SELECT xfid, title, artist, album, year, track, length, bitrate, frequence, genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 AND views > 0 ORDER BY views DESC LIMIT ".$options[0]."");

		while ( $myrow = $xoopsDB->fetchArray($result) ) {
if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'], $groups, $module_id)) {
		$views = array();
		$views['id'] = intval($myrow['xfid']);
		$views['title'] = $myts->htmlSpecialChars($myrow['title']);
		$views['artist'] = $myts->htmlSpecialChars($myrow['artist']);

		$block['debaser_filesviews'][] = $views;
}
	}

	return $block;
}

	function b_debaser_views_edit($options) {

	$form = ""._MB_DEBASER_BLOCLATE."<input type='text' size='3' maxlength='2' name='options[]' value='".$options[0]."' />&nbsp;"._MB_DEBASER_SONGS."";

	return $form;
	}

	function debaser_showradio() {

		global $xoopsDB, $xoTheme;

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		$block = array();

		$result = $xoopsDB->query("SELECT radio_id, radio_name, canplay FROM ".$xoopsDB->prefix('debaserradio')." ORDER BY radio_name ASC");

		$radiolist = array();

		$seloptions = '';

		while (list($radio_id, $radio_name, $canplay) = $xoopsDB->fetchRow($result)) {
			$seloptions .= '<option value="'.$radio_id.'">'.$radio_name.'</option>';
		}
		$radiolist['options'] = $seloptions;
		$block['radiolist'][] = $radiolist;

		$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function () { $("#radioselect").change(function () { $("#radiolistresponse").load("'.DEBASER_URL.'/ajaxed.php", { action : "radioselecter", radioselect : this.value }); }); }); ');

		return $block;
	}

	function debaser_showtv() {

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		global $xoopsDB, $xoTheme;

		$block = array();

		$result = $xoopsDB->query("SELECT tv_id, tv_name, canplay FROM ".$xoopsDB->prefix('debaser_tv')." ORDER BY tv_name ASC");

		$tvlist = array();

		$seloptions = '';

		while (list($tv_id, $tv_name, $canplay) = $xoopsDB->fetchRow($result)) {
			$seloptions .= '<option value="'.$tv_id.'">'.$tv_name.'</option>';
		}
		$radiolist['options'] = $seloptions;
		$block['tvlist'][] = $tvlist;

		$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function () { $("#tvselect").change(function () {
$("#tvlistresponse").load("'.DEBASER_URL.'/ajaxed.php?gettv=1", { tvselect : this.value }); }); });');

		return $block;
	}

?>