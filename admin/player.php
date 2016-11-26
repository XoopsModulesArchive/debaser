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

    include 'admin_header.php';
    include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

	// index for player management
	function playermanager() {

	global $xoopsDB, $imagearray, $xoopsModuleConfig;
	$start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;

	$sql = 'SELECT xpid, name FROM '.$xoopsDB->prefix('debaser_player').' ORDER BY xpid';
	$result = $xoopsDB->query($sql, $xoopsModuleConfig['indexperpage'], $start);
	$result2 = $xoopsDB->query($sql);
	$totalarts = $xoopsDB->getRowsNum($result2);
	debaser_adminMenu();

	echo "<table class='outer' align='center' width='100%' cellspacing='1' cellpadding='0' id='listplayers'>";

		while (list($xpid, $player) = $xoopsDB->fetchRow($result)) {
			$playername = str_replace(' ', '%20', $player);
			echo "<tr><td class='even'>".$player."</td>";
			echo "<td class='odd' width='40' align='center'><a href='".DEBASER_URL."/admin/player.php?op=editplayer&amp;xpid=".$xpid."'>".$imagearray['editimg']."</a></td>";
			echo "<td class='even' width='40' align='center'><a href='player.php?op=deleteplayer&amp;xpid=".$xpid."&amp;playername=".$playername."'>".$imagearray['deleteimg']."</a></td></tr>";
		}
	$pagenav = new XoopsPageNav( $totalarts, $xoopsModuleConfig['indexperpage'], $start, 'start', 'op=playermanager');
	echo "</table><div align='right'>".$pagenav->renderNav()."</div><br /><br />";

	}

	function addplayer() {
	debaser_adminMenu();
	global $xoopsDB;

	$nuform = new XoopsThemeForm(_AM_DEBASER_NEWPLAYER, 'newplayerform', 'player.php');
	$nuform->setExtra('enctype="multipart/form-data"');
	$nuform->addElement(new XoopsFormText(_AM_DEBASER_NAME, 'newplayername', 50, 50), true);
	$nuform->addElement(new XoopsFormTextArea (_AM_DEBASER_CODE, 'newplayer', '', 15, 50), false);
	$nuform->addElement(new XoopsFormText (_AM_DEBASER_HEIGHT, 'playerheight', 4, 4), false);
	$nuform->addElement(new XoopsFormText(_AM_DEBASER_WIDTH, 'playerwidth', 4, 4), false);
	$nuform->addElement(new XoopsFormText(_AM_DEBASER_AUTOSTART, 'autostart', 11, 10));
	$formxspf = new XoopsFormSelect(_AM_DEBASER_XSPF, 'xspf', '', 1, false);
	$formxspf->addOption('0', '----');
	$formxspf->addOption('xml', 'XSPF');
	$formxspf->addOption('smil', 'SMIL');
	$formxspf->addOption('wpl', 'WPL');
	$nuform->addElement($formxspf);
	$nuform->addElement(new XoopsFormRadioYN(_AM_DEBASER_PLAYERACTIVE, 'isactive', 0));
	$nuform->addElement(new XoopsFormRadioYN(_AM_DEBASER_PLATFORM, 'platform', 0));
	$nuform->addElement(new XoopsFormRadioYN(_AM_DEBASER_EQUALIZER, 'equalizer', 0));
	$nuform->addElement(new XoopsFormRadioYN(_AM_DEBASER_EMBEDDING, 'embedding', 0));
	$graph_array = &XoopsLists::getImgListAsArray(DEBASER_ROOT.'/images/playericons/');
	$indeximage_select = new XoopsFormSelect('', 'playericon', '');
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"playericon\", \"modules/debaser/images/playericons/\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_SELPLAYICON, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);
	$indeximage_tray->addElement(new XoopsFormFile('', 'playerimg', 0));
	$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
	$nuform->addElement($indeximage_tray);
	$nuform->addElement(new XoopsFormHidden('op', 'newplayer'));

	$playersql = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')."");
	$formfileext = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'canplay', '', 4, true);
		while(list($mime_ext) = $xoopsDB->fetchRow($playersql)) {
			$formfileext->addOption($mime_ext, $mime_ext);
		}
	$nuform->addElement($formfileext);
	$nuform->addElement(new XoopsFormText(_AM_DEBASER_URLTOSCRIPT, 'urltoscript', 50, 50), false);
	$nuform->addElement(new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit'));
	$nuform->display();
	}

	// function for modifying the player
	function editplayer() {

	global $xoopsDB, $myts;

	$result = $xoopsDB->query("SELECT xpid, name, html_code, height, width, autostart, xspf, playericon, canplay, urltoscript, isactive, platform, equalizer, embedding FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid=".intval($_GET['xpid'])." ");

	list($xpid, $name, $html_code, $height, $width, $autostart, $xspf, $playericon, $canplay, $urltoscript, $isactive, $platform, $equalizer, $embedding) = $xoopsDB->fetchRow($result);

	$edform = new XoopsThemeForm(_AM_DEBASER_EDITPLAYER, 'editplayerform', 'player.php');
	$edform->setExtra('enctype="multipart/form-data"');
	$edform->addElement(new XoopsFormText(_AM_DEBASER_NAME, 'namenew', 50, 50, $name), true);
	$edform->addElement(new XoopsFormTextArea (_AM_DEBASER_CODE, 'playernew', $myts->htmlSpecialChars($html_code), 15, 50), false);
	$edform->addElement(new XoopsFormText (_AM_DEBASER_HEIGHT, 'playerheight', 4, 4, $height), false);
	$edform->addElement(new XoopsFormText(_AM_DEBASER_WIDTH, 'playerwidth', 4, 4, $width), false);
	$edform->addElement(new XoopsFormText(_AM_DEBASER_AUTOSTART, 'autostart', 11, 10, $autostart), false);
	$formxspf = new XoopsFormSelect(_AM_DEBASER_XSPF, 'xspf', $xspf, 1, false);
	$formxspf->addOption('0', '----');
	$formxspf->addOption('xml', 'XSPF');
	$formxspf->addOption('smil', 'SMIL');
	$formxspf->addOption('wpl', 'WPL');
	$edform->addElement($formxspf);
	$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_PLAYERACTIVE, 'isactive', $isactive));
	$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_PLATFORM, 'platform', $platform));
	$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_EQUALIZER, 'equalizer', $equalizer));
	$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_EMBEDDING, 'embedding', $embedding));
		$graph_array = &XoopsLists::getImgListAsArray(DEBASER_RIMG . "/playericons/");
		$indeximage_select = new XoopsFormSelect('', 'playericon', $playericon);
		$indeximage_select -> addOption ('', '----------');
		$indeximage_select->addOptionArray($graph_array);
		$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"playericon\", \"modules/debaser/images/playericons/\", \"\", \"" . XOOPS_URL . "\")'");
		$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_SELPLAYICON, '&nbsp;');
		$indeximage_tray->addElement($indeximage_select);
		$indeximage_tray->addElement(new XoopsFormFile('', 'playerimg', 0));

		if (!empty($playericon)) $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='".DEBASER_UIMG."/playericons/" . $playericon . "' name='image' id='image' alt='' />"));
		else $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));

	$edform->addElement($indeximage_tray);
	$edform->addElement(new XoopsFormHidden('op', 'changeplayer'));
	$edform->addElement(new XoopsFormHidden('xpid', $xpid));
	$preselect = explode(' ', $canplay);
	$playersql = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')."");
	$formfileext = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'canplay', $preselect, 4, true);
		while(list($mime_ext) = $xoopsDB->fetchRow($playersql)) {
			$formfileext->addOption($mime_ext, $mime_ext);
		}
	$edform->addElement($formfileext);
	$edform->addElement(new XoopsFormText(_AM_DEBASER_URLTOSCRIPT, 'urltoscript', 50, 50, $urltoscript), false);
	$edform->addElement(new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit'));
	debaser_adminMenu();
	$edform->display();
	}

	// function for saving new players
	function newplayer() {

	global $xoopsDB, $myts;

	if (isset($_POST['canplay']) && !empty($_POST['canplay']))
		$canplay = implode(' ', $_POST['canplay']);
	else
		$canplay = '';

		if (isset($_FILES['playerimg']) && !empty($_FILES['playerimg']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_RIMG.'/playericons/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 50000, 50, 50);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$playericon = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$playericon = $_POST['playericon'];
		}

	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_player')." (name, html_code, width, height, autostart, xspf, playericon, canplay, urltoscript, isactive, platform, equalizer, embedding) VALUES (".$xoopsDB->quoteString($_POST['newplayername']).", '".$myts->stripSlashesGPC($_POST['newplayer'])."', ".intval($_POST['playerwidth']).", ".intval($_POST['playerheight']).", ".$xoopsDB->quoteString($_POST['autostart']).", ".$xoopsDB->quoteString($_POST['xspf']).", ".$xoopsDB->quoteString($playericon).", ".$xoopsDB->quoteString($canplay).", ".$xoopsDB->quoteString($_POST['urltoscript']).", ".intval($_POST['isactive']).", ".intval($_POST['platform']).", ".intval($_POST['equalizer']).", ".intval($_POST['embedding']).") ");

	redirect_header('player.php?op=playermanager', 2, _AM_DEBASER_NEWPLAYADD);
	}

	// function for saving player changes
	function changeplayer() {

	global $xoopsDB, $myts;

	if (isset($_POST['canplay']) && !empty($_POST['canplay']))
		$canplay = implode(' ', $_POST['canplay']);
	else
		$canplay = '';

		if (isset($_FILES['playerimg']) && !empty($_FILES['playerimg']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_RIMG.'/playericons/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 50000, 50, 50);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$playericon = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$playericon = $_POST['playericon'];
		}

	$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_player')." SET html_code = '".$myts->stripSlashesGPC($_POST['playernew'])."', name = ".$xoopsDB->quoteString($_POST['namenew']).", height = ".intval($_POST['playerheight']).", width = ".intval($_POST['playerwidth']).", autostart = ".$xoopsDB->quoteString($_POST['autostart']).", xspf = ".$xoopsDB->quoteString($_POST['xspf']).", playericon = ".$xoopsDB->quoteString($playericon).", canplay = ".$xoopsDB->quoteString($canplay).", urltoscript = ".$xoopsDB->quoteString($_POST['urltoscript']).", isactive = ".intval($_POST['isactive']).", platform = ".intval($_POST['platform']).", equalizer = ".intval($_POST['equalizer']).", embedding = ".intval($_POST['embedding'])." WHERE xpid=".intval($_POST['xpid'])." ");

	redirect_header('player.php?op=playermanager', 2, _AM_DEBASER_DBUPDATE);
	}

	// function for deleting player when confirmed
	function deleteplayer($del=0) {

	global $xoopsDB;

		if (isset($_POST['del']) && $_POST['del'] == 1) {
		$sql = "DELETE FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid=".intval($_POST['xpid'])." ";

			if ($xoopsDB->query($sql)) {
			redirect_header('player.php?op=playermanager', 2, $_POST['playername']._AM_DEBASER_DELETED);
			}
			else {
			redirect_header('player.php?op=playermanager', 2, $_POST['playername']._AM_DEBASER_NOTDELETED);
			}
		exit();
		}
		else {
		xoops_cp_header();
		echo "<h4>"._AM_DEBASER_PLAYERADMIN."</h4>";
		$playername = str_replace('%20', ' ', $_GET['playername']);
		xoops_confirm(array('playername' => $playername, 'xpid' => $_GET['xpid'], 'del' => 1), 'player.php?op=deleteplayer', _AM_DEBASER_SUREDELETEPLAYER);
		xoops_cp_footer();
		}
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'deleteplayer':
		deleteplayer();
		break;

		case 'addplayer':
		xoops_cp_header();
		addplayer();
		xoops_cp_footer();
		break;

		case 'newplayer':
		newplayer();
		break;

		case 'editplayer':
		xoops_cp_header();
		editplayer();
		xoops_cp_footer();
		break;

		case 'changeplayer':
		xoops_cp_header();
		changeplayer();
		xoops_cp_footer();
		break;

		case 'default':
		default:
		xoops_cp_header();
		playermanager();
		xoops_cp_footer();
		break;
	}
?>