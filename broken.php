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
	$xoopsOption['template_main'] = 'debaser_broken.html';

	function debaserreportbroken() {

		global $xoopsDB, $xoopsModuleConfig, $current_userid, $xoopsConfig, $xoopsUser, $xoTheme;

		if ($current_userid == 'guest' || !isset($_GET['brokenid']) || empty($_GET['brokenid'])) redirect_header('index.php', 2, _NOPERM);

		include XOOPS_ROOT_PATH.'/header.php';

		$result = $xoopsDB->query("SELECT title, artist FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid=".intval($_GET['brokenid'])."");

		list($title, $artist) = $xoopsDB->fetchRow($result);

		$brokenform = new XoopsThemeForm(_MD_DEBASER_REPORTBROKEN, 'brokenform', 'broken.php', 'post', true);
		$reasons = new XoopsFormSelect(_MD_DEBASER_BROKENREASON, 'reason', '', 1, false);
		$reasonarray = explode(' ', $xoopsModuleConfig['brokenreason']);
		$reasons->addOption('0', '-----');
		foreach ($reasonarray as $reason) {
			$reasons->addOption($reason, constant($reason));
		}
		$brokenform->addElement($reasons);
		$brokenform->addElement(new XoopsFormTextArea(_MD_DEBASER_BROKCOMMENT, 'brokcomment', '', 5, 50));
		$brokenform->addElement(new XoopsFormHidden('xfid', $_GET['brokenid']));
		$brokenform->addElement(new XoopsFormHidden('op', 'sendit'));
		$brokenform->addElement(new XoopsFormHidden('reporterid', $current_userid));
		$brokenform->addElement(new XoopsFormHidden('artisttitle', $artist.' - '.$title));
		$brokenform->addElement(new XoopsFormButton( '', 'save', _SUBMIT, 'submit' ));
		$xoopsTpl->assign('brokenform', $brokenform->render());
		$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));
		include XOOPS_ROOT_PATH.'/footer.php';
	}

	function debasersendit() {

		global $xoopsModule, $xoopsDB;

		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
			exit();
		}

		$result1 = $xoopsDB->query("SELECT whichid FROM ".$xoopsDB->prefix('debaser_broken')." WHERE whichid = ".intval($_POST['xfid'])."");
		$hasresult = $xoopsDB->getRowsNum($result1);

		if ($hasresult != 1) {

		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['BROKEN_NAME'] = $_POST['artisttitle'];
		$tags['BROKEN_REPORTER'] = XOOPS_URL.'/userinfo.php?uid='.$_POST['reporterid'];
		$tags['BROKEN_URL'] = DEBASER_URL. '/singlefile.php?id='.$_POST['xfid'];
		$tags['BROKEN_COMM'] = $_POST['brokcomment'];
		$notification_handler->triggerEvent('global', 0, 'reportbroken', $tags);

		$result = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_broken')." (whichid, brokentitle, reporter, reason) VALUES (".intval($_POST['xfid']).", ".$xoopsDB->quoteString($_POST['artisttitle']).", ".intval($_POST['reporterid']).", ".$xoopsDB->quoteString($_POST['brokcomment']).")");

		if ($result) redirect_header('index.php', 2, _MD_DEBASER_DBUPDATED);
		else redirect_header('index.php', 2, _MD_DEBASER_DBNOTUPDATED);
		} else {
			redirect_header('index.php', 2, _MD_DEBASER_ALREADYREP);
		}
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'sendit':
		debasersendit();
		break;

		case 'default':
		default:
		debaserreportbroken();
		break;
	}

?>