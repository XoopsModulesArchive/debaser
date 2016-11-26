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
	include_once DEBASER_CLASS.'/debasertree.php';

	$xoopsOption['template_main'] = 'debaser_singlefile.html';

	include XOOPS_ROOT_PATH.'/header.php';

	$jstohead = '$(document).ready(function() {';

	if ($xoopsModuleConfig['useffmpeg'] == 1) {
		$jstohead .= '
			$(".imgHoverable").hover( function() {
				var hoverImg = HoverImgOf($(this).attr("src"));
				$(this).attr("src", hoverImg);
			}, function() {
				var normalImg = NormalImgOf($(this).attr("src"));
				$(this).attr("src", normalImg);
			}
			);
			function HoverImgOf(filename) {
				var re = new RegExp("(.+)\\.(gif)", "g");
				return filename.replace(re, "$1_hover.$2");
			}
			function NormalImgOf(filename) {
				var re = new RegExp("(.+)_hover\\.(gif)", "g");
				return filename.replace(re, "$1.$2");
			}';
	}

	if (is_object($xoopsUser)) {
		$xoTheme->addScript(DEBASER_UJS.'/jquery.ezpz_tooltip.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$xoTheme->addScript(DEBASER_UJS.'/jquery.simpleCaptcha-0.1.min.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$jstohead .= '
			$("#stay-target-1").ezpz_tooltip({ contentPosition: "belowStatic", stayOnContent: true, offset: 0 });
			$("#tafsubmit").live("click", function(event) {
				var formContent = $("#tafsendto").val();
				var simpleCaptcha = $("#simpleCaptcha_0").val();
				$("#tafreaction").load("'.DEBASER_URL.'/ajaxed.php", { action: "tellafriend", sendtomail: formContent, captchaSelection : simpleCaptcha, fileid : "'.intval($_GET['id']).'" });
				setTimeout(function() { $("#stay-content-1").fadeOut(3000); }, 3000);
				setTimeout(function() { $("#tafsendto").val(""); $("#tafreaction").empty(); }, 3100); }); 			$("#captcha").simpleCaptcha({ numImages: 7, introText: "<p>'._MD_DEBASER_HUMAN.' <strong class=\"captchaText\"><\/strong><\/p>"});';
	}

	if ($xoopsModuleConfig['simlimit'] != -1) {
		$xoTheme->addScript(DEBASER_UJS.'/jquery.ezpz_tooltip.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$jstohead .= '
			$("#stay-target-2").ezpz_tooltip({ contentPosition: "belowStatic", stayOnContent: true, offset: 0 });
			$("#similarid").load("'.DEBASER_URL.'/ajaxed.php", { action: "similarities", thisfileid: "'.intval($_GET['id']).'" });';
		$xoopsTpl->assign('similarity', 0);
	} else {
		$xoopsTpl->assign('similarity', -1);
	}

	if (@array_intersect($xoopsModuleConfig['allowplaylist'], $groups)) {
		$xoopsTpl->assign('playlistyes', true);
		$jstohead .= '
			$.get("debasertoken.php",function(txt){ $(".jsecure").append("<input type=\"hidden\" id=\"ts\" name=\"ts\" value=\""+txt+"\" />"); });
			$("#playlistresponse").hide();
			$("#addtoplaylist").live("click", function () {
				var getHidden = $("#ts").val();
				$("#playlistresponse").load("'.DEBASER_URL.'/ajaxed.php", { action: "ajaxupdate", id: "a"+'.intval($_GET['id']).', ts : getHidden }, function() {$("#playlistresponse").show("slow")} ); $("#playlistresponse").hide(); });';
	} else {
		$xoopsTpl->assign('playlistyes', false);
	}

	if ($xoopsModuleConfig['innerdisplay'] == 1) {
		$jstohead .= '
			$("a.innerdisp").fancybox({ "hideOnContentClick": false, "zoomSpeedIn": 500, "zoomSpeedOut": 500, "overlayShow": true, "padding": 0, "easingIn": "easeOutBack", "easingOut": "easeInBack" });';
		$xoTheme->addScript(DEBASER_UJS.'/jquery.fancybox-1.2.6.pack.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$xoTheme->addScript(DEBASER_UJS.'/jquery.easing.1.3.pack.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$xoTheme->addStylesheet(DEBASER_UCSS.'/jquery.fancybox-1.2.6.css', array('type' => 'text/css', 'media' => 'screen', null));
		$xoopsTpl->assign('innerdisplay', true);
	} else {
		$xoopsTpl->assign('innerdisplay', false);
	}

	if ($xoopsModuleConfig['allowembed'] == 1) {
		$xoopsTpl->assign('allowembed', 1);
		$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);
		$jstohead .= '
			$("#codedisplayer").change(function () { $("#showthecode").load("'.DEBASER_URL.'/ajaxed.php", { action: "ajaxembed", displayer: this.value, id: '.intval($_GET['id']).' }); });';
	} else {
		$xoopsTpl->assign('allowembed', 0);
	}

	$jstohead .= '});';
	$xoTheme->addScript(null, array('type' => 'text/javascript', 'charset' => _CHARSET), $jstohead);

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

	$imagebar = '';

	$result = $xoopsDB->query("SELECT xfid, filename, artist, title, album, year, track, genreid, length, bitrate, frequence, rating, votes, approved, fileext, hits, views, uid, linktype, linkcode, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($_GET['id'])."");
	list($id, $filename, $artist, $title, $album, $year, $track, $genreid, $length, $bitrate, $frequence, $rating, $votes, $approved, $fileext, $hits, $views, $uid, $linktype, $linkcode, $haslofi) = $xoopsDB->fetchRow($result);

	if ($xoopsModuleConfig['uselame'] == 1) $getlofi = checkLofi();
	else $getlofi = 0;

	if ($haslofi == 1 && $getlofi == 1) $lofi = 'lofi_';
	else $lofi = '';

	if ($results = array_search_ext($fileextstack, $fileext, false)) {
  		foreach($results as $res) {
			$thekey = implode("', '", $res['keys']);
			if ($externalstack[$thekey] == 0) {
				if ($xoopsModuleConfig['innerdisplay'] == 1) {
					$path = getthedir($uid);

					if ($equalstack[$thekey] == 0) $equal = '';
					else $equal = 1;

					$imagebar .= '<a class="innerdisp" title="'.$artist.' '.$title.'" href="'.DEBASER_URL.'/ajaxed.php?action=ajaxinner&amp;id='.$id.'&amp;player='.$xpidstack[$thekey].'&amp;path='.$path.'&amp;equal='.$equal.'"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
				} else {
					$imagebar .= '<img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/player.php?id='.$id.'&amp;player='.$xpidstack[$thekey].'\',\'player\',10,10)" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /> ';
				}
			} else {
				$path = getthedir($uid);

				if ($xoopsModuleConfig['innerdisplay'] == 0) {
					$imagebar .= '<a href="'.DEBASER_UUP.'/'.$path.$lofi.$filename.'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
				} else {
					$imagebar .= '<a href="'.DEBASER_UUP.'/'.$path.$lofi.$filename.'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
				}
			}
  		}
	}

	if ($xoopsModuleConfig['allowembed'] == 1) {
		$selresult = $xoopsDB->query("SELECT xpid, name, canplay FROM ".$xoopsDB->prefix('debaser_player')." WHERE embedding = 1 AND canplay LIKE '%$fileext%'");
		$seloptions = '<option value="0">'._MD_DEBASER_SELECTCODE.'</option>';
		$i = 1;

		while($fetch = $xoopsDB->fetchArray($selresult)) {
			$seloptions .= '<option value="'.$fetch['xpid'].'">'.$fetch['name'].'</option>';
			$i++;
		}

		if ($i == 1) {
			$xoopsTpl->assign('selcount', 0);
		} else {
			$xoopsTpl->assign('seloptions', $seloptions);
			$xoopsTpl->assign('selcount', 1);
		}
	}

	$result2 = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_GET['id'])." AND language = $langa");
	list($addinfo) = $xoopsDB->fetchRow($result2);
	$xoopsTpl->assign(array('id' => $id, 'artist' => $artist, 'title' => $title, 'album' => $album, 'year' => $year, 'track' => $track, 'length' => $length, 'bitrate' => $bitrate, 'frequence' => $frequence, 'addinfo' => $myts->displayTarea($addinfo, $html, 1, 1, 1, $dobr), 'hits' => $hits, 'views' => $views, 'imagebar' => $imagebar, 'fileext' => $fileext, 'uid' => $uid, 'filename' => $filename));

	if ($xoopsModuleConfig['sameauthor'] != -1) {
		$member_handler =& xoops_gethandler('member');
		$user =& $member_handler->getUser($uid);
		$uname = $myts->htmlSpecialChars($user->getVar('uname'));

		$xoopsTpl->assign('thisuser', '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$uid.'">'.$uname.'</a>');

		if ($xoopsModuleConfig['sameauthor'] == 0) $limit = '';
		else $limit = 'LIMIT '.$xoopsModuleConfig['sameauthor'];

		$samesame = array();
		$samesql = "SELECT xfid, title, artist, genreid, approved FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 AND uid = ".intval($uid)." AND NOT xfid = ".intval($id)." ORDER BY title ASC $limit";
		$sameresult = $xoopsDB->query($samesql);
		$hasitems = $xoopsDB->getRowsNum($sameresult);

		if ($hasitems > 0) {
			while ($sqlfetch = $xoopsDB->fetchArray($sameresult)) {
				if ($gperm_handler->checkRight('DebaserCatPerm', $sqlfetch['genreid'], $groups, $module_id)) {
					$samesame['id'] = $sqlfetch['xfid'];
					$samesame['title'] = $sqlfetch['title'];
					$samesame['artist'] = $sqlfetch['artist'];
					$xoopsTpl->append('samesame', $samesame);
				}
			}
			$xoopsTpl->assign('noothersong', false);
		} else {
			$xoopsTpl->assign('noothersong', true);
		}
	} else {
		$xoopsTpl->assign('noothersong', true);
	}

	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_files')." SET views = views+1 WHERE xfid = ".intval($_GET['id'])."");

	if ($rating != 0.0000) {
		$ratenum = $myts->stripSlashesGPC(number_format( $rating, 2));
		$rateminus = (10-$ratenum)*10;
		$xoopsTpl->assign('ratenum', $ratenum);
		$xoopsTpl->assign('rateminus', $rateminus);
		$xoopsTpl->assign('votes', $votes);
	} else {
		$xoopsTpl->assign('votes', 0);
	}

	$xoopsTpl->assign('verifyuser', $current_userid);
	$xoopsTpl->assign('isxadmin', $is_deb_admin);

	if ($linktype != '' || $linkcode != '') $xoopsTpl->assign('isplatorlink', true);
	else $xoopsTpl->assign('isplatorlink', false);

	if (@array_intersect($xoopsModuleConfig['allowdownload'], $groups)) $xoopsTpl->assign('allowyes', true);

	$mytree = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$langa, 'AND textfileid = 0', true);
	$pathway = $mytree->getDebaserNicePathFromId($genreid, 'textcattitle', 'genre.php?genreid='.$genreid.'');
	$xoopsTpl->assign('pathway', $pathway);

	if ($xoopsModuleConfig['guestvote'] == 1) $xoopsTpl->assign('guestvote', true);
	if (is_object($xoopsUser)) $xoopsTpl->assign('guestvote', true);

	include XOOPS_ROOT_PATH.'/include/comment_view.php';

	if ($xoopsModuleConfig['useffmpeg'] == 1) {
	$remfileext = strlen($fileext)+1;
	$imagename = substr($filename, 0, -$remfileext);
	if (is_file(DEBASER_RUP.'/'.$path.$imagename.'.gif')) {
		$xoopsTpl->assign('thumbnail', '<img src="'.DEBASER_UUP.'/'.$path.$imagename.'.gif" class="imgHoverable" alt="" />');
		$xoopsTpl->assign('hasthumb', true);
	} else {
		$xoopsTpl->assign('hasthumb', false);
	}
	} else {
		$xoopsTpl->assign('hasthumb', false);
	}

	$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

	include_once XOOPS_ROOT_PATH.'/footer.php';

?>