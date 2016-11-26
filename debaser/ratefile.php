<?php
// $Id: ratefile.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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
	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';

	foreach ( $_POST as $k => $v ) {
	${$k} = $v;
	}

	foreach ( $_GET as $k => $v ) {
	${$k} = $v;
	}

	if (empty($_POST['submit'])) {
		$_POST['submit'] = '';
	}

	include XOOPS_ROOT_PATH . '/header.php';

	if ( $_POST['submit'] ) {
		$ratinguser = (is_object($xoopsUser)) ? $xoopsUser->uid() : 0;
		$rating = ($_POST['rating']) ? $_POST['rating'] : 0;

	// Make sure only 1 anonymous from an IP in a single day.
		$anonwaitdays = 1;
		$ip = getenv( "REMOTE_ADDR" );
		$lid = intval($_POST['lid']);

	// Check if Rating is Null
	if (!$rating) {
	redirect_header("singlefile.php?id=$lid", 1, _MD_DEBASER_NORATING);
	exit();
	}

	// Check if REG user is trying to vote twice.
		$result = $xoopsDB->query("SELECT ratinguser FROM ".$xoopsDB->prefix('debaservotedata')." WHERE lid=$lid");

	while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
	if ($ratinguserDB == $ratinguser) {
	redirect_header("singlefile.php?id=$lid", 1, _MD_DEBASER_VOTEONCE);
	exit();
		}
	}

	// Check if ANONYMOUS user is trying to vote more than once per day.
	if ($ratinguser == 0) {
		$yesterday = (time() - (86400 * $anonwaitdays));
		$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('debaservotedata')." WHERE lid = $lid AND ratinguser = 0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");

	list($anonvotecount) = $xoopsDB->fetchRow($result);

	if ($anonvotecount >= 1) {
	redirect_header("singlefile.php?id=$lid", 1, _MD_DEBASER_VOTEONCE);
	exit();
		}
	}

	// All is well.  Add to Line Item Rate to DB.
		$newid = $xoopsDB->genId($xoopsDB->prefix('debaservotedata'). "_ratingid_seq");
		$datetime = time();
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaservotedata')." (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $rating, '$ip', $datetime)" );

	// All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
	updatedebaserrating($lid);
		$ratemessage = _MD_DEBASER_VOTEAPPRE . "<br />" . sprintf(_MD_DEBASER_THANKYOU, $xoopsConfig['sitename']);
	redirect_header("singlefile.php?id=$lid", 1, $ratemessage);
	exit();
	}
	else {
	redirect_header("singlefile.php?id=$lid", 1, _MD_DEBASER_UNKNOWNERROR);
	exit();
	}

	include 'footer.php';

?>