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

	function b_debaser_displatest_show($options) {

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

		if (isset($_POST['playme']) && $_POST['playme'] == 'playlatest') {

		$sql = "SELECT d.xfid, d.filename, d.link, d.approved, d.fileext, e.mime_ext, e.mime_preselect, f.xpid, f.html_code FROM ".$xoopsDB->prefix('debaser_files')." d, ".$xoopsDB->prefix('debaser_mimetypes')." e, ".$xoopsDB->prefix('debaser_player')." f WHERE d.approved = 1 AND d.fileext = e.mime_ext AND e.mime_preselect = f.xpid ORDER BY d.xfid DESC LIMIT 1";
	$result = $xoopsDB->query($sql);

			while ( $myrow = $xoopsDB->fetchArray($result) ) {
			$disp_latest = array();
			$disp_latest['id'] = $myts->makeTboxData4Show($myrow['xfid']);

				if ($myrow['link'] != '') {
				$player1 = str_replace('<@mp3file@>',$myrow['link'],$myrow['html_code']);
				}
				else {
				$player1 = str_replace('<@mp3file@>',XOOPS_URL.'/modules/debaser/upload/'.$myrow['filename'],$myrow['html_code']);
				}

			$player2 = str_replace('<@height@>',$options[0],$player1);
			$player3 = str_replace('<@width@>',$options[1],$player2);
			$player = str_replace('<@autostart@>','true',$player3);

			$disp_latest['player'] = $player;
			}

		$block['display_latest'][] = $disp_latest;
		}
		else {
		$emptyvar = '';
		$disp_latest['donotdeletethistag'] = $emptyvar;
		$block['display_latest'][] = $disp_latest;
		}
		return $block;

	}

	function b_debaser_displatest_edit($options) {

	$form = ""._MB_DEBASER_HEIGHT."<input type='text' size='3' maxlength='3' name='options[]' value='".$options[0]."' /><br />
	"._MB_DEBASER_WIDTH."<input type='text' size='3' maxlength='3' name='options[]' value='".$options[1]."' />";

	return $form;
	}

?>