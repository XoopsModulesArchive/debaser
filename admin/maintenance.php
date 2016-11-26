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

	function listbroken() {

		xoops_cp_header();
		global $xoopsDB, $imagearray, $xoTheme;

		debaser_adminMenu();

		$xoTheme->addStylesheet(null, array('type' => 'text/css'), 'a.tooltip span { display:none; padding:2px 3px; margin-left:8px; width:180px; } a.tooltip:hover span { display:inline; position:absolute; background:#fff; border:1px solid #ccc; color:#6c6c6c; font-weight: normal; font-size: 80%; margin-top: -20px }');

		echo "<table class='outer' width='100%'><tr><td colspan='3' class='odd'><b>"._AM_DEBASER_REPDEF."</b></td></tr>";

		$result = $xoopsDB->query("SELECT brokenid, whichid, brokentitle, reason FROM ".$xoopsDB->prefix('debaser_broken')." ORDER BY whichid ASC");
		$hasresult = 0;

		while (list($brokenid, $whichid, $brokentitle, $reason) = $xoopsDB->fetchRow($result)) {

			echo "<tr><td class='even' align='center' width='40'><b>".$whichid."</b></td><td class='odd'><a class='tooltip' href='".DEBASER_URL."/singlefile.php?id=".$whichid."'>".$brokentitle."<span>".$reason."</span></a></td><td class='odd' align='center' width='40'><a href='maintenance.php?op=brokendelete&amp;fileid=".$whichid."'>".$imagearray['deleteimg']."</a></td></tr>";
			$hasresult = $hasresult+1;
		}
		echo "</table><br />";

		if (($hasresult < 1)) echo "<b>"._AM_DEBASER_NODEF."</b>";

	}

	function brokendelete() {

		global $xoopsDB;

		$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix('debaser_broken')." WHERE whichid = ".intval($_GET['fileid'])." ");
		redirect_header('maintenance.php', 2, _AM_DEBASER_DBUPDATE);
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch($op) {

		case "brokendelete":
		brokendelete();
		break;

		default:
		listbroken();
		break;

	}

	xoops_cp_footer();

?>