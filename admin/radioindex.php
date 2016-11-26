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

	include_once 'admin_header.php';
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	xoops_cp_header();

	function radiodisplay() {

		global $xoopsDB, $imagearray;

		debaser_adminMenu();

		echo '<div style="float:left; width:100%"><table class="outer" width="100%"><tr><td colspan="4" class="odd"><b>'._AM_DEBASERRAD_PROG.'</b></td></tr>';

		$result = $xoopsDB->query("SELECT radio_id, radio_name FROM ".$xoopsDB->prefix('debaserradio')." ORDER BY radio_name ASC");
		$hasradio = $xoopsDB->getRowsNum($result);

		while (list($radio_id, $radio_name) = $xoopsDB->fetchRow($result)) {

			echo '<tr><td class="even" align="center" width="40"><b>'.$radio_id.'</b></td><td class="odd">'.$radio_name.'</td><td class="even" align="center" width="40"><a href="radioindex.php?op=radioedit&amp;radioid='.$radio_id.'">'.$imagearray['editimg'].'</a></td><td class="odd" align="center" width="40"><a href="radioindex.php?op=radiodelete&amp;radioid='.$radio_id.'">'.$imagearray['deleteimg'].'</a></td></tr>';
		}

		echo '</table><br />';

		if (($hasradio < 1)) '<em>'._AM_DEBASERRAD_NOST.'</em>';

	}


	function radiodelete() {

		global $xoopsDB;

		$sql = "DELETE FROM ".$xoopsDB->prefix('debaserradio')." WHERE radio_id = ".intval($_GET['radioid'])." ";
		$xoopsDB->queryF($sql);
		redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);
	}

	function radioedit() {

		global $radioid, $xoopsDB;

		debaser_adminMenu();

		$sql = "SELECT radio_id, radio_name, radio_stream, radio_url, radio_picture, canplay FROM ".$xoopsDB->prefix('debaserradio')." WHERE radio_id = ".intval($_GET['radioid'])."";

		$result = $xoopsDB->query($sql);

		list($radio_id, $radio_name, $radio_stream, $radio_url, $radio_picture, $canplay) = $xoopsDB->fetchRow($result);

		$edform = new XoopsThemeForm(_AM_DEBASERRAD_EDIT, 'radioedit', 'radioindex.php');
		$edform->setExtra('enctype="multipart/form-data"');
		$edform->addElement(new XoopsFormText(_AM_DEBASERRAD_NAME, 'radioname', 50, 255, $radio_name), true);
		$edform->addElement(new XoopsFormText(_AM_DEBASERRAD_URL, 'radiourl', 50, 255, $radio_url), false);
		$edform->addElement(new XoopsFormText(_AM_DEBASERRAD_STREAM, 'radiostream', 50, 255, $radio_stream), true);
		$graph_array = &XoopsLists::getImgListAsArray(DEBASER_RIMG.'/radio/');
		$indeximage_select = new XoopsFormSelect('', 'radiopicture', $radio_picture);
		$indeximage_select -> addOption ('', '----------');
		$indeximage_select->addOptionArray($graph_array);
		$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"radiopicture\", \"modules/debaser/images/radio/\", \"\", \"" . XOOPS_URL . "\")'");
		$indeximage_tray = new XoopsFormElementTray(_AM_DEBASERRAD_PICT, '&nbsp;');
		$indeximage_tray->addElement($indeximage_select);
		$indeximage_tray->addElement(new XoopsFormFile('', 'radioimg', 0));
		if (!empty($radio_picture)) $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . DEBASER_UIMG . "/radio/" . $radio_picture . "' name='image' id='image' alt='' />"));
		else $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
		$edform->addElement($indeximage_tray);
		$preselect = explode(' ', $canplay);
		$playersql = $xoopsDB->query("SELECT xpid, name FROM ".$xoopsDB->prefix('debaser_player')."");
		$formplayer = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'canplay', $preselect, 4, true);
		while(list($xpid, $name) = $xoopsDB->fetchRow($playersql)) {
			$formplayer->addOption($xpid, $name);
		}
		$edform->addElement($formplayer);
		$edform->addElement(new XoopsFormHidden('op', 'radiochange'));
		$edform->addElement(new XoopsFormHidden('radioid', $radio_id));
		$edform->addElement(new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit'));
		$edform->display();

	}

	function radiochange() {

		global $xoopsDB;

		if (isset($_POST['canplay']) && !empty($_POST['canplay'])) $canplay = implode(' ', $_POST['canplay']);
		else $canplay = '';

		if (isset($_FILES['radioimg']) && !empty($_FILES['radioimg']['name'])) {

			include_once XOOPS_ROOT_PATH.'/class/uploader.php';

			$uploaddir = DEBASER_RIMG.'/radio/';
			$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
			$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 100000, 150, 150);

			if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {

				if (!$uploader->upload()) echo $uploader->getErrors();
				else $radiopicture = $uploader->getSavedFileName();

			} else {
				echo $uploader->getErrors();
			}
		} else {
			$radiopicture = $_POST['radiopicture'];
		}

		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaserradio')." SET radio_name=".$xoopsDB->quoteString($_POST['radioname']).", radio_stream=".$xoopsDB->quoteString($_POST['radiostream']).", radio_url=".$xoopsDB->quoteString($_POST['radiourl']).", radio_picture=".$xoopsDB->quoteString($radiopicture).", canplay = ".$xoopsDB->quoteString($canplay)." WHERE radio_id = ".intval($_POST['radioid'])."");

	redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);

	}

	function radionew() {

	global $xoopsDB;

	debaser_adminMenu();

	$nuform = new XoopsThemeForm(_AM_DEBASERRAD_NEW, 'radionew', 'radioindex.php');
	$nuform->setExtra('enctype="multipart/form-data"');
	$nuform->addElement(new XoopsFormText(_AM_DEBASERRAD_NAME, 'radioname', 50, 50), true);
	$nuform->addElement(new XoopsFormText(_AM_DEBASERRAD_URL, 'radiourl', 50, 255, 'http://'), false);
	$nuform->addElement(new XoopsFormText(_AM_DEBASERRAD_STREAM, 'radiostream', 50, 255, 'http://'), true);

	$graph_array = &XoopsLists::getImgListAsArray(DEBASER_RIMG.'/radio/');
	$indeximage_select = new XoopsFormSelect('', 'radiopicture', '');
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"radiopicture\", \"modules/debaser/images/radio/\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASERRAD_PICT, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);
	$indeximage_tray->addElement(new XoopsFormFile('', 'radioimg', 0));
	$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
	$nuform->addElement($indeximage_tray);

	$playersql = $xoopsDB->query("SELECT xpid, name FROM ".$xoopsDB->prefix('debaser_player')."");

	$mimeyes = 1;
	$formplayer = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'canplay', '', 4, true);
		while(list($xpid, $name) = $xoopsDB->fetchRow($playersql)) {
			$formplayer->addOption($xpid, $name);
			$mimeyes++;
		}
	$nuform->addElement($formplayer);

	if ($mimeyes == 1) $nuform->insertBreak(_AM_DEBASERRAD_NOPLAY, 'head');

	$nuform->addElement(new XoopsFormHidden('op', 'radioadd'));
	$submitbutton = new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit');
	if ($mimeyes == 1) $submitbutton->setExtra(" disabled=\"disabled\" style='color:gray; border:1px solid gray;' ", false);
	$nuform->addElement($submitbutton);
	$nuform->display();

	echo '<br /><a href="http://www.radio-locator.com" target="_blank">Open MIT Radio Locator</a>';

	}

	function radioadd() {

		global $xoopsDB;

		if (isset($_POST['canplay']) && !empty($_POST['canplay'])) $canplay = implode(' ', $_POST['canplay']);
		else $canplay = '';

		if (isset($_FILES['radioimg']) && !empty($_FILES['radioimg']['name'])) {

			include_once XOOPS_ROOT_PATH.'/class/uploader.php';

			$uploaddir = DEBASER_RIMG.'/radio/';
			$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
			$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 100000, 150, 150);

			if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {

				if (!$uploader->upload()) echo $uploader->getErrors();
				else $radiopicture = $uploader->getSavedFileName();

			} else {
				echo $uploader->getErrors();
			}
		} else {
			$radiopicture = $_POST['radiopicture'];
		}

		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaserradio')." VALUES ('',".$xoopsDB->quoteString($_POST['radioname']).", ".$xoopsDB->quoteString($_POST['radiostream']).", ".$xoopsDB->quoteString($_POST['radiourl']).", ".$xoopsDB->quoteString($radiopicture).", ".$xoopsDB->quoteString($canplay).")");

		redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch($op) {

		case "radiodisplay":
		radiodisplay();
		break;

		case "radiodelete":
		radiodelete();
		break;

		case "radioedit":
		radioedit();
		break;

		case "radiochange":
		radiochange();
		break;

		case "radionew":
		radionew();
		break;

		case "radioadd":
		radioadd();
		break;

		default:
		radiodisplay();
		break;

	}

	xoops_cp_footer();

?>