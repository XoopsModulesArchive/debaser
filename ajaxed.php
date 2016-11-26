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

	include_once 'header.php';
	include_once DEBASER_RINC.'/functions.php';

	function goawayajax() {
		redirect_header('index.php', 2, _NOPERM);
		exit();
	}

	function tellafriend() {

		global $xoopsMailer, $xoopsConfig, $myts, $xoopsUser;

		if ($_POST['captchaSelection'] != $_SESSION['simpleCaptchaAnswer']) {
			echo _MD_DEBASER_WRONGCAPTCHA;
			exit();
		}

		$xoopsMailer =& xoops_getMailer();
		$xoopsMailer->setTemplateDir(DEBASER_ROOT.'/language/'.$xoopsConfig['language'].'/mail_template/');
		$xoopsMailer->setTemplate('taf.tpl');
		$xoopsMailer->setFromName($myts->stripSlashesGPC($xoopsUser->uname()));
		$xoopsMailer->setFromEmail($myts->stripSlashesGPC($xoopsUser->email()));
		$xoopsMailer->setToEmails($myts->stripSlashesGPC($_POST['sendtomail']));
		$xoopsMailer->setSubject($myts->stripSlashesGPC(_MD_DEBASER_INTEREST));
		$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
		$xoopsMailer->assign('FILEID', DEBASER_URL.'/singlefile.php?id='.$_POST['fileid']);
		$xoopsMailer->assign('FROMUSER', $myts->stripSlashesGPC($xoopsUser->uname()));
		$xoopsMailer->useMail();

		if (!$xoopsMailer->send(true)) echo _MD_DEBASER_TAFNOTSENT;
		else echo _MD_DEBASER_TAFSENT;

	}

	function radioselecter() {

		global $xoopsDB;

		$result1 = $xoopsDB->query("SELECT radio_id, radio_stream, canplay FROM ".$xoopsDB->prefix('debaserradio')." WHERE radio_id = ".intval($_POST['radioselect'])."");
		list($radio_id, $radio_stream, $canplay) = $xoopsDB->fetchRow($result1);
		$canplayarray = explode(' ', $canplay);

		$result2 = $xoopsDB->query("SELECT xpid, name, html_code, playericon FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid IN (".implode(', ', array_map('intval', $canplayarray)).")");

		$imagerow = '';

		while($fetch = $xoopsDB->fetchArray($result2)) {

			if ($fetch['html_code'] == 'external') {
				$imagerow .= '<a href="'.$radio_stream.'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$fetch['playericon'].'" alt="'.$fetch['name'].'" title="'.$fetch['name'].'" />';
			} else {
				$imagerow .= '<img name="button'.$fetch['xpid'].'" id="button'.$fetch['xpid'].'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/radiopopup.php?radio=\'+document.radiolist.radioselect.options[document.radiolist.radioselect.selectedIndex].value+\'&amp;player='.$fetch['xpid'].'\',\'player\',10,10)" src="'.DEBASER_UIMG.'/playericons/'.$fetch['playericon'].'" width="20" height="20" alt="'.$fetch['name'].'" title="'.$fetch['name'].'" /> ';
			}
		}
		echo $imagerow;
	}

	function tvselecter() {

    	global $xoopsDB;

		$result1 = $xoopsDB->query("SELECT tv_id, tv_stream, canplay FROM ".$xoopsDB->prefix('debaser_tv')." WHERE tv_id = ".intval($_POST['tvselect'])."");
		list($tv_id, $tv_stream, $canplay) = $xoopsDB->fetchRow($result1);
		$canplayarray = explode(' ', $canplay);

		$result2 = $xoopsDB->query("SELECT xpid, name, html_code, playericon FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid IN (".implode(', ', array_map('intval', $canplayarray)).")");

		$imagerow = '';

		while($fetch = $xoopsDB->fetchArray($result2)) {
			if ($fetch['html_code'] == 'external') {
				$imagerow .= '<a href="'.$tv_stream.'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$fetch['playericon'].'" alt="'.$fetch['name'].'" title="'.$fetch['name'].'" />';
			} else {
				$imagerow .= '<img name="button'.$fetch['xpid'].'" id="button'.$fetch['xpid'].'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/tvpopup.php?tv=\'+document.tvlist.tvselect.options[document.tvlist.tvselect.selectedIndex].value+\'&amp;player='.$fetch['xpid'].'\',\'player\',10,10)"  src="'.DEBASER_UIMG.'/playericons/'.$fetch['playericon'].'" width="20" height="20" alt="'.$fetch['name'].'" title="'.$fetch['name'].'" /> ';
			}
		}
    echo $imagerow;
	}

	function ajaxembed() {

		global $xoopsDB, $xoopsModuleConfig, $xoopsUser, $myts;

		if ($_POST['displayer'] != 0) {
			$result1 = $xoopsDB->query("SELECT html_code, height, width, autostart, urltoscript FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($_POST['displayer'])."");
			list($htmlcode, $height, $width, $autostart, $urltoscript) = $xoopsDB->fetchRow($result1);
		} else {
				$htmlcode = '';
		}

		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$result2 = $xoopsDB->query("SELECT filename, uid, linktype, linkcode FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($_POST['id'])."");
		list($filename, $uid, $linktype, $linkcode) = $xoopsDB->fetchRow($result2);

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $urltoscript, intval($_POST['id']));

		$htmlcode = str_replace($searcharray, $replacearray, $htmlcode);

		if ($linkcode == '') {
			$userpath = getthedir($uid);
			$htmlcode = str_replace('<@mp3file@>', DEBASER_UUP.'/'.$userpath.$filename, $htmlcode);
		} else {
			if ($linktype == 'link') {
				$htmlcode = str_replace('<@mp3file@>', $linkcode, $htmlcode);
			} else {
				$htmlcode = stripslashes($linkcode);
			}
		}

		echo $myts->htmlspecialchars($htmlcode);
	}

	function ajaxupdate() {

		$proceed = false;
		$seconds = 60*10;

		if (isset($_POST['ts']) && isset($_COOKIE['jtoken']) && $_COOKIE['jtoken'] == md5('fb loves yk'.$_POST['ts'])) $proceed = true;

		if (!$proceed) {
			echo _MD_DEBASER_SUSP;
			exit;
		}

		if (((int)$_POST['ts'] + $seconds) < mktime()) {
			echo _MD_DEBASER_ELAPSE;
			exit;
		}

    	global $xoopsDB, $current_userid, $xoopsModuleConfig, $groups;

		$result = $xoopsDB->query("SELECT playlist FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		$hasresult = $xoopsDB->getRowsNum($result);

		$fileid = substr($_POST['id'], 1);

		if ($hasresult != 1) {
			$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_user')." (debuid, playlist) VALUES (".intval($current_userid).", ".intval($fileid).")");
		} else {
			list($currentfav) = $xoopsDB->fetchRow($result);
			$currentfav = $currentfav.' '.$fileid;
			$currentfav = explode(' ', $currentfav);
			$currentfav = array_unique($currentfav);
			$currentfav = implode(' ', $currentfav);

			$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_user')." SET playlist = ".$xoopsDB->quoteString(trim($currentfav))." WHERE debuid = ".intval($current_userid)."");
		}
	    echo _MD_DEBASER_WASADDED;

		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl');

		$result2 = $xoopsDB->query("SELECT playlist FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		list($currentorder) = $xoopsDB->fetchRow($result2);

		$favarray = explode(" ", $currentorder);
		$correctorder = str_replace(' ', ',', $currentorder);

		$result2 = $xoopsDB->query("SELECT xfid, filename, title, artist, fileext FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid IN (".implode(', ', array_map('intval', $favarray)).") ORDER BY FIND_IN_SET(xfid, '$correctorder')");

		if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) $file_path = DEBASER_UUP.'/user_'.$current_userid.'_/';
		else $file_path = DEBASER_UUP.'/';

		$hard_path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $file_path);

		$getlofi = checkLofi();

	// Write the physical playlist file
	$playlistxspf =  '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$playlistxspf .= '<playlist version="1" xmlns="http://xspf.org/ns/0/">'."\n";
	$playlistxspf .= "\t<trackList>\n\n";

	$playlistsmil =  '<smil>' . "\n";
	$playlistsmil .= '<body>'."\n";
	$playlistsmil .= "\t<seq>\n\n";

	$playlistwpl = '<?wpl version="1.0"?>'."\n";
	$playlistwpl .= '<smil>' . "\n";
    $playlistwpl .= '<head>' . "\n";
    $playlistwpl .= '<meta name="Generator" content="Microsoft Windows Media Player -- 11.0.5721.5145"/>' . "\n";
	$playlistwpl .= '<meta name="AverageRating" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="TotalDuration" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="ItemCount" content="0"/>' . "\n";
	$playlistwpl .= '<author/>' . "\n";
	$playlistwpl .= '<title>debaser playlist</title>' . "\n";
    $playlistwpl .= '</head>' . "\n";
	$playlistwpl .= '<body>'."\n";
	$playlistwpl .= "\t<seq>\n\n";

	while($fetch = $xoopsDB->fetchArray($result2)) {

		if ($getlofi == 1 && file_exists($hard_path.$fetch['filename']) && $fetch['fileext'] == 'mp3') $loprefix = 'lofi_';
		else $loprefix = '';

		$playlistxspf .= "\t\t<track>\n";
		$playlistxspf .= "\t\t\t<title>".$fetch['title']."</title>\n";
		$playlistxspf .= "\t\t\t<creator>".$fetch['artist']."</creator>\n";
		$playlistxspf .= "\t\t\t<location>".$file_path.$loprefix.$fetch['filename']."</location>\n";
		$playlistxspf .= "\t\t</track>\n\n";
		$playlistsmil .= "\t\t\t<audio src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
		$playlistwpl .= "\t\t\t<media src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
	}
	$playlistxspf .= ("\t</trackList>\n");
	$playlistxspf .= ("</playlist>");

	$playlistsmil .= ("\t</seq>\n");
	$playlistsmil .= ("</body>\n");
	$playlistsmil .= ("</smil>");

	$playlistwpl .= ("\t</seq>\n");
	$playlistwpl .= ("</body>\n");
	$playlistwpl .= ("</smil>");

	$playlistfilexspf = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml';
	$Handlexspf = fopen($playlistfilexspf, 'wb');
	fwrite($Handlexspf, $playlistxspf);
	fclose($Handlexspf);

	$playlistfilesmil = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil';
	$Handlesmil = fopen($playlistfilesmil, 'wb');
	fwrite($Handlesmil, $playlistsmil);
	fclose($Handlesmil);

	$playlistfilewpl = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl';
	$Handlewpl = fopen($playlistfilewpl, 'wb');
	fwrite($Handlewpl, $playlistwpl);
	fclose($Handlewpl);

	}

    function ajaxinner() {
    global $xoopsDB, $xoopsModuleConfig;

	$fileselect = $_GET['id'];
	$playerselect = $_GET['player'];
	$path = $_GET['path'];
	$equal = intval($_GET['equal']);

	$result1 = $xoopsDB->query("SELECT xpid, html_code, height, width, autostart, urltoscript FROM ".$xoopsDB->prefix('debaser_player')." WHERE xpid = ".intval($playerselect)." AND isactive = 1");
	list($xpid, $playercode, $height, $width, $autostart, $urltoscript) = $xoopsDB->fetchRow($result1);

	$result2 = $xoopsDB->query("SELECT xfid, filename, linkcode, linktype, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($fileselect)."");
	list($xfid, $filename, $linkcode, $linktype, $haslofi) = $xoopsDB->fetchRow($result2);

	if ($xoopsModuleConfig['uselame'] == 1) $getlofi = checkLofi();
	else $getlofi = 0;

	if ($haslofi == 1 && $getlofi == 1) $filename = 'lofi_'.$filename;

	$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_files')." SET views = views+1 WHERE xfid = ".intval($xfid)."");

		if ($linkcode == '' && $filename != '') {

		$extrapath = getthedir($path);
		$urltofile = DEBASER_UUP.'/'.$extrapath.$filename;
		// generate the output code
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';

		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $urltofile, $urltoscript, intval($xfid));

		$playercode = str_replace($searcharray, $replacearray, $playercode);

		} else {
			if ($linktype != 'link') {
			$playercode = str_replace('\\', '', $linkcode);
			} else {
		if ($urltoscript != '') $urltoscript = DEBASER_URL.'/'.$urltoscript.'/';
		else $urltoscript = '';
		$searcharray = array('<@height@>', '<@width@>', '<@autostart@>', '<@mp3file@>', '<@urltoscript@>', '<@id@>');
		$replacearray = array($height, $width, $autostart, $linkcode, $urltoscript, intval($xfid));

		$playercode = str_replace($searcharray, $replacearray, $playercode);
			}
		}

		if ($equal == 1) {
			$content = '<div style="float:left; background-color: #231f20; padding-left: 5px; padding-right:20px"><!--[if IE]><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" height="140" width="165"><param name="movie" value="'.DEBASER_URL.'/swf/equalizer.swf" /><param name="quality" value="best" /><param name="wmode" value="transparent" /><![endif]--><object class="playerdisplay" height="140" data="'.DEBASER_URL.'/swf/equalizer.swf" type="application/x-shockwave-flash" width="165">	<param name="quality" value="best" /><param name="wmode" value="transparent" /></object><!--[if IE]></object><![endif]--></div>';
			if ($height < 140) $height = 140;
			else $height+20;
			$width = $width+220;
		} else {
			$content = '';
			$height = $height+20;
			$width = $width+20;
		}

    $content .= '<style type="text/css">#fancy_outer {width: '.$width.'px !important; height: '.$height.'px !important;}</style><div style="background-color:#fff; padding-right:15px; z-index:999; float:right">'.$playercode.'</div>';

	echo $content;
	}

	function transcode() {

		global $xoopsModuleConfig, $xoopsDB, $xoopsConfig;

    	require_once DEBASER_CLASS.'/getid3/getid3.php';
    	include_once DEBASER_ROOT.'/language/'.$xoopsConfig['language'].'/admin.php';

    	$fileid = $_POST['id'];

		$result = $xoopsDB->query("SELECT filename, uid, fileext, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid = ".intval($fileid)."");
		list($filename, $uid, $fileext, $haslofi) = $xoopsDB->fetchRow($result);

		$extrapath = getthedir($uid);
		$infile = DEBASER_RUP.'/'.$extrapath.$filename;

		$id3 = new getID3;
		getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);
		$info = $id3->Analyze($infile);
		getid3_lib::CopyTagsToComments($info);
		$plustime = time();
		$tmp = DEBASER_ROOT.'/tmp/mp3v2c_'.$plustime;

//--silent --nores --nohist --mp3input -f s -b 64

		// Re-encode
		exec("".$xoopsModuleConfig['pathtolame']." ".$xoopsModuleConfig['resampleto']." ".escapeshellarg($infile)." ".escapeshellarg($tmp)." ");
		// Re-write id3-information
		if (!empty($info['comments_html']['artist']) || !empty($info['comments_html']['title']) || !empty($info['comments_html']['album']) || !empty($info['comments_html']['year']) || !empty($info['comments_html']['track']) || !empty($info['comments_html']['genre']) || !empty($info['comments_html']['totaltracks']) || !empty($info['comments_html']['tracknum'])) {
			$tagwriter = new getid3_writetags;
			$tagwriter->filename = $tmp;
			$tagwriter->tagformats = array('id3v1', 'id3v2.4');
			$tagwriter->remove_other_tags = true;
			$tagdata = array(array());

			if (!empty($info['comments_html']['artist'])) $tagdata['artist'][0] = $info['comments_html']['artist'][0];
			if (!empty($info['comments_html']['title'])) $tagdata['title'][0] = $info['comments_html']['title'][0];
			if (!empty($info['comments_html']['album'])) $tagdata['album'][0] = $info['comments_html']['album'][0];
			if (!empty($info['comments_html']['year'])) $tagdata['year'][0] = $info['comments_html']['year'][0];
			if (!empty($info['comments_html']['track'])) $tagdata['track'][0] = $info['comments_html']['track'][0];
			if (!empty($info['comments_html']['genre'])) $tagdata['genre'][0] = $info['comments_html']['genre'][0];
			if (!empty($info['comments_html']['totaltracks'])) $tagdata['totaltracks'][0] = $info['comments_html']['totaltracks'][0];
			if (!empty($info['comments_html']['tracknum'])) $tagdata['tracknum'][0] = $info['comments_html']['tracknum'][0];

  			$tagwriter->tag_data = $tagdata;

  			if ($tagwriter->WriteTags()) {
    			if (!empty($tagwriter->warnings)) echo sprintf(_AM_DEBASER_REWRITEWARN1, implode("\n", $tagwriter->warnings)."\n");
			} else {
				echo _AM_DEBASER_REWRITEWARN2;
			}
		}

	if ($haslofi == 1) @unlink(DEBASER_RUP.'/'.$extrapath.'lofi_'.$filename);
	else $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET haslofi = 1 WHERE xfid = ".intval($fileid)."");

	$outfile = DEBASER_RUP.'/'.$extrapath.'lofi_'.$filename;

	if (rename($tmp, $outfile)) {
		echo '<img src="'.DEBASER_UIMG.'/lofiok.png" alt="" />';
	} else {
		echo _AM_DEBASER_REWRITEWARN3;
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET haslofi = 0 WHERE xfid = ".intval($fileid)."");
		@unlink($tmp);
		exit();
	}
    }

	function ajaxsearch() {

		global $xoopsDB;

		// check the values
		$searchitem1 = isset($_POST['searchitem1']) ? addSlashes($_POST['searchitem1']) : '';

		$result = $xoopsDB->query("SELECT xfid, artist, title FROM ".$xoopsDB->prefix('debaser_files')." WHERE CONCAT_WS(' ', artist, title) LIKE '$searchitem1' OR artist LIKE '%$searchitem1%' OR title LIKE '%$searchitem1%'");

		$returnresult = '<br /><div id="debsearchresults"><h1>'._MD_DEBASER_SEARCHRES.'</h1>';

		while(list($getback, $getback2, $getback3) = $xoopsDB->fetchRow($result)) {
			$returnresult .= '<span style="line-height:150%"><a href="'.DEBASER_URL.'/singlefile.php?id='.$getback.'">'.$getback2.' '.$getback3.'</a><br /></span>';
		}

		$returnresult .= '</div>';
		echo $returnresult;
	}

	function reorderplaylist() {

		global $current_userid, $xoopsDB, $groups, $module_id, $xoopsModuleConfig, $xoopsConfig;

		$proceed = false;
		$seconds = 60*10;

		if (isset($_POST['ts']) && isset($_COOKIE['jtoken']) && $_COOKIE['jtoken'] == md5('fb loves yk'.$_POST['ts'])) $proceed = true;

		if (!$proceed) {
			echo _MD_DEBASER_SUSP;
			exit;
		}

		if (((int)$_POST['ts'] + $seconds) < mktime()) {
			echo _MD_DEBASER_ELAPSE;
			exit;
		}

		$favor1 = $_POST['ids'];

		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_user')." SET playlist = ".$xoopsDB->quoteString($favor1)."  WHERE debuid = ".intval($current_userid)." ");

		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl');

		$getlofi = checkLofi();

		$favarray = explode(" ", $favor1);
		$correctorder = str_replace(' ', ',', $favor1);

		$result = $xoopsDB->query("SELECT xfid, filename, title, artist, fileext FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid IN (".implode(', ', array_map('intval', $favarray)).") ORDER BY FIND_IN_SET(xfid, '$correctorder')");

		if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) $file_path = DEBASER_UUP.'/user_'.$current_userid.'_/';
		else $file_path = DEBASER_UUP.'/';

		$hard_path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $file_path);

	// Write the physical playlist file
	$playlistxspf =  '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$playlistxspf .= '<playlist version="1" xmlns="http://xspf.org/ns/0/">'."\n";
	$playlistxspf .= "\t<trackList>\n\n";

	$playlistsmil =  '<smil>' . "\n";
	$playlistsmil .= '<body>'."\n";
	$playlistsmil .= "\t<seq>\n\n";

	$playlistwpl = '<?wpl version="1.0"?>'."\n";
	$playlistwpl .= '<smil>' . "\n";
    $playlistwpl .= '<head>' . "\n";
    $playlistwpl .= '<meta name="Generator" content="Microsoft Windows Media Player -- 11.0.5721.5145"/>' . "\n";
	$playlistwpl .= '<meta name="AverageRating" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="TotalDuration" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="ItemCount" content="0"/>' . "\n";
	$playlistwpl .= '<author/>' . "\n";
	$playlistwpl .= '<title>debaser playlist</title>' . "\n";
    $playlistwpl .= '</head>' . "\n";
	$playlistwpl .= '<body>'."\n";
	$playlistwpl .= "\t<seq>\n\n";

	while($fetch = $xoopsDB->fetchArray($result)) {

		if ($getlofi == 1 && file_exists($hard_path.'lofi_'.$fetch['filename']) && $fetch['fileext'] == 'mp3') $loprefix = 'lofi_';
		else $loprefix = '';

		$playlistxspf .= "\t\t<track>\n";
		$playlistxspf .= "\t\t\t<title>".$fetch['title']."</title>\n";
		$playlistxspf .= "\t\t\t<creator>".$fetch['artist']."</creator>\n";
		$playlistxspf .= "\t\t\t<location>".$file_path.$loprefix.$fetch['filename']."</location>\n";
		$playlistxspf .= "\t\t</track>\n\n";
		$playlistsmil .= "\t\t\t<audio src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
		$playlistwpl .= "\t\t\t<media src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
	}
	$playlistxspf .= ("\t</trackList>\n");
	$playlistxspf .= ("</playlist>");

	$playlistsmil .= ("\t</seq>\n");
	$playlistsmil .= ("</body>\n");
	$playlistsmil .= ("</smil>");

	$playlistwpl .= ("\t</seq>\n");
	$playlistwpl .= ("</body>\n");
	$playlistwpl .= ("</smil>");

	$playlistfilexspf = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml';
	$Handlexspf = fopen($playlistfilexspf, 'wb');
	fwrite($Handlexspf, $playlistxspf);
	fclose($Handlexspf);

	$playlistfilesmil = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil';
	$Handlesmil = fopen($playlistfilesmil, 'wb');
	fwrite($Handlesmil, $playlistsmil);
	fclose($Handlesmil);

	$playlistfilewpl = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl';
	$Handlewpl = fopen($playlistfilewpl, 'wb');
	fwrite($Handlewpl, $playlistwpl);
	fclose($Handlewpl);

	}

	function removefrompl() {

		$proceed = false;
		$seconds = 60*10;

		if (isset($_POST['ts']) && isset($_COOKIE['jtoken']) && $_COOKIE['jtoken'] == md5('fb loves yk'.$_POST['ts'])) $proceed = true;

		if (!$proceed) {
			echo _MD_DEBASER_SUSP;
			exit();
		}

		if (((int)$_POST['ts'] + $seconds) < mktime()) {
			echo _MD_DEBASER_ELAPSE;
			exit();
		}

		global $current_userid, $xoopsDB, $groups, $module_id, $xoopsModuleConfig, $xoopsConfig;

		$tempid = str_replace('a', '', $_POST['id']);

		$deleteid = @explode(' ', $tempid);

		$resultpl = $xoopsDB->query("SELECT playlist FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		list($actualplaylist) = $xoopsDB->fetchRow($resultpl);

			$favor1 = @subtractArrays(explode(' ', $actualplaylist), $deleteid);
			$favor1 = @implode(' ', $favor1);

		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_user')." SET playlist = ".$xoopsDB->quoteString($favor1)."  WHERE debuid = ".intval($current_userid)." ");

		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil');
		if (file_exists(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl')) @unlink(DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl');

		$getlofi = checkLofi();

		$favarray = explode(" ", $favor1);
		$correctorder = str_replace(' ', ',', $favor1);

		$result = $xoopsDB->query("SELECT xfid, filename, title, artist, fileext FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid IN (".implode(', ', array_map('intval', $favarray)).") ORDER BY FIND_IN_SET(xfid, '$correctorder')");

		if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) $file_path = DEBASER_UUP.'/user_'.$current_userid.'_/';
		else $file_path = DEBASER_UUP.'/';

		$hard_path = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $file_path);

	$write2ul = '';

	// Write the physical playlist files
	$playlistxspf =  '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$playlistxspf .= '<playlist version="1" xmlns="http://xspf.org/ns/0/">'."\n";
	$playlistxspf .= "\t<trackList>\n\n";

	$playlistsmil =  '<smil>' . "\n";
	$playlistsmil .= '<body>'."\n";
	$playlistsmil .= "\t<seq>\n\n";

	$playlistwpl = '<?wpl version="1.0"?>'."\n";
	$playlistwpl .= '<smil>' . "\n";
    $playlistwpl .= '<head>' . "\n";
    $playlistwpl .= '<meta name="Generator" content="Microsoft Windows Media Player -- 11.0.5721.5145"/>' . "\n";
	$playlistwpl .= '<meta name="AverageRating" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="TotalDuration" content="0"/>' . "\n";
	$playlistwpl .= '<meta name="ItemCount" content="0"/>' . "\n";
	$playlistwpl .= '<author/>' . "\n";
	$playlistwpl .= '<title>debaser playlist</title>' . "\n";
    $playlistwpl .= '</head>' . "\n";
	$playlistwpl .= '<body>'."\n";
	$playlistwpl .= "\t<seq>\n\n";

	while($fetch = $xoopsDB->fetchArray($result)) {

		if ($getlofi == 1 && file_exists($hard_path.$fetch['filename']) && $fetch['fileext'] == 'mp3') $loprefix = 'lofi_';
		else $loprefix = '';

		$playlistxspf .= "\t\t<track>\n";
		$playlistxspf .= "\t\t\t<title>".$fetch['title']."</title>\n";
		$playlistxspf .= "\t\t\t<creator>".$fetch['artist']."</creator>\n";
		$playlistxspf .= "\t\t\t<location>".$file_path.$loprefix.$fetch['filename']."</location>\n";
		$playlistxspf .= "\t\t</track>\n\n";
		$playlistsmil .= "\t\t\t<audio src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
		$playlistwpl .= "\t\t\t<media src=\"".$file_path.$loprefix.$fetch['filename']."\" />\n";
		$write2ul .= '<li title="'.$fetch['xfid'].'" style="padding-bottom:5px"><div class="diffi" style="border:1px solid black; width:380px; padding:5px; float:left">'.$fetch['artist'].': '.$fetch['title'].'</div> <div style="padding-bottom:10px"><img src="'.DEBASER_UIMG.'/delete.png" id="a'.$fetch['xfid'].'" class="removefromplaylist" title="'._DELETE.'" alt="'._DELETE.'" /></div></li>';
	}
	$playlistxspf .= ("\t</trackList>\n");
	$playlistxspf .= ("</playlist>");

	$playlistsmil .= ("\t</seq>\n");
	$playlistsmil .= ("</body>\n");
	$playlistsmil .= ("</smil>");

	$playlistwpl .= ("\t</seq>\n");
	$playlistwpl .= ("</body>\n");
	$playlistwpl .= ("</smil>");

	$playlistfilexspf = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.xml';
	$Handlexspf = fopen($playlistfilexspf, 'wb');
	fwrite($Handlexspf, $playlistxspf);
	fclose($Handlexspf);

	$playlistfilesmil = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.smil';
	$Handlesmil = fopen($playlistfilesmil, 'wb');
	fwrite($Handlesmil, $playlistsmil);
	fclose($Handlesmil);

	$playlistfilewpl = ''.DEBASER_PLAY.'/playlist_'.$current_userid.'_.wpl';
	$Handlewpl = fopen($playlistfilewpl, 'wb');
	fwrite($Handlewpl, $playlistwpl);
	fclose($Handlewpl);

		echo $write2ul;
	}

	function similarities() {
		global $xoopsDB, $xoopsModuleConfig, $langa;
		$similaritypcnt1 = '';
		$similaritypcnt2 = '';
		$similaritypcnt3 = '';
		$similaritypcnt4 = '';
		$similaritypcnt5 = '';
		$similaritypcnt6 = '';
		$similaritypcnt7 = '';
		$simkeys = array();
		$simvalues = array();
		$output = '<table cellpadding="0" cellspacing="0" border="0" class="similartable">';

		$original = $xoopsDB->query("SELECT a.xfid, a.title, a.artist, a.album, a.year, a.bitrate, a.frequence, b.textfileid, b.textfiletext, b.language FROM ".$xoopsDB->prefix('debaser_files')." a, ".$xoopsDB->prefix('debaser_text')." b WHERE a.xfid = ".intval($_POST['thisfileid'])." AND b.textfileid = ".intval($_POST['thisfileid'])." AND b.language = $langa");
		list($oxfid, $otitle, $oartist, $oalbum, $oyear, $obitrate, $ofrequence, $otextfileid, $otextfiletext, $olanguage) = $xoopsDB->fetchRow($original);

		$comparison = $xoopsDB->query("SELECT a.xfid, a.title, a.artist, a.album, a.year, a.bitrate, a.frequence, b.textfileid, b.textfiletext, b.textcatid, b.language FROM ".$xoopsDB->prefix('debaser_files')." a, ".$xoopsDB->prefix('debaser_text')." b WHERE a.xfid != ".intval($_POST['thisfileid'])." AND b.textfileid != ".intval($_POST['thisfileid'])." AND b.textcatid = 0 AND b.language = $langa GROUP BY a.xfid");
		while ($row = $xoopsDB->fetchArray($comparison)) {
			similar_text(strtolower($otitle), strtolower($row['title']), $similaritypcnt1);
			similar_text(strtolower($oartist), strtolower($row['artist']), $similaritypcnt2);
			similar_text(strtolower($oalbum), strtolower($row['album']), $similaritypcnt3);
			similar_text(strtolower($oyear), strtolower($row['year']), $similaritypcnt4);
			similar_text(strtolower($obitrate), strtolower($row['bitrate']), $similaritypcnt5);
			similar_text(strtolower($ofrequence), strtolower($row['frequence']), $similaritypcnt6);
			similar_text(strtolower($otextfiletext), strtolower($row['textfiletext']), $similaritypcnt7);
			$percentage = ($similaritypcnt1 + $similaritypcnt2 + $similaritypcnt3 + $similaritypcnt4 + $similaritypcnt5 + $similaritypcnt6 + $similaritypcnt7)/7;
			if ($percentage >= $xoopsModuleConfig['simlimit']) {
				array_push($simkeys, number_format($percentage, 0));
				array_push($simvalues, '<a href="singlefile.php?id='.$row['xfid'].'">'.$row['artist'].' - '.$row['title'].'</a>');
			}
			unset($percentage);
		}
		if (!empty($simkeys)) {
		$outarray = array_combine($simkeys, $simvalues);
		krsort($outarray);
		foreach ($outarray as $k => $v) {
    		$output .= '<tr><td class="simperc">'.$k.'%</td><td class="simentry">'.$v.'</a></td></tr>';
		}
		unset($outarray);
		$output .= '</table>';
		} else {
			$output = _MD_DEBASER_NOSIMATM;
		}
		echo $output;
	}

	if(!isset($_POST['action'])) $op = isset($_GET['action']) ? $_GET['action'] : 'default';
	else $op = $_POST['action'];

	switch ($op) {

		case 'tellafriend':
		tellafriend();
		break;

		case 'radioselecter':
		radioselecter();
		break;

		case 'tvselecter':
		tvselecter();
		break;

		case 'removefrompl':
		removefrompl();
		break;

		case 'reorderplaylist':
		reorderplaylist();
		break;

		case 'ajaxsearch':
		ajaxsearch();
		break;

		case 'transcode':
		transcode();
		break;

		case 'ajaxinner':
		ajaxinner();
		break;

		case 'ajaxupdate':
		ajaxupdate();
		break;

		case 'ajaxembed':
		ajaxembed();
		break;

		case 'similarities':
		similarities();
		break;

		case 'default':
		default:
		goawayajax();
		break;
	}

?>