<?php
// $Id: include/functions.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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

	/* updates rating data in itemtable for a given item */
	function updatedebaserrating($sel_id) {

	global $xoopsDB, $xoopsModuleConfig;

	$totalrating = 0;
	$votesDB = 0;
	$finalrating = 0;

	$query = "
	SELECT rating
	FROM ".$xoopsDB->prefix('debaservotedata')."
	WHERE lid = ".intval($sel_id)." ";

	$voteresult = $xoopsDB->query($query);
	$votesDB = $xoopsDB->getRowsNum( $voteresult );

		while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
		$totalrating += $rating;
		}

		if (($totalrating) != 0 && $votesDB != 0) {
		$finalrating = $totalrating / $votesDB;
		$finalrating = number_format($finalrating, 4);
		}

		$xoopsDB->queryF("
		UPDATE " . $xoopsDB -> prefix('debaser_files') . "
		SET rating = '$finalrating', votes = '$votesDB' WHERE xfid  = $sel_id" );
	}
	/* -- */

	/* file already there? */
	function mpegExist($artist, $title) {

	global $xoopsDB;
	$titleexist = "
	SELECT COUNT(*)
	FROM ".$xoopsDB->prefix('debaser_files')."
	WHERE artist = '".$artist."'
	AND title = '".$title."'";

	$resultexist = $xoopsDB->query($titleexist);
	list($count) = $xoopsDB->fetchRow($resultexist);

		if ($count > 0) {
		redirect_header('index.php',2,_MD_DEBASER_ALREADYEXIST);
		die;
		}
	}
	/* -- */

	/* genre already there? */
	function genreExist($genre) {

	global $xoopsDB, $genre;
	$titleexist = "
	SELECT COUNT(*)
	FROM ".$xoopsDB->prefix('debaser_genre')."
	WHERE genretitle = '".$genre."'";

	$resultexist = $xoopsDB->query($titleexist);
	list($count) = $xoopsDB->fetchRow($resultexist);

		if ($count == 0) {
		$sql = "
		INSERT INTO ".$xoopsDB->prefix('debaser_genre')." (genretitle)
		VALUES ('".$genre."')";

		$result = $xoopsDB->queryF($sql);
		}
	}
	/* -- */

	/* if no genre title is available default genre will be set */
	function noGenreTitle() {
	global $xoopsDB, $genretitle;

	$resultx = $xoopsDB->query("
	SELECT genretitle
	FROM ".$xoopsDB->prefix('debaser_genre')."
	WHERE genreid=1 ");

	list($genretitle) = $xoopsDB->fetchRow($resultx);
	$genretitle = $genretitle;
	return $genretitle;
	}
	/* -- */

	function debaseradminMenu ($currentoption = 0, $breadcrumb = '') {
	/* Nice button styles */
	?>
	<style type="text/css">
	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
	#buttonbar { float:left; width:100%; background: #e7e7e7 url("../images/bg.gif") repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
	#buttonbar li { display:inline; margin:0; padding:0; }
	#buttonbar a { float:left; background:url("../images/left_both.gif") no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
	#buttonbar a span { float:left; display:block; background:url("../images/right_both.gif") no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
	/* Commented Backslash Hack hides rule from IE5-Mac \*/
	#buttonbar a span {float:none;}
	/* End IE5-Mac hack */
	#buttonbar a:hover span { color:#333; }
	#buttonbar #current a { background-position:0 -150px; border-width:0; }
	#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
	#buttonbar a:hover { background-position:0% -150px; }
	#buttonbar a:hover span { background-position:100% -150px; }
	</style>
	<?php
	global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	$tblCol = Array();
	$tblCol[0]=$tblCol[1]=$tblCol[2]=$tblCol[3]=$tblCol[4]=$tblCol[5]=$tblCol[6]=$tblCol[7]=$tblCol[8]='';
	$tblCol[$currentoption] = 'current';

	echo "<div id='buttontop'>";
	echo "<table style='width: 100%; padding: 0;' cellspacing='0'><tr>";
	echo "<td style='width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'><a class='nobutton' href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule ->getVar('mid')."'>"._PREFERENCES."</a> | <a href='../index.php'>"._AM_DEBASER_GOMOD."</a> | <a href='../language/".$xoopsConfig['language']."/help.php'>"._AM_DEBASER_HELP."</a> | <a href='about.php'>"._AM_DEBASER_ABOUT."</a></td>";
	echo "<td style='width: 55%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>".$xoopsModule->name()._AM_DEBASER_MODADMIN."</b> ".$breadcrumb."</td>";
	echo "</tr></table>";
	echo "</div>";

	echo "<div id='buttonbar'>";
	echo "<ul>";
	echo "<li id='".$tblCol[0]."'><a href='index.php'><span>"._AM_DEBASER_SHOWFILES."</span></a></li>";
	echo "<li id='".$tblCol[1]."'><a href='radioindex.php'><span>"._AM_DEBASERRAD_ADMIN."</span></a></li>";
	echo "<li id='".$tblCol[2]."'><a href='category.php'><span>"._AM_DEBASER_EDITGENRES."</span></a></li>";
	echo "<li id='".$tblCol[3]."'><a href='index.php?op=playermanager'><span>"._AM_DEBASER_EDITPLAYERS."</span></a></li>";
	echo "<li id='".$tblCol[4]."'><a href='upload.php?op=singleupload'><span>"._AM_DEBASER_SINGLEUP."</span></a></li>";
	echo "<li id='".$tblCol[5]."'><a href='upload.php?op=batchadd'><span>"._AM_DEBASER_BATCH."</span></a></li>";
	echo "<li id='".$tblCol[6]."'><a href='index.php?op=approve'><span>"._AM_DEBASER_APPROVE."</span></a></li>";
	echo "<li id='".$tblCol[7]."'><a href='mimetypes.php'><span>"._AM_DEBASER_FILETYPES."</span></a></li>";
	if (($xoopsModuleConfig['usecatperm'] == 1) || ($xoopsModuleConfig['usefileperm'] == 1)) {
	echo "<li id='".$tblCol[8]."'><a href='permissions.php'><span>"._AM_DEBASER_PERMISSIONS."</span></a></li>";
	}
	echo "</ul></div><br />";
}

	/* returns the allowed mimetypes reflecting permissions for user uploads */
	function retdebasermime($filename, $usertype = 1) {
	global $xoopsDB;

	$ext = ltrim(strrchr($filename, '.'), '.');
	$sql = "
	SELECT mime_types, mime_ext
	FROM ".$xoopsDB->prefix('debaser_mimetypes')."
	WHERE mime_ext = '" . strtolower($ext) . "'";

		if ($usertype == 1) {
		$sql .= " AND mime_admin = 1";
		}
		else {
		$sql .= " AND mime_user = 1";
		}

	$result = $xoopsDB->query($sql);
	list($mime_types , $mime_ext) = $xoopsDB->fetchrow($result);
	$mimtypes = explode(' ', trim($mime_types));
	return $mimtypes;
	}
	/* -- */

	/* this is a comparison between the mimetype of the file and mimetypes allowed, the returned value is the file extension */
	function debasermimecompare() {
	global $xoopsDB;
	$mimesql = "
	SELECT mime_ext
	FROM ".$xoopsDB->prefix('debaser_mimetypes')."
	WHERE mime_types LIKE '%".$_FILES['mp3file']['type']."%'";

	$mimeresult = $xoopsDB->query($mimesql);
	list($mimetyp) = $xoopsDB->fetchRow($mimeresult);
	return $mimetyp;
	}
	/* -- */

/**
 * down_debasercreatethumb()
 *
 * @param $img_name
 * @param $img_path
 * @param $img_savepath
 * @param integer $img_w
 * @param integer $img_h
 * @param integer $quality
 * @param integer $update
 * @param integer $aspect
 * @return
 **/
	function down_debasercreatethumb($img_name, $img_path, $img_savepath, $img_w = 100, $img_h = 100, $quality = 100, $update = 0, $aspect = 1) {
	global $xoopsModuleConfig, $xoopsConfig;

	// paths
		if ($xoopsModuleConfig['usethumbs'] == 0) {
		$image_path = XOOPS_URL . "/{$img_path}/{$img_name}";
		return $image_path;
		}

	$image_path = XOOPS_ROOT_PATH . "/{$img_path}/{$img_name}";
	$savefile = $img_path . "/" . $img_savepath . "/" . $img_w . "x" . $img_h . "_" . $img_name;
	$savepath = XOOPS_ROOT_PATH . "/" . $savefile;

		// Return the image if no update and image exists
		if ($update == 0 && file_exists($savepath)) {
		return XOOPS_URL . "/" . $savefile;
		}

	list($width, $height, $type, $attr) = getimagesize($image_path, $info);

	switch ($type) {

		case 1:
		# GIF image
			if (function_exists('imagecreatefromgif')) {
			$img = @imagecreatefromgif($image_path);
			}
			else {
			$img = @imageCreateFromPNG($image_path);
			}
			break;

		case 2:
		# JPEG image
		$img = @imageCreateFromJPEG($image_path);
		break;

		case 3:
		# PNG image
		$img = @imageCreateFromPNG($image_path);
		break;

		default:
		return $image_path;
		break;
	}

		if (!empty($img)) {
		/* Get image size and scale ratio */
		$scale = min($img_w / $width, $img_h / $height);
		/* If the image is larger than the max shrink it */
			if ($scale < 1 && $aspect == 1) {
			$img_w = floor($scale * $width);
			$img_h = floor($scale * $height);
			}
		/* Create a new temporary image */
			if (function_exists('imagecreatetruecolor')) {
			$tmp_img = imagecreatetruecolor($img_w, $img_h);
			}
			else {
			$tmp_img = imagecreate($img_w, $img_h);
			}
		/* Copy and resize old image into new image */
		ImageCopyResampled($tmp_img, $img, 0, 0, 0, 0, $img_w, $img_h, $width, $height);
		imagedestroy($img);
		flush();
		$img = $tmp_img;
		}

	switch ($type) {

		case 1:
		default:
		# GIF image
			if (function_exists('imagegif')) {
			imagegif($img, $savepath);
			}
			else {
			imagePNG($img, $savepath);
			}
		break;

		case 2:
		# JPEG image
		imageJPEG($img, $savepath, $quality);
		break;

		case 3:
		# PNG image
		imagePNG($img, $savepath);
		break;
	}
	imagedestroy($img);
	flush();
	return XOOPS_URL . "/" . $savefile;
	}

	/* function for saving permissions */
	function debaser_save_Perm($groups, $id, $perm_name) {
	$result = true;
	$hModule = & xoops_gethandler('module');
	$debModule = & $hModule -> getByDirname('debaser');
	$module_id = $debModule -> getVar('mid');
	$gperm_handler = & xoops_gethandler('groupperm');

	/* First, if the permissions are already there, delete them */
	$gperm_handler -> deleteByModule($module_id, $perm_name, $id);
	
		/* Save the new permissions */
		if (is_array($groups)) {
		
			foreach ($groups as $group_id) {
			$gperm_handler -> addRight($perm_name, $id, $group_id, $module_id);
			}
		}
	return $result;
	}
	/* -- */

	/* function for retrieving preselected players */
	function preselected_player($fileid) {
	global $xoopsDB;
	$preselectsql = "SELECT d.xfid, d.fileext, e.mime_ext, e.mime_preselect, f.xpid, f.html_code, f.height, f.width, f.autostart FROM ".$xoopsDB->prefix('debaser_files')." d, ".$xoopsDB->prefix('debaser_mimetypes')." e, ".$xoopsDB->prefix('debaser_player')." f WHERE d.xfid = ".intval($fileid)." AND d.fileext = e.mime_ext AND e.mime_preselect = f.xpid ";

	$preselectresult = $xoopsDB->query($preselectsql);

	list($xfid, $fileext, $mimeext, $mime_preselect, $fxpid, $player, $height, $width, $autostart) = $xoopsDB->fetchRow($preselectresult);
/* 		if ($autostart == 1) {
	$autostart = 'true';
	}
	else {
	$autostart = 'false';
	} */

	/* generate output */
	$player1 = str_replace('<@height@>',$height,$player);
	$player2 = str_replace('<@width@>',$width,$player1);
	$player3 = str_replace('<@autostart@>',$autostart,$player2);
	//$player4 = str_replace('<@mp3file@>',$mp3file,$player3);
	//$preselectplayer = $html_code;
	return $player3;
	}
	/* -- */

	/**
 * getTotalItems()
 * 
 * @param integer $sel_id
 * @param integer $get_child
 * @return the total number of items in items table that are accociated with a given table $table id
 **/
 	function getTotalDebaserItems($sel_id = 0) {

	global $xoopsDB, $mytree, $xoopsModule, $xoopsUser, $xoopsModuleConfig;

	$groups = (is_object($xoopsUser)) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
	$gperm_handler = & xoops_gethandler('groupperm');

    $count = 0;

    $arr = array();
    $query = "SELECT d.genreid, d.genretitle, t.xfid, t.genre, t.approved from ".$xoopsDB->prefix('debaser_genre')." d, ".$xoopsDB->prefix('debaser_files')." t WHERE d.genretitle = t.genre AND t.approved = 1 ";
    if ($sel_id)
    {
        $query .= " AND d.genreid=" . $sel_id . "";
    }  
    $result = $xoopsDB -> query($query);
    while (list($genreid, $genretitle, $xfid, $genre, $approved) = $xoopsDB -> fetchRow($result))
    {
				if ($xoopsModuleConfig['usecatperm'] == 1) {
        if ($gperm_handler -> checkRight('DebaserFilePerm', $xfid , $groups, $xoopsModule -> getVar('mid')))
        {
            $count++;
        } 
		}
		else {
		$count++;
		}
    } 

    $info['count'] = $count;
    return $info;
}

function maketree($rootcatid,$sql,$maxlevel){
global $xoopsDB;

         $result = $xoopsDB->query($sql);
                 while(list($catid,$parcat,$name)= $xoopsDB->fetchBoth($result)){
                 $table[$parcat][$catid]=$name;
                 $partable[$catid][$parcat]=$name;
                 }
         $result = makebranch($rootcatid,$table,0,$maxlevel);

         return $result;
}

function makeSeltree($rootcatid,$sql,$maxlevel){
global $xoopsDB;

         $result = $xoopsDB->query($sql);
                 while(list($catid,$parcat,$name)= $xoopsDB->fetchBoth($result)){
                 $table[$parcat][$catid]=$name;
                 $partable[$catid][$parcat]=$name;
                 }
         $result = makeSelbranch($rootcatid,$table,0,$maxlevel);

         return $result;
}

function makebranch($parcat,$table,$level,$maxlevel) {

         $list=$table[$parcat];

                while(list($key,$val) = each($list)){

                      if ($level=="0"){
                      $output = "";
                      }
					  else {
					  $output = str_repeat ("--", $level); 
                      }
			
                $result.= "$output <a href=genre.php?genreid=$key>$val</a> ($level)<br />\n";
                      if ((isset($table[$key])) AND (($maxlevel>$level+1) OR ($maxlevel=="0"))){
                      $result .= makebranch($key,$table,$level+1,$maxlevel);
                      }
                }
         return $result;
}

function makeSelbranch($parcat,$table,$level,$maxlevel) {

         $list=$table[$parcat];

                while(list($key,$val)=each($list)){

                      if ($level=="0"){
                      $output="";
                      }else{
					  $output = str_repeat ("--", $level); 
                      }
				//$result = $result;
                $result.= "<option value='$key'>$output $val</option>\n";
                      if ((isset($table[$key])) AND (($maxlevel>$level+1) OR ($maxlevel=="0"))){
                      $result .= makeSelbranch($key,$table,$level+1,$maxlevel);
                      }
                }
         return $result;
}

	function countDebaserFiles($chtitle) {
	global $xoopsDB;
	$zaehlersql = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('debaser_files')." WHERE genre = '".$chtitle."'";
	$zaehlerresult = $xoopsDB->query($zaehlersql);
	list($items) = $xoopsDB->fetchRow($zaehlerresult);
	return $items;
	}

?>