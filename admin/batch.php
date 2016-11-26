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
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once DEBASER_CLASS.'/debasertree.php';

	function makeBatch() {

		global $xoopsDB, $xoopsModuleConfig;

		xoops_cp_header();
		debaser_adminMenu();

		$batchform = new XoopsThemeForm(_AM_DEBASER_BATCHFORM, 'batchform', xoops_getenv('PHP_SELF'));
		$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
		ob_start();
		$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle');
		$listgenre = new XoopsFormLabel(_AM_DEBASER_GENRE, ob_get_contents());
		ob_end_clean();
		$batchform->addElement($listgenre);
		$batchform->addElement(new XoopsFormSelectUser(_AM_DEBASER_OWNER, 'uid', false, '', '1', false));
		$batchform->addElement(new XoopsFormRadioYN(_AM_DEBASER_USELAME, 'lameit', 0, _YES, _NO));
		if ($xoopsModuleConfig['useffmpeg'] == 1) $batchform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MAKETHUMB, 'thumbit', 0, _YES, _NO));
		$batchform->addElement(new XoopsFormHidden('op', 'batchprocessing'));

		$dir = DEBASER_ROOT.'/batchload/';
		$i = 0;
		if (is_dir($dir)) {
    		if ($dh = opendir($dir)) {
    			while(($file = readdir($dh)) !== false) {
        			if ($file == '.' || $file == '..' || $file == 'index.html') continue;
        			$i = $i+1;
        			$batchform->addElement(new XoopsFormLabel($i, $file));
    			}
    		}
		}

		closedir($dh);

		$batchform->addElement(new XoopsFormButton( '', 'submit', _SUBMIT, 'submit' ));
		$batchform->display();

		xoops_cp_footer();
		}

	// processing the files
	function batchprocessing() {

		global $xoopsModuleConfig, $xoopsDB, $groups;

		$batchapprove = $xoopsModuleConfig['batchapprove'];
		$language = $xoopsModuleConfig['masterlang'];

		$member_handler =& xoops_gethandler('member');
		$groupsarray = $member_handler->getGroupsByUser($_POST['uid']);

		if (@array_intersect($xoopsModuleConfig['owndir'], $groupsarray)) {
			$userpath = 'user_'.$_POST['uid'].'_/';
			if (!is_dir(DEBASER_RUP.'/'.$userpath)) @mkdir(DEBASER_RUP.'/'.$userpath, 0777);
		} else {
			$userpath = '';
		}

		if ($xoopsModuleConfig['uselame'] == 1 && $_POST['lameit'] == 1) $uselame = 1;
		else $uselame = 0;

		require_once DEBASER_CLASS.'/getid3/getid3.php';

			$dir = DEBASER_ROOT.'/batchload/';
			$i = 0;
			if (is_dir($dir)) {
    			if ($dh = opendir($dir)) {
    				while(($file = readdir($dh)) !== false) {
        				if ($file == '.' || $file == '..' || $file == 'index.html') continue;
        				$i = $i+1;
						$batchfile = $dir.$file;
						$getID3 = new getID3;

						$ThisFileInfo = $getID3->Analyze($batchfile);
						getid3_lib::CopyTagsToComments($ThisFileInfo);
						$mimetyplink = (!empty($ThisFileInfo['mime_type']) ? $ThisFileInfo['mime_type'] : '');
						$fileext = (!empty($ThisFileInfo['fileformat']) ? $ThisFileInfo['fileformat'] : '');
						$length = (!empty($ThisFileInfo['playtime_string']) ? $ThisFileInfo['playtime_string'] : '');
						$bitrate = (!empty($ThisFileInfo['bitrate']) ? round($ThisFileInfo['bitrate'] / 1000) : '');
						$artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode($ThisFileInfo['comments_html']['artist']) : '');
						$title = (!empty($ThisFileInfo['comments_html']['title']) ? implode($ThisFileInfo['comments_html']['title']) : '');
						$frequence = (!empty($ThisFileInfo['audio']['sample_rate']) ? $ThisFileInfo['audio']['sample_rate'] : '');
						$album = (!empty($ThisFileInfo['comments_html']['album']) ? implode($ThisFileInfo['comments_html']['album']) : '');
						$track = (!empty($ThisFileInfo['comments_html']['track']) ? implode($ThisFileInfo['comments_html']['track']) : '');
						$year = (!empty($ThisFileInfo['comments_html']['year']) ? implode($ThisFileInfo['comments_html']['year']) : '');

						if ($artist == '') {
							$artisterror = 1;
							$artist = 'Artist'.$i;
						} else {
							$artisterror = 0;
						}

				if ($title == '') {
					$titleerror = 1;
					$title = 'Title'.$i;
				} else {
					$titleerror = 0;
				}

				if ($mimetyplink == '') $mimetypeerror = 1;
				else $mimetypeerror = 0;

				if ($fileext == '') $fileexterror = 1;
				else $fileexterror = 0;

				$ending = substr(strrchr($file, "."), 1);
				$filename = mt_rand().'_'.time().'.'.$ending;
				$added = time();

				if (isset($_POST['thumbit']) && $_POST['thumbit'] == 1) {
					$ffmpegfiletype = explode(' ', $xoopsModuleConfig['ffmpegtypes']);
					if (in_array($fileext, $ffmpegfiletype)) {
						require_once DEBASER_CLASS.'/Thumbnail_Extractor.php';
						require_once DEBASER_CLASS.'/Thumbnail_Joiner.php';

						$ffmpeg = $xoopsModuleConfig['pathtoffmpeg']; //'C:/xampp/imagemagick/ffmpeg';
						$video = $dir.$file;
						$thumbsize = $xoopsModuleConfig['ffmpegthumbsize'];
						$frames = explode(' ', $xoopsModuleConfig['ffmpegframes']);
						// we need a singleframe for a still image
						$stillframes = $frames;
						$stillframe = array(array_shift($stillframes));
						$stilljoiner = new Thumbnail_Joiner(0);
						foreach (new Thumbnail_Extractor($video, $stillframe, $thumbsize, $ffmpeg) as $key => $singleframe) {
							$stilljoiner->add($singleframe);
						}
				// we need to remove the file extension
				$remfileext = strlen($fileext)+1;
				$imagename = substr($filename, 0, -$remfileext);
				$stilljoiner->save(DEBASER_RUP.'/'.$userpath.$imagename.'.gif');
				// now we make the animation
				$joiner = new Thumbnail_Joiner($xoopsModuleConfig['ffmpegdelay']);
				foreach (new Thumbnail_Extractor($video, $frames, $thumbsize, $ffmpeg) as $key => $frame) {
					$joiner->add($frame);
				}
				$joiner->save(DEBASER_RUP.'/'.$userpath.$imagename.'_hover.gif');
			}
				}

				if ($uselame == 1 && $fileext == 'mp3' && $_POST['lameit'] == 1) {
					$plustime = time();
					$tmp = DEBASER_ROOT.'/tmp/mp3v2c_'.$plustime;
					exec("".$xoopsModuleConfig['pathtolame']." ".$xoopsModuleConfig['resampleto']." ".escapeshellarg($batchfile)." ".escapeshellarg($tmp)." ");
					getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);

					if (!empty($ThisFileInfo['comments_html']['artist']) || !empty($ThisFileInfo['comments_html']['title']) || !empty($ThisFileInfo['comments_html']['album']) || !empty($ThisFileInfo['comments_html']['year']) || !empty($ThisFileInfo['comments_html']['track']) || !empty($ThisFileInfo['comments_html']['genre']) || !empty($ThisFileInfo['comments_html']['totaltracks']) || !empty($ThisFileInfo['comments_html']['tracknum'])) {
			$tagwriter = new getid3_writetags;
			$tagwriter->filename = $tmp;
			$tagwriter->tagformats = array('id3v1', 'id3v2.4');
			$tagwriter->remove_other_tags = true;
			$tagdata = array(array());

			if (!empty($ThisFileInfo['comments_html']['artist'])) $tagdata['artist'][0] = $ThisFileInfo['comments_html']['artist'][0];
			if (!empty($ThisFileInfo['comments_html']['title'])) $tagdata['title'][0] = $ThisFileInfo['comments_html']['title'][0];
			if (!empty($ThisFileInfo['comments_html']['album'])) $tagdata['album'][0] = $ThisFileInfo['comments_html']['album'][0];
			if (!empty($ThisFileInfo['comments_html']['year'])) $tagdata['year'][0] = $ThisFileInfo['comments_html']['year'][0];
			if (!empty($ThisFileInfo['comments_html']['track'])) $tagdata['track'][0] = $ThisFileInfo['comments_html']['track'][0];
			if (!empty($ThisFileInfo['comments_html']['genre'])) $tagdata['genre'][0] = $ThisFileInfo['comments_html']['genre'][0];
			if (!empty($ThisFileInfo['comments_html']['totaltracks'])) $tagdata['totaltracks'][0] = $ThisFileInfo['comments_html']['totaltracks'][0];
			if (!empty($ThisFileInfo['comments_html']['tracknum'])) $tagdata['tracknum'][0] = $ThisFileInfo['comments_html']['tracknum'][0];

  			$tagwriter->tag_data = $tagdata;

  			if ($tagwriter->WriteTags()) {

    			$rewritewarn2 = 0;

    			if (!empty($tagwriter->warnings)) $rewritewarn1 = 1;
    			else $rewritewarn1 = 0;
			} else {
				$rewritewarn2 = 1;
			}
		}
				$outfile = DEBASER_RUP.'/'.$userpath.'lofi_'.$filename;
				$outfile2 = DEBASER_RUP.'/'.$userpath.$filename;
				if (@copy($tmp, $outfile)) {
					@copy($tmp, $outfile2);
					$haslofi = 1;
					$renameerror = 0;
					@unlink($tmp);
				} else {
					$haslofi = 0;
					$renameerror = 1;
					@unlink($tmp);
				}


				} else {
					$haslofi = 0;
					$outfile = DEBASER_RUP.'/'.$userpath.$filename;
					if (@copy($batchfile, $outfile)) {
						$renameerror = 0;
					} else {
						$renameerror = 1;
					}
				}

				if ($artisterror == 1 || $titleerror == 1 || $mimetypeerror == 1 || $rewritewarn1 == 1 || $rewritewarn2 == 1 || $renameerror == 1) $batchapprove = 0;

				// guessing the character encoding and forcing an reencode if no match
				if (function_exists('iconv') && function_exists('mb_detect_encoding')) {
					if (@mb_detect_encoding($title) != _CHARSET && @mb_detect_encoding($artist) != _CHARSET) {
    					$intitle = @mb_detect_encoding($title);
    					$inartist = @mb_detect_encoding($artist);
    					$title = @iconv($intitle, _CHARSET, $title);
    					$artist = @iconv($inartist, _CHARSET, $artist);
					}
				}


				$result2 = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_files')." (filename, added, title, artist, album, year, track, genreid, length, bitrate, frequence, approved, fileext, uid, language, haslofi) VALUES (".$xoopsDB->quoteString($filename).", ".intval($added).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($artist).", ".$xoopsDB->quoteString($album).", ".intval($year).", ".intval($track).", ".intval($_POST['textcatid']).", ".$xoopsDB->quoteString($length).", ".intval($bitrate).", ".intval($frequence).", ".intval($batchapprove).", ".$xoopsDB->quoteString($fileext).", ".intval($_POST['uid']).", ".$xoopsDB->quoteString($language).", ".intval($haslofi).")");

				$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total+1 WHERE genreid = ".intval($_POST['textcatid'])."");

		$poster = &$member_handler->getUser($_POST['uid']);
		$member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);

    		} // while end
    	}
	}

	closedir($dh);

	redirect_header('index.php', 2, _AM_DEBASER_ALLBATCHED);
	}

	function batchimaging() {
		global $xoopsDB, $xoopsModuleConfig;

		require_once DEBASER_CLASS.'/Thumbnail_Extractor.php';
		require_once DEBASER_CLASS.'/Thumbnail_Joiner.php';
		$ffmpeg = $xoopsModuleConfig['pathtoffmpeg']; //'C:/xampp/imagemagick/ffmpeg';
		$thumbsize = $xoopsModuleConfig['ffmpegthumbsize'];
		$frames = explode(' ', $xoopsModuleConfig['ffmpegframes']);
		$typearray = explode(' ', $xoopsModuleConfig['ffmpegtypes']);
		$inlist = "'".implode("','",$typearray)."'";
		$result = $xoopsDB->query("SELECT filename, fileext, uid FROM ".$xoopsDB->prefix('debaser_files')." WHERE fileext IN ($inlist) ");
		while(list($filename, $fileext, $uid) = $xoopsDB->fetchRow($result)) {
			$extrapath = getthedir($uid);
			$remfileext = strlen($fileext)+1;
			$imagename = substr($filename, 0, -$remfileext);
			if (!is_file(DEBASER_RUP.'/'.$extrapath.$imagename.'.gif')) {
				$video = DEBASER_RUP.'/'.$extrapath.$filename;
				// we need a singleframe for a still image
				$stillframes = $frames;
				$stillframe = array(array_shift($stillframes));
				$stilljoiner = new Thumbnail_Joiner(0);
				foreach (new Thumbnail_Extractor($video, $stillframe, $thumbsize, $ffmpeg) as $key => $singleframe) {
					$stilljoiner->add($singleframe);
				}
				// we need to remove the file extension
				$stilljoiner->save(DEBASER_RUP.'/'.$extrapath.$imagename.'.gif');
				// now we make the animation
				$joiner = new Thumbnail_Joiner($xoopsModuleConfig['ffmpegdelay']);
				foreach (new Thumbnail_Extractor($video, $frames, $thumbsize, $ffmpeg) as $key => $frame) {
					$joiner->add($frame);
				}
				$joiner->save(DEBASER_RUP.'/'.$extrapath.$imagename.'_hover.gif');
			}
		}

		redirect_header('index.php', 2, _AM_DEBASER_IMGWRITTEN);
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'batchprocessing':
		batchprocessing();
		break;

		case 'batchimaging':
		batchimaging();
		break;

		case 'default':
		default:
		makeBatch();
		break;
	}
?>