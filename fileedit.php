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
	include_once DEBASER_CLASS.'/debasertree.php';

	$xoopsOption['template_main'] = 'debaser_fileedit.html';

	if ($current_userid == 'guest' || !@array_intersect($xoopsModuleConfig['canedit'], $groups)) redirect_header('index.php', 2, _NOPERM);

	function editmpegs() {

		global $xoopsDB, $mpegid, $artist, $genrelist, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $current_userid, $groups, $module_id, $gperm_handler, $editor, $xoopsConfig, $is_deb_admin, $langa, $xoTheme;

		include XOOPS_ROOT_PATH.'/header.php';

		$sql = "SELECT xfid, title, artist, album, year, track, genreid, length, approved, bitrate, frequence, fileext, uid, linkcode, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid=".intval($_GET['mpegid'])."";
		$result = $xoopsDB->query($sql);

		list($xfid, $title, $artist, $album, $year, $track, $genreid, $length, $approved, $bitrate, $frequence, $fileext, $uid, $linkcode, $haslofi) = $xoopsDB->fetchRow($result);

		if (($current_userid == $uid && @array_intersect($xoopsModuleConfig['candelete'], $groups)) || $is_deb_admin == true) $deleteallowed = 1;
		else $deleteallowed = 0;

		$edform = new XoopsThemeForm(_MD_DEBASER_EDITMPEG, 'editmpegform', 'fileedit.php', 'post', true);
		$edform->addElement(new XoopsFormHidden('mpegid', $xfid));
		$edform->addElement(new XoopsFormText(_MD_DEBASER_ARTIST, 'artist', 50, 255, $artist));
		$edform->addElement(new XoopsFormText(_MD_DEBASER_TITLE, 'title', 50, 255, $title));

		if ($linkcode != '') {
			if (strlen($linkcode) > 255) {
				$edform->addElement(new XoopsFormTextArea(_MD_DEBASER_TYPEOFLINK, 'linkcode', str_replace('\\', '', $linkcode), 10, 50));
			} else {
				$edform->addElement(new XoopsFormText(_MD_DEBASER_TYPEOFLINK, 'linkcode', 50, 255, $linkcode));
			}
		} else {
			$edform->addElement(new XoopsFormHidden('linkcode', ''));
		}

		$edform->addElement(new XoopsFormText(_MD_DEBASER_ALBUM, 'album', 50, 255, $album));
		$edform->addElement(new XoopsFormText(_MD_DEBASER_YEAR, 'year', 4, 4, $year));
		$edform->addElement(new XoopsFormHidden('op', 'saveeditmpegs'));

		if ($xoopsModuleConfig['multilang'] == 1) {
			$langlist = XoopsLists::getLangList();
			$flaglist = '';
			foreach ($langlist as $flags) {
				$flaglist .= '<img onclick="toggleMe3(\'editmpegform\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
			}

			$edform->addElement(new XoopsFormLabel(_MD_DEBASER_LANGSELECT, $flaglist));

			foreach ($langlist as $languagedesc) {
				$langresult = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = '$languagedesc' AND textfileid = ".intval($xfid)."");
				list ($description) = $xoopsDB->fetchRow($langresult);
				$languagedesc = get_debaserwysiwyg(_MD_DEBASER_COMMENT.$languagedesc, $languagedesc.'_description', $description, '100%', '400px', 'hiddentext');
				$edform->addElement($languagedesc);
				unset($languagedesc);
				}
		} else {
			$langresult = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = ".$xoopsDB->quote($xoopsConfig['language'])." AND textfileid = ".intval($xfid)."");
			list ($description) = $xoopsDB->fetchRow($langresult);
  			$edform->addElement(get_debaserwysiwyg(_MD_DEBASER_COMMENT, 'description', $description, 15, 60));
  			$edform->addElement(new XoopsFormHidden('singlelang', 'single'));
		}

		$edform->addElement(new XoopsFormText(_MD_DEBASER_TRACK, 'track', 3, 3, $track));

		if ($is_deb_admin == true) {
			$edform->addElement(new XoopsFormSelectUser(_MD_DEBASER_OWNER, 'uid', false, $uid, '1', false));
			$edform->addElement(new XoopsFormHidden('olduid', $uid));
		} else {
			$edform->addElement(new XoopsFormHidden('uid', $uid));
		}

		$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$langa, 'AND textfileid = 0', true);

		ob_start();
		@$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', $genreid, 0, 'genrefrom');

		// Wicked, evil, naughty zoot: i know!!
		if ($xoopsModuleConfig['multilang'] == 1) {
			$evilfallback = ob_get_contents();
			$evilfallback = strip_tags($evilfallback);
			$evilfallback = trim($evilfallback);

			if ($evilfallback == '') {
				ob_end_clean();
				$langa = $xoopsDB->quote($xoopsModuleConfig['masterlang']);
				$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$langa, 'AND textfileid = 0', true);
				ob_start();
				$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', $genreid, 0, 'genrefrom');
			}
		}

		$edform->addElement(new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents()));
		ob_end_clean();
		$edform->addElement(new XoopsFormText(_MD_DEBASER_LENGTH, 'length', 5, 5, $length));
		$edform->addElement(new XoopsFormText(_MD_DEBASER_BITRATE, 'bitrate', 3, 3, $bitrate));
		$edform->addElement(new XoopsFormText(_MD_DEBASER_FREQUENCY, 'frequence', 5, 5, $frequence));

		if ($is_deb_admin == true) $edform->addElement(new XoopsFormRadioYN(_MD_DEBASER_APPROVE, 'approved', $approved));
		else $edform->addElement(new XoopsFormHidden('approved', $approved));

			if ($xoopsModuleConfig['uselame'] == 1) {
				if ($haslofi == 1) {
					$yesorno = _YES;
					$edform->addElement(new XoopsFormHidden('haslofi', 'haslofi'));
				} else {
					$yesorno = _NO;
				}
				$edform->addElement(new XoopsFormLabel('haslofi', $yesorno));
			}

		$edform->addElement(new XoopsFormButton( '', 'save', _SUBMIT, 'submit' ));
		$xoopsTpl->assign('editmpeg', $edform->render());

		if ($xoopsModuleConfig['multilang'] == 1) {
			$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
			$multijs = 'window.onload = function() {';

			foreach ($langlist as $wotever) {
				$trnodedesc = $wotever.'_description';
				$multijs .= '
				var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
				'.$trnodedesc.'.style.display="none";';
			}
			$multijs .= '}';
			$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
		}

		$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

		include_once XOOPS_ROOT_PATH.'/footer.php';
		}

	function saveeditmpegs() {

		global $xoopsDB, $xoopsModuleConfig, $xoopsConfig;

		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
			exit();
		}

		if (isset($_POST['olduid'])) $uid = $_POST['uid'];
		else $uid = $_POST['olduid'];

		if (isset($_POST['singlelang']) && $_POST['singlelang'] == 'single') {
			$result1 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET title=".$xoopsDB->quoteString($_POST['title']).", artist=".$xoopsDB->quoteString($_POST['artist']).", album=".$xoopsDB->quoteString($_POST['album']).", year=".intval($_POST['year']).", track=".intval($_POST['track']).", genreid=".intval($_POST['genrefrom']).", length=".$xoopsDB->quoteString($_POST['length']).", approved=".intval($_POST['approved']).", bitrate=".intval($_POST['bitrate']).", frequence=".intval($_POST['frequence']).", uid = ".intval($uid).", linkcode = ".$xoopsDB->quoteString($_POST['linkcode'])." WHERE xfid=".intval($_POST['mpegid'])." ");
			$result2 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quote($_POST['description'])." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quote($xoopsConfig['language'])."");
		} else {
			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
			$i = 0;

			foreach ($langlist as $langcontent) {
				$postdescription = $bb[$i].'_description';
				$language = $bb[$i];

				if ($_POST[$postdescription] != '') {
					$getlang = $xoopsDB->query("SELECT textfileid FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quote("$bb[$i]")."");
					$getlangs = $xoopsDB->getRowsNum($getlang);

					if ($getlangs == 1) {
					$result2 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quoteString($_POST[$postdescription]).", language = ".$xoopsDB->quoteString("$language")." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quoteString("$language")."");
					} else {
					$result3 = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($_POST['mpegid']).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");
					}
				}
				$i++;

				unset($postdescription);
				unset($language);
			}
	}

	redirect_header('index.php',2,_MD_DEBASER_DBUPDATED);

	}

	function deletesong($del=0) {

	global $xoopsDB, $xoopsModule, $xoopsUser, $current_userid, $groups, $module_id, $gperm_handler, $xoopsModuleConfig;

		if (isset($_POST['del']) && $_POST['del'] == 1) {

		if (getthedir($_POST['uid']) != '') $userpath = 'user_'.$_POST['uid'].'_/';
		else $userpath = '';

		$sql = "DELETE FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($_POST['mpegid'])."";
		$sql2 = "DELETE FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_POST['mpegid'])."";
		$getgenreid = $xoopsDB->query("SELECT genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($_POST['mpegid'])."");
		list($genreid) = $xoopsDB->fetchRow($getgenreid);

			if ($xoopsDB->query($sql)) {
			$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total-1 WHERE genreid = ".intval($genreid)."");

			if (file_exists(DEBASER_RUP.'/'.$userpath.'lofi_'.$_POST['delfile'])) @unlink(DEBASER_RUP.'/'.$userpath.'lofi_'.$_POST['delfile']);

			@unlink(DEBASER_RUP.'/'.$userpath.$_POST['delfile']);
			xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['mpegid']);
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'song', $_POST['mpegid']);
			$member_handler = &xoops_gethandler('member');
			$poster = &$member_handler->getUser($_POST['uid']);
			$member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') - 1);
			redirect_header('index.php', 2, $_POST['delfile']._MD_DEBASER_DELETED);
			}
			else {
			redirect_header('index.php', 2, $_POST['delfile']._MD_DEBASER_NOTDELETED);
			}
		exit();
		}
		else {
		if (($current_userid == $_GET['uid'] && @array_intersect($xoopsModuleConfig['candelete'], $groups)) || $xoopsUser->isAdmin($xoopsModule->mid())) $deleteallowed = 1;
		else $deleteallowed = 0;

		if ($deleteallowed != 1) redirect_header('index.php', 2, _NOPERM);

		include_once XOOPS_ROOT_PATH.'/header.php';
		echo "<h4>"._MD_DEBASER_FILEADMIN."</h4>";

		if (isset($_POST['delfile']) && ($_POST['mpegid'])) {
		$delfile = $_POST['delfile'];
		$mpegid = $_POST['mpegid'];
		$uid = $_POST['uid'];
		}
		else {
		$delfile = $_GET['delfile'];
		$mpegid = $_GET['mpegid'];
		$uid = $_GET['uid'];
		}

		xoops_confirm(array('delfile' => $delfile, 'mpegid' => $mpegid, 'del' => 1, 'uid' => $uid), 'fileedit.php?op=deletesong', _MD_DEBASER_SUREDELETEFILE.' <b>'.$delfile.'</b>');
			include_once XOOPS_ROOT_PATH.'/footer.php';
		}
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'deletesong':
		deletesong();
		break;

		case 'saveeditmpegs':
		saveeditmpegs();
		break;

		case 'default':
		default:
		editmpegs();
		break;
	}

?>