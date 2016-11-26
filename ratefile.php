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

	/* updates rating data in itemtable for a given item */
	function updatedebaserrating($sel_id) {

		global $xoopsDB, $xoopsModuleConfig;

		$totalrating = 0;
		$votesDB = 0;
		$finalrating = 0;

		$query = "SELECT rating FROM ".$xoopsDB->prefix('debaservotedata')." WHERE lid = ".intval($sel_id)."";

		$voteresult = $xoopsDB->query($query);
		$votesDB = $xoopsDB->getRowsNum( $voteresult );

		while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
			$totalrating += $rating;
		}

		if (($totalrating) != 0 && $votesDB != 0) {
			$finalrating = $totalrating / $votesDB;
			$finalrating = number_format($finalrating, 4);
		}

		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_files')." SET rating = '$finalrating', votes = '$votesDB' WHERE xfid  = ".intval($sel_id)."");
	}

	if ($current_userid == 'guest' && $xoopsModuleConfig['guestvote'] == 0) redirect_header('index.php', 2, _NOPERM);


	if (!empty($_GET['rating']) || !empty($_GET['lid'])) {
		$ratinguser = (is_object($xoopsUser)) ? $current_userid : 0;
		$rating = ($_GET['rating']) ? $_GET['rating'] : 0;

		// Make sure only 1 anonymous from an IP in a single day.
		$anonwaitdays = 1;
		$ip = getenv( "REMOTE_ADDR" );
		$lid = intval($_GET['lid']);

		// Check if Rating is Null
		if (!$rating) redirect_header("singlefile.php?id=$lid", 2, _MD_DEBASER_NORATING);

		// Check if ANONYMOUS user is trying to vote more than once per day.
		if ($ratinguser == 0) {
			$yesterday = (time() - (86400 * $anonwaitdays));
			$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('debaservotedata')." WHERE lid = $lid AND ratinguser = 0 AND ratinghostname = '$ip' AND ratingtimestamp > $yesterday");

			list($anonvotecount) = $xoopsDB->fetchRow($result);

			if ($anonvotecount >= 1) redirect_header("singlefile.php?id=$lid", 2, _MD_DEBASER_VOTEONCE);

		} else {
			// Check if REG user is trying to vote twice.
			$result = $xoopsDB->query("SELECT ratinguser FROM ".$xoopsDB->prefix('debaservotedata')." WHERE lid=$lid");
			list($ratinguserDB) = $xoopsDB->fetchRow($result);
		if ($ratinguserDB == $ratinguser) redirect_header("singlefile.php?id=$lid", 2, _MD_DEBASER_VOTEONCE);
		}

		// All is well.  Add to Line Item Rate to DB.
		$newid = $xoopsDB->genId($xoopsDB->prefix('debaservotedata'). "_ratingid_seq");
		$datetime = time();
		$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('debaservotedata')." (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $lid, $ratinguser, $rating, '$ip', $datetime)" );

		// All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
		updatedebaserrating($lid);
		$ratemessage = _MD_DEBASER_VOTEAPPRE . "<br />" . sprintf(_MD_DEBASER_THANKYOU, $xoopsConfig['sitename']);
		redirect_header("singlefile.php?id=$lid", 2, $ratemessage);
		exit();
	} else {
		redirect_header("singlefile.php?id=$lid", 2, _MD_DEBASER_UNKNOWNERROR);
		exit();
	}

?>