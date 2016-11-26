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

	function b_debaser_rated_show($options) {

	global $xoopsDB, $xoopsUser;
	
	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('debaser');
	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$module_id = $module->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');

	$myts =& MyTextSanitizer::getInstance();
	$block = array();
	$sql = "SELECT xfid, title, artist, album, year, addinfo, track, genre, length, bitrate, frequence FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 AND rating > 0 ORDER BY rating DESC LIMIT ".$options[0]."";
	$result = $xoopsDB->query($sql);

		while ($myrow = $xoopsDB->fetchArray($result)) {
		if ($moduleConfig['usefileperm'] == 1) {
		if ($gperm_handler->checkRight('DebaserFilePerm', $myrow['xfid'] , $groups, $module_id)) {		
		$ratefiles = array();
		$ratefiles['id'] = $myts->makeTboxData4Show($myrow['xfid']);
		$ratefiles['title'] = $myts->makeTboxData4Show($myrow['title']);
		$ratefiles['artist'] = $myts->makeTboxData4Show($myrow['artist']);
		if ($moduleConfig['usetooltips'] == 1) {
		$ratefiles['album'] = $myts->makeTboxData4Show($myrow['album']);
		$ratefiles['year'] = $myts->makeTboxData4Show($myrow['year']);
		$ratefiles['addinfo'] = $myts->makeTboxData4Show($myrow['addinfo']);
		$ratefiles['track'] = $myts->makeTboxData4Show($myrow['track']);
		$ratefiles['genre'] = $myts->makeTboxData4Show($myrow['genre']);
		$ratefiles['length'] = $myts->makeTboxData4Show($myrow['length']);
		$ratefiles['bitrate'] = $myts->makeTboxData4Show($myrow['bitrate']);
		$ratefiles['frequence'] = $myts->makeTboxData4Show($myrow['frequence']);
		$ratefiles['usetooltips'] = true;
		}
}
}
else {
		$ratefiles = array();
		$ratefiles['id'] = $myts->makeTboxData4Show($myrow['xfid']);
		$ratefiles['title'] = $myts->makeTboxData4Show($myrow['title']);
		$ratefiles['artist'] = $myts->makeTboxData4Show($myrow['artist']);
		if ($moduleConfig['usetooltips'] == 1) {
		$ratefiles['album'] = $myts->makeTboxData4Show($myrow['album']);
		$ratefiles['year'] = $myts->makeTboxData4Show($myrow['year']);
		$ratefiles['addinfo'] = $myts->makeTboxData4Show($myrow['addinfo']);
		$ratefiles['track'] = $myts->makeTboxData4Show($myrow['track']);
		$ratefiles['genre'] = $myts->makeTboxData4Show($myrow['genre']);
		$ratefiles['length'] = $myts->makeTboxData4Show($myrow['length']);
		$ratefiles['bitrate'] = $myts->makeTboxData4Show($myrow['bitrate']);
		$ratefiles['frequence'] = $myts->makeTboxData4Show($myrow['frequence']);
		$ratefiles['usetooltips'] = true;
		}		
}
		$block['debaser_ratefiles'][] = $ratefiles;
	}
	return $block;
}

	function b_debaser_rated_edit($options) {

	$form = ""._MB_DEBASER_BLOCLATE."<input type='text' size='3' maxlength='2' name='options[]' value='".$options[0]."' />&nbsp;"._MB_DEBASER_SONGS."";

	return $form;
	}

?>