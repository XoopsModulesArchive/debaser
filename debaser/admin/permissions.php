<?php
/**
 * $Id: permissions.php v 1.03 05 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.4
 * Release Date: 11 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

	include 'admin_header.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';

	xoops_cp_header();
	debaseradminMenu(_AM_DEBASER_PERM_MANAGEMENT);

	if ($xoopsModuleConfig['usecatperm'] == 1) {
	echo "
		<div style='float: left; width:100%;'>
		<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_DEBASER_PERM_CPERMISSIONS."</legend>\n
		<div style='padding: 2px;'>\n";

	$cat_form = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'DebaserCatPerm', _AM_DEBASER_PERM_CSELECTPERMISSIONS );

	$result = $xoopsDB->query("
	SELECT genreid, subgenreid, genretitle 
	FROM " . $xoopsDB->prefix('debaser_genre'));

		if ($xoopsDB->getRowsNum($result)) {

			while ($cat_row = $xoopsDB->fetchArray($result)) {
			$cat_form->addItem($cat_row['genreid'], $cat_row['genretitle'], $cat_row['subgenreid']);
			} 

		echo $cat_form->render();
		}
		else {
		echo "<div><strong>"._AM_DEBASER_PERM_CNOCATEGORY."</strong></div>";
		} 

	echo "</div></fieldset></div><br />";
	unset ($cat_form);
	}

/*
* File permission form
*/ 
	if ($xoopsModuleConfig['usefileperm'] == 1) {
	echo "
		<div style='float: left; width:100%;'>
		<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_DEBASER_PERM_FPERMISSIONS."</legend>\n
		<div style='padding: 2px;'>\n";
	$file_form = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'DebaserFilePerm', _AM_DEBASER_PERM_FSELECTPERMISSIONS);

	$result2 = $xoopsDB->query("
	SELECT xfid, title 
	FROM " . $xoopsDB->prefix('debaser_files'));

		if ($xoopsDB->getRowsNum($result2)) {

			while ($file_row = $xoopsDB->fetchArray($result2)) {
			$file_form->addItem($file_row['xfid'], $file_row['title'], 0);
			} 

		echo $file_form->render();
		} 
		else {
		echo "<div><strong>"._AM_DEBASER_PERM_FNOFILES."</strong></div>";
		} 

	echo "</div></fieldset></div><br />";
	unset ($file_form);
	}

	echo _AM_DEBASER_PERM_PERMSNOTE;

	xoops_cp_footer();

?>