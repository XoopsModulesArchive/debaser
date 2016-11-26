<?php
// $Id: getfile.php,v 0.80 2004/10/23 10:00:00 frankblack Exp $
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

	$myts =& MyTextSanitizer::getInstance();

	$fileid = ($_GET['id']) ? $_GET['id'] : 1;

	$sql = "
	SELECT filename, title, artist, fileext
	FROM ".$xoopsDB->prefix('debaser_files')."
	WHERE xfid=".intval($fileid)."";

	$newfilename = '';
	$result = $xoopsDB->query($sql);

		if ($result) {
		list($downfile, $downtitle, $downartist, $fileext) = $xoopsDB->fetchRow($result);
		$newfilename = $myts -> undoHtmlSpecialChars($downartist).' - '.$myts -> undoHtmlSpecialChars($downtitle).'.'.$fileext;
		$newfilename = str_replace(' ', '', $newfilename);
		$filename = 'upload/'.$downfile;
		}
		else {
		redirect_header('index.php',2,_MD_DEBASER_FILENOTFOUND);
		}

		/* This peace of code is stolen from wfsection ;-) */

		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
		header("Pragma: public");
		header("Content-Type: audio/mpeg; name=\"".basename($filename)."\"");
		header("Content-Length: ".filesize($filename)."\n");
		header("Expires: 0");
		header("Cache-control: private");
		header("Content-Disposition: attachment; filename=\"".basename($newfilename)."\"");
		}
		else {
		header("Content-Type: audio/mpeg; name=\"".basename($filename)."\"");
		header("Content-Length: ".filesize($filename)."\n");
		header("Content-Disposition: attachment; filename=\"".basename($newfilename)."\"");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		}

	@readfile($filename, 'r');

	$filesql = "UPDATE ".$xoopsDB->prefix('debaser_files')." SET hits = hits+1 WHERE xfid = ".intval($fileid)."";
	$fileresult = $xoopsDB->queryF($filesql);

	exit();

?>