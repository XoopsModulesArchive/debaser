<?php
// $Id: blocks/debaser_blocks.php,v 0.60 2004/06/30 10:00:00 frankblack Exp $
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

	function debaser_showradio() {

	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('debaser');
	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	global $xoopsDB;

		$myts =& MyTextSanitizer::getInstance();
		$block = array();
		$sql = "SELECT radio_id, radio_name FROM ".$xoopsDB->prefix('debaserradio')." ORDER BY radio_name ASC";
		$result = $xoopsDB->query($sql);

	while ( $myrow = $xoopsDB->fetchArray($result) ) {
		$webradios = array();
		$radio_id = $myts->makeTboxData4Show($myrow['radio_id']);
		$radio_name = $myts->makeTboxData4Show($myrow['radio_name']);

		$webradios['radio_id'] = $radio_id;
		$webradios['radio_name'] = $radio_name;


		$block['debaser_radios'][] = $webradios;
	}
	return $block;
}

?>