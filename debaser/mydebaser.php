<?php
// $Id: mydebaser.php,v 0.60 2004/06/30 10:00:00 frankblack Exp $
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
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';

	$xoopsOption['template_main'] = 'debaser_mydebaser.html';

	include XOOPS_ROOT_PATH.'/header.php';

		//get the userid
		if (is_object($xoopsUser)) {
		$uid = $xoopsUser->getVar('uid');
		}
		else {
		$uid = 0;
		}
		//

	$playerlist = array();

	$sql2 = "
	SELECT xpid, name
	FROM ".$xoopsDB->prefix('debaser_player')." ORDER BY name";

	$result2 = $xoopsDB->query($sql2);

		while ($sqlfetch2 = $xoopsDB->fetchArray($result2)) {
		$playerlist['playerid'] = $sqlfetch2['xpid'];
		$playerlist['playername'] = $sqlfetch2['name'];
		$xoopsTpl->append('playerlist', $playerlist);
		}

	$extlist = array();

	$sql3 = "
	SELECT mime_id, mime_ext
	FROM ".$xoopsDB->prefix('debaser_mimetypes')." ";

	$result3 = $xoopsDB->query($sql3);

		while ($sqlfetch3 = $xoopsDB->fetchArray($result3)) {
		$extlist['mime_id'] = $sqlfetch3['mime_id'];
		$extlist['mime_ext'] = $sqlfetch3['mime_ext'];
		$xoopsTpl->append('extlist', $extlist);
		}

		
		
	function selplayer() {

	global $xoopsDB, $uid;

	$valuesfromselbox = '';
	$zp=$_POST['selectvalues'];

		for ($i=0; $i<count($zp); $i++) {
		$valuesfromselbox = $valuesfromselbox." ".$zp[$i];
		}

	$sql1 = "
	SELECT uid
	FROM ".$xoopsDB->prefix('debaser_user')."
	WHERE uid=".intval($uid)." ";

	$result1 = $xoopsDB->query($sql1);
	$sqlfetch1 = $xoopsDB->fetchRow($result1);

		if ($sqlfetch1 == '') {
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_user')." (uid, useplayer) VALUES (".intval($uid).", ".$xoopsDB->quoteString($valuesfromselbox).")");

		redirect_header('index.php',1,_MD_DEBASER_DBUPDATED);
		}
		else {
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_user')." SET useplayer = ".$xoopsDB->quoteString($valuesfromselbox)."  WHERE uid = ".intval($uid)." ");

		redirect_header('index.php',1,_MD_DEBASER_DBUPDATED);
		}
	}

	if (isset($_POST['op']) && $_POST['op'] == 'selplayer') {
	$op = 'selplayer';
	}

		if (!isset($op)) {
		$op = '';
		}

	switch ($op) {

		case 'selplayer':
		selplayer();
		break;
	}

	include_once XOOPS_ROOT_PATH.'/footer.php';

?>