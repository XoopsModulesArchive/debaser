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

function tvdisplay() {

	global $xoopsDB, $imagearray;

	debaser_adminMenu();

	echo "<div style='float:left; width:100%;'><table class='outer' width='100%'><tr><td colspan='4' class='odd'><strong>"._AM_DEBASERTV_PROG."</strong></td></tr>";

		$result = $xoopsDB->query("SELECT tv_id, tv_name FROM ".$xoopsDB->prefix('debaser_tv')." ORDER BY tv_name ASC");
		$hastv = $xoopsDB->getRowsNum($result);

	while (list($tv_id, $tv_name) = $xoopsDB->fetchRow($result)) {

	echo "<tr><td class='even' align='center' width='40'><strong>".$tv_id."</strong></td><td class='odd'>".$tv_name."</td><td class='even' align='center' width='40'><a href='tvindex.php?op=tvedit&amp;tvid=".$tv_id."'>".$imagearray['editimg']."</a></td><td class='odd' align='center' width='40'><a href='tvindex.php?op=tvdelete&amp;tvid=".$tv_id."'>".$imagearray['deleteimg']."</a></td></tr>";
	}
	echo "</table><br />";
	if (($hastv < 1)) {
	echo "<em>"._AM_DEBASERTV_NOST."</em> ";
	}
}


function tvdelete() {
	global $xoopsDB;

		$sql = "DELETE FROM ".$xoopsDB->prefix('debaser_tv')." WHERE tv_id = ".intval($_GET['tvid'])." ";
		$xoopsDB->queryF($sql);
	redirect_header('tvindex.php', 2, _AM_DEBASER_DBUPDATE);
}

	function tvedit() {

	global $tvid, $xoopsDB;

	debaser_adminMenu();

	$sql = "SELECT tv_id, tv_name, tv_stream, tv_url, tv_picture, canplay FROM ".$xoopsDB->prefix('debaser_tv')." WHERE tv_id = ".intval($_GET['tvid'])."";

	$result = $xoopsDB->query($sql);

	list($tv_id, $tv_name, $tv_stream, $tv_url, $tv_picture, $canplay) = $xoopsDB->fetchRow($result);

	$edform = new XoopsThemeForm(_AM_DEBASERTV_EDIT, 'tvedit', 'tvindex.php');
	$edform->setExtra('enctype="multipart/form-data"');
	$edform->addElement(new XoopsFormText(_AM_DEBASERTV_NAME, 'tvname', 50, 255, $tv_name), true);
	$edform->addElement(new XoopsFormText(_AM_DEBASERRAD_URL, 'tvurl', 50, 255, $tv_url), false);
	$edform->addElement(new XoopsFormText(_AM_DEBASERRAD_STREAM, 'tvstream', 50, 255, $tv_stream), true);

	$graph_array = &XoopsLists::getImgListAsArray(DEBASER_RIMG.'/tv/');
	$indeximage_select = new XoopsFormSelect('', 'tvpicture', $tv_picture);
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"tvpicture\", \"modules/debaser/images/tv/\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASERTV_PICT, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);
	$indeximage_tray->addElement(new XoopsFormFile('', 'tvimg', 0));
		if (!empty($tv_picture)) $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . DEBASER_UIMG . "/tv/" . $tv_picture . "' name='image' id='image' alt='' />"));
		else $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
	$edform->addElement($indeximage_tray);
	$preselect = explode(' ', $canplay);
	$playersql = $xoopsDB->query("SELECT xpid, name FROM ".$xoopsDB->prefix('debaser_player')."");
	$formplayer = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'canplay', $preselect, 4, true);
		while(list($xpid, $name) = $xoopsDB->fetchRow($playersql)) {
			$formplayer->addOption($xpid, $name);
		}
	$edform->addElement($formplayer);

	$edform->addElement(new XoopsFormHidden('op', 'tvchange'));
	$edform->addElement(new XoopsFormHidden('tvid', $tv_id));
	$edform->addElement(new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit'));
	$edform->display();

	}

function tvchange() {

	global $xoopsDB;

	if (isset($_POST['canplay']) && !empty($_POST['canplay']))
		$canplay = implode(' ', $_POST['canplay']);
	else
		$canplay = '';

		if (isset($_FILES['tvimg']) && !empty($_FILES['tvimg']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_RIMG.'/tv/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 100000, 150, 150);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$tvpicture = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$tvpicture = $_POST['tvpicture'];
		}

		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_tv')." SET tv_name=".$xoopsDB->quoteString($_POST['tvname']).", tv_stream=".$xoopsDB->quoteString($_POST['tvstream']).", tv_url=".$xoopsDB->quoteString($_POST['tvurl']).", tv_picture=".$xoopsDB->quoteString($tvpicture).", canplay = ".$xoopsDB->quoteString($canplay)." WHERE tv_id = ".intval($_POST['tvid'])."");

	redirect_header('tvindex.php', 2, _AM_DEBASER_DBUPDATE);
}

	function tvnew() {

	global $xoopsDB;

	debaser_adminMenu();

	$nuform = new XoopsThemeForm(_AM_DEBASERTV_NEW, 'tvnew', 'tvindex.php');
	$nuform->setExtra('enctype="multipart/form-data"');
	$nuform->addElement(new XoopsFormText(_AM_DEBASERTV_NAME, 'tvname', 50, 50), true);
	$nuform->addElement(new XoopsFormText(_AM_DEBASERRAD_URL, 'tvurl', 50, 255, 'http://'), false);
	$nuform->addElement(new XoopsFormText(_AM_DEBASERRAD_STREAM, 'tvstream', 50, 255, 'http://'), true);

	$graph_array = &XoopsLists::getImgListAsArray(DEBASER_RIMG.'/tv/');
	$indeximage_select = new XoopsFormSelect('', 'tvpicture', '');
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"tvpicture\", \"modules/debaser/images/tv/\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASERTV_PICT, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);
	$indeximage_tray->addElement(new XoopsFormFile('', 'tvimg', 0));
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

	if ($mimeyes == 1) {
		$nuform->insertBreak(_AM_DEBASERTV_NOPLAY, 'head');
	}

	$nuform->addElement(new XoopsFormHidden('op', 'tvadd'));
	$submitbutton = new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit');
	if ($mimeyes == 1) $submitbutton->setExtra(" disabled=\"disabled\" style='color:gray; border:1px solid gray;' ", false);
	$nuform->addElement($submitbutton);
	$nuform->display();
	}



function tvadd() {

	global $xoopsDB;

	if (isset($_POST['canplay']) && !empty($_POST['canplay']))
		$canplay = implode(' ', $_POST['canplay']);
	else
		$canplay = '';

		if (isset($_FILES['tvimg']) && !empty($_FILES['tvimg']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_RIMG.'/tv/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, 100000, 150, 150);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$tvpicture = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$tvpicture = $_POST['tvpicture'];
		}

		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_tv')." VALUES ('',".$xoopsDB->quoteString($_POST['tvname']).", ".$xoopsDB->quoteString($_POST['tvstream']).", ".$xoopsDB->quoteString($_POST['tvurl']).", ".$xoopsDB->quoteString($tvpicture).", ".$xoopsDB->quoteString($canplay).")");

	redirect_header('tvindex.php', 2, _AM_DEBASER_DBUPDATE);

}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

switch($op) {

	case "tvdisplay":
	tvdisplay();
	break;

	case "tvdelete":
	tvdelete();
	break;

	case "tvedit":
	tvedit();
	break;

	case "tvchange":
	tvchange();
	break;

	case "tvnew":
	tvnew();
	break;

	case "tvadd":
	tvadd();
	break;

	default:
	tvdisplay();
	break;

}

xoops_cp_footer();

?>