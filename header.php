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

	include '../../mainfile.php';

	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

	include_once DEBASER_RINC.'/functions.php';

	global $xoopsDB;

	$module_handler = &xoops_gethandler('module');
	$module =& $module_handler->getByDirname('debaser');
	$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$module_id = $module->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module_id);

	if (is_object($xoopsUser)) {
		$current_userid = $xoopsUser->getVar('uid');
		if ($xoopsUser->isAdmin($xoopsModule->mid())) $is_deb_admin = 1;
		else $is_deb_admin = 0;
	} else {
		$current_userid = 'guest';
	}

	$wysiwyg_editor = array('tinymce', 'ckeditor');

	if (!in_array($xoopsModuleConfig['use_wysiwyg'], $wysiwyg_editor)) {
		$html = 0;
		$dobr = 1;
	} else {
		$html = 1;
		$dobr = 0;
	}

	include_once XOOPS_ROOT_PATH.'/class/module.textsanitizer.php';
	$myts =& MyTextSanitizer::getInstance();

	if ($xoopsModuleConfig['multilang'] == 0) {
		$langa = $xoopsDB->quoteString($xoopsModuleConfig['masterlang']);
		$langb = $xoopsModuleConfig['masterlang'];

	} else {
		// get the language - could be shorter?
		$cookietrue = (isset($_COOKIE['lang'])) ? '1' : '0';
		$gettrue = (isset($_GET['lang'])) ? '1' : '0';

		if ($cookietrue == '0' && $gettrue == '0') {
			$langa = $xoopsDB->quoteString($xoopsConfig['language']);
			$langb = $xoopsConfig['language'];
		}

		if (($cookietrue == '1') || ($cookietrue == '1' && $gettrue == '1')) {
			$langa = $xoopsDB->quoteString($_COOKIE['lang']);
			$langb = $_COOKIE['lang'];
		}

		if (($gettrue == '1') || ($gettrue == '1' && $cookietrue == '0')) {
			$langa = $xoopsDB->quoteString($_GET['lang']);
			$langb = $_GET['lang'];
		}
	}

?>