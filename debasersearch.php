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

	if (isset($_GET['q']) && $_GET['q'] != '') {

		$getquery = addSlashes($_GET['q']);
		$getlimit = intval($_GET['limit']);

		$result = $xoopsDB->query("SELECT artist, title, genreid FROM ".$xoopsDB->prefix('debaser_files')." WHERE title LIKE '%$getquery%' OR artist LIKE '%$getquery%' OR album LIKE '%$getquery%' OR year LIKE '%$getquery%' OR length LIKE '%$getquery%' OR frequence LIKE '%$getquery%' OR bitrate LIKE '%$getquery%' LIMIT $getlimit");

		while(list($artist, $title, $genreid) = $xoopsDB->fetchRow($result)) {
			if ($gperm_handler->checkRight('DebaserCatPerm', $genreid, $groups, $module_id)) echo $artist." "."$title\n";
		}
	}

?>