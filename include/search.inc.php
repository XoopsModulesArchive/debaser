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

function debaser_search($queryarray, $andor, $limit, $offset){
	global $xoopsDB, $gperm_handler, $groups, $module_id, $xoopsUser;

	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('debaser');
	$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$module_id = $module->getVar('mid');
	$gperm_handler = &xoops_gethandler('groupperm');
	$sql = "SELECT xfid, added, title, artist, album, genreid, uid FROM ".$xoopsDB->prefix('debaser_files')." WHERE added > 0 ";
	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((artist LIKE '%$queryarray[0]%' OR title LIKE '%$queryarray[0]%' OR album LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(artist LIKE '%$queryarray[$i]%' OR title LIKE '%$queryarray[$i]%' OR album LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY added DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
	while($myrow = $xoopsDB->fetchArray($result)){
		if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'], $groups, $module_id))
		$ret[$i]['image'] = 'images/music.png';
		$ret[$i]['link'] = "singlefile.php?id=".$myrow['xfid'];
		$ret[$i]['title'] = $myrow['artist']." - ".$myrow['title'];
		$ret[$i]['time'] = $myrow['added'];
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}

?>