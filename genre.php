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

	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
	include_once DEBASER_CLASS.'/debasertree.php';

	$mytree = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$langa, 'AND textfileid = 0', true);
	$start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;
	$xoopsOption['template_main'] = 'debaser_genre.html';

	include XOOPS_ROOT_PATH.'/header.php';

	$jstohead = '$(document).ready(function() {';

	if ($xoopsModuleConfig['useffmpeg'] == 1) {
		$jstohead .= '$(".imgHoverable").hover( function() { var hoverImg = HoverImgOf($(this).attr("src")); $(this).attr("src", hoverImg); }, function() { var normalImg = NormalImgOf($(this).attr("src")); $(this).attr("src", normalImg); } ); function HoverImgOf(filename) { var re = new RegExp("(.+)\\.(gif)", "g"); return filename.replace(re, "$1_hover.$2"); } function NormalImgOf(filename) { var re = new RegExp("(.+)_hover\\.(gif)", "g"); return filename.replace(re, "$1.$2"); }';
	}

	if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups)) {
		$xoopsTpl->assign('playlistyes', true);
		$jstohead .= '$.get("debasertoken.php",function(txt){ $(".jsecure").append("<input type=\"hidden\" id=\"ts\" name=\"ts\" value=\""+txt+"\" />");});$("#playlistresponse").hide(); $("img.addtoplaylist").live("click", function () { var getHidden = $("#ts").val(); $("#playlistresponse").load("'.DEBASER_URL.'/ajaxed.php", { action : "ajaxupdate", id : this.id, ts : getHidden }, function() {$("#playlistresponse").show("slow")} ); $("#playlistresponse").hide(); });';
	} else {
		$xoopsTpl->assign('playlistyes', false);
	}

	if ($xoopsModuleConfig['innerdisplay'] == 1) {
		$xoopsTpl->assign('innerdisplay', true);
		$jstohead .= '
$("a.innerdisp").fancybox({ "hideOnContentClick": false, "zoomSpeedIn": 500, "zoomSpeedOut": 500, "overlayShow": true, "padding": 0, "easingIn": "easeOutBack", "easingOut": "easeInBack" }); ';
		$xoTheme->addScript(DEBASER_UJS.'/jquery.fancybox-1.2.6.pack.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$xoTheme->addScript(DEBASER_UJS.'/jquery.easing.1.3.pack.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$xoTheme->addStylesheet(DEBASER_UCSS.'/jquery.fancybox-1.2.6.css', array('type' => 'text/css', 'media' => 'screen', null));
	} else {
		$xoopsTpl->assign('innerdisplay', false);
	}

	$jstohead .= '});';
	$xoTheme->addScript(null, array('type' => 'text/javascript', 'charset' => _CHARSET), $jstohead);

	!empty($_GET['genreid']) ? $genrelist = intval($_GET['genreid']) : $genrelist = intval($_GET['textcatid']);

	if (!$gperm_handler->checkRight('DebaserCatPerm', $genrelist, $groups, $module_id)) redirect_header('index.php', 2, _NOPERM);

$pathstring = "<a href='index.php'>"._MD_DEBASER_MAIN."</a>&nbsp;|&nbsp;"._MD_DEBASER_YOUAREHERE.":&nbsp;";
$pathstring .= $mytree->getDebaserNicePathFromId($genrelist, "textcattitle", "genre.php?genreid=");
$xoopsTpl->assign('category_path', $pathstring);
// get child category objects
$arr = array();
$arr = $mytree->getDebaserFirstChild($genrelist, 'textcattitle');
if ( count($arr) > 0 ) {
    foreach($arr as $ele){
		$sub_arr = array();
		$sub_arr = $mytree->getDebaserFirstChild($ele['textcatid'], 'textcattitle');
		$space = 0;
		$infercategories = '';
		foreach($sub_arr as $sub_ele){
			$chtitle = $myts->addSlashes($sub_ele['textcattitle']);
			if ($space > 0) {
				$infercategories .= ", ";
			}
			$infercategories .= "<a href=\"".DEBASER_URL."/genre.php?genreid=".$sub_ele['textcatid']."\">".$chtitle."</a>";
			$space++;
		}
   		$xoopsTpl->append('subcategories', array('title' => $myts->addSlashes($ele['textcattitle']), 'id' => $ele['textcatid'], 'infercategories' => $infercategories));
    }
}

	$sql = "SELECT xfid, filename, artist, title, album, year, track, bitrate, frequence, hits, views, uid, fileext, linktype, linkcode, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE genreid= ".intval($genrelist)." ORDER BY ".$xoopsModuleConfig['index_sortby']." ".$xoopsModuleConfig['index_orderby']." ";

	$result2 = $xoopsDB->query($sql);
	$result = $xoopsDB->query( $sql, $xoopsModuleConfig['indexperpagefront'], $start );

	$totalarts = $xoopsDB->getRowsNum($result2);

	$mimeresult = $xoopsDB->query("SELECT xpid, name, html_code, playericon, canplay, equalizer FROM ".$xoopsDB->prefix('debaser_player')." WHERE xsubid = 0 AND isactive = 1 ORDER BY xpid ASC");

	$fileextstack = array();
	$xpidstack = array();
	$namestack = array();
	$iconstack = array();
	$externalstack = array();
	$equalstack = array();

	while ($sqlfetch = $xoopsDB->fetchArray($mimeresult)) {
		if ($sqlfetch['html_code'] == 'external') array_push($externalstack, '1');
		else array_push($externalstack, '0');

		array_push($fileextstack, $sqlfetch['canplay']);
		array_push($xpidstack, $sqlfetch['xpid']);
		array_push($namestack, $sqlfetch['name']);
		array_push($iconstack, $sqlfetch['playericon']);
		array_push($equalstack, $sqlfetch['equalizer']);
	}

	if ($xoopsModuleConfig['uselame'] == 1) $getlofi = checkLofi();
	else $getlofi = 0;

	while ($sqlfetch = $xoopsDB->fetchArray($result)) {
		$imagebar = '';
		$filelist = array('id' => $sqlfetch['xfid'], 'filename' => $sqlfetch['filename'], 'artist' => $sqlfetch['artist'], 'title' => $sqlfetch['title'], 'album' => $sqlfetch['album'], 'year' => $sqlfetch['year'], 'track' => $sqlfetch['track'], 'bitrate' => $sqlfetch['bitrate'], 'frequence' => $sqlfetch['frequence'], 'hits' => $sqlfetch['hits'], 'views' => $sqlfetch['views'], 'uid' => $sqlfetch['uid'], 'fileext' => $sqlfetch['fileext'], 'haslofi' => $sqlfetch['haslofi']);

		if ($sqlfetch['linktype'] != '' || $sqlfetch['linkcode'] != '') $filelist['isplatorlink'] = true;
		else $filelist['isplatorlink'] = false;

		$needle = $sqlfetch['fileext'];

		if ($filelist['haslofi'] == 1 && $getlofi == 1) $lofi = 'lofi_';
		else $lofi = '';

		if ($results = array_search_ext($fileextstack, $needle, false)) {
  			foreach($results as $res) {
				$thekey = implode("', '", $res['keys']);
				if ($externalstack[$thekey] == 0) {
					if ($xoopsModuleConfig['innerdisplay'] == 1) {
						$path = getthedir($filelist['uid']);

						if ($path == '') $path = "''";

						if ($equalstack[$thekey] == 0) $equal = '';
						else $equal = 1;

						$imagebar .= '<a class="innerdisp" title="'.$filelist['artist'].' '.$filelist['title'].'" href="'.DEBASER_URL.'/ajaxed.php?action=ajaxinner&amp;id='.$filelist['id'].'&amp;player='.$xpidstack[$thekey].'&amp;path='.$path.'&amp;equal='.$equal.'"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
					} else {
						$imagebar .= '<img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/player.php?id='.$filelist['id'].'&amp;player='.$xpidstack[$thekey].'\',\'player\',10,10)" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /> ';
					}
				} else {
					$path = getthedir($filelist['uid']);
					if ($xoopsModuleConfig['innerdisplay'] == 0) {
						$imagebar .= '<a href="'.DEBASER_UUP.'/'.$path.$lofi.$filelist['filename'].'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
					} else {
						$imagebar .= '<a href="'.DEBASER_UUP.'/'.$path.$lofi.$filelist['filename'].'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
					}
				}
  			}
		}

	if ($xoopsModuleConfig['useffmpeg'] == 1) {

	$remfileext = strlen($filelist['fileext'])+1;

	$imagename = substr($filelist['filename'], 0, -$remfileext);
	$secpath = getthedir($filelist['uid']);

	if (is_file(DEBASER_RUP.'/'.$secpath.$imagename.'.gif')) {

		$filelist['thumbnail'] = '<img src="'.DEBASER_UUP.'/'.$secpath.$imagename.'.gif" class="imgHoverable" alt="" />';
		$filelist['hasthumb'] = true;
	} else {
		$filelist['hasthumb'] = false;
	}
	} else {
		$filelist['hasthumb'] = false;
	}
			$filelist['imagebar'] = $imagebar;
			unset($imagebar);
			$xoopsTpl->append('filelist', $filelist);
		}

	$pagenav = new XoopsPageNav( $totalarts, $xoopsModuleConfig['indexperpagefront'], $start, 'start', 'genreid=' . $genrelist );
	$navbar = $pagenav -> renderNav(2);

	$xoopsTpl->assign('navbar', $navbar);

	if ($totalarts < 1) redirect_header('index.php', 2, _MD_DEBASER_NOFILES);

	$xoopsTpl->assign('verifyuser', $current_userid);
	$xoopsTpl->assign('isxadmin', $is_deb_admin);

	if ($current_userid == 'guest') {
		if ($xoopsModuleConfig['guestdownload'] == 1) $xoopsTpl->assign('allowyes', true);
	} else {
		if (@array_intersect($xoopsModuleConfig['allowdownload'], $groups)) $xoopsTpl->assign('allowyes', true);
	}

	$mimearray = array();
	$mimeinlist = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mimeinlist = 1");
	while ($mimefetch = $xoopsDB->fetchArray($mimeinlist)) {
		array_push($mimearray, $mimefetch['mime_ext']);
	}
	$xoopsTpl->assign('mimearray', $mimearray);

	$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

	include_once XOOPS_ROOT_PATH.'/footer.php';

?>