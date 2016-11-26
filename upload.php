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

	if (isset($_FILES['Filedata']) && isset($_FILES['Filedata']['name'])) {

		/* Due to a flash bug for MAC we have to output something, else
		   successful upload is never fired. Nasty sideeffect: you have
		   to grant module access to guests. Once the flash bug is fixed
		   you can remove the next line */

		echo ' ';

		$current_userid = $_POST['xuserid'];
		$groups = explode(' ', $_POST['xgroups']);
		$module_id = $_POST['xmoduleid'];

		include '../../mainfile.php';
		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		include_once DEBASER_RINC.'/functions.php';
		include_once XOOPS_ROOT_PATH.'/class/module.textsanitizer.php';
		$myts =& MyTextSanitizer::getInstance();

		// Workaround for Flash-Cookie-Bug, we have to restore the session
		if (isset($_POST['PHPSESSID'])) {
			session_id($_POST['PHPSESSID']);
		} else if (isset($_GET['PHPSESSID'])) {
			session_id($_GET['PHPSESSID']);
		}

		session_start();

		if (!isset($_POST['flashtoken']) || empty($_POST['flashtoken'])) exit();

		$tokenkey = $_SESSION['extfile'.$current_userid];
		if ($tokenkey !== $_POST['flashtoken']) exit();

		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('debaser');
		$module_id = $module->getVar('mid');

		$cookietrue = (isset($_COOKIE['lang'])) ? '1' : '0';
		$gettrue = (isset($_GET['lang'])) ? '1' : '0';

		if ($cookietrue == '0' && $gettrue == '0') {
			$langa = $xoopsDB->quoteString($xoopsConfig['language']);
			$langb = $xoopsConfig['language'];
		}

		if (($cookietrue == '1') || ($cookietrue == '1' && $gettrue == '1')) {
			$langa = $xoopsDB->quoteString($_COOKIE['lang']);
			$langb = $_COOKIE['lang'];
		}

		if (($gettrue == '1') || ($gettrue == '1' && $cookietrue == '0')) {
			$langa = $xoopsDB->quoteString($_GET['lang']);
			$langb = $_GET['lang'];
		}
	} else {
		include 'header.php';
		include_once DEBASER_RINC.'/functions.php';
		$current_userid = $_POST['userid'];

		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
			exit();
		}
	}

	// To prevent misuse or accessing this file directly without sending data
	if (!isset($_FILES['Filedata'])) {
		if (!isset($_FILES['mpupload'])) die(_MD_DEBASER_NOVALDATA);
	}

	$added = time();

	// checks for permission user directories
	if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) $upload_path = DEBASER_RUP.'/user_'.$current_userid.'_/';
	else $upload_path = DEBASER_RUP.'/';

	// checks for permission flash upload or alternatively if the user disabled the flash upload
	if (@array_intersect($xoopsModuleConfig['allowflashupload'], $groups)) {
		$use_flash = 1;
		$result = $xoopsDB->query("SELECT flashupload FROM ".$xoopsDB->prefix('debaser_user')." WHERE debuid = ".intval($current_userid)."");
		$hassetting = $xoopsDB->getRowsNum($result);
		if ($hassetting == 1) {
			list($flashupload) = $xoopsDB->fetchRow($result);
			if ($use_flash == $flashupload) {
				$use_flash = 1;
			} else {
				$use_flash = 0;
			}
		}
	} else {
		$use_flash = 0;
	}

	if ($use_flash == 1) {
		// Handle the Flashupload
		if (isset($_FILES['Filedata']) && isset($_FILES['Filedata']['name'])) {

			$ending = substr(strrchr($_FILES['Filedata']['name'], "."), 1);
			$fallbacktitle = $_FILES['Filedata']['name'];
			$cleanfilename = mt_rand().'_'.time().'.'.$ending;

			if (file_exists($upload_path.$cleanfilename)) $cleanfilename = $added.$cleanfilename;

			if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], $upload_path.$cleanfilename)) {
				// do nothing, because flash gets no response from here
			} else {
				// chmod the file
				@chmod($upload_path.$cleanfilename, 0777);
			}
		}
	} else {
		// Handle normal upload
		if (isset($_FILES['mpupload']) && isset($_FILES['mpupload']['name'])) {

			$ending = substr(strrchr($_FILES['mpupload']['name'], "."), 1);
			$fallbacktitle = $_FILES['mpupload']['name'];
			$cleanfilename = mt_rand().'_'.time().'.'.$ending;

			if (file_exists($upload_path.$cleanfilename)) $cleanfilename = $added.$cleanfilename;

			if (!move_uploaded_file($_FILES['mpupload']['tmp_name'], $upload_path.$cleanfilename)) {
				redirect_header('uploading.php', 2, _MD_FAILUPLOADING);
			} else {
				// chmod the file
				@chmod($upload_path.$cleanfilename, 0777);
			}
		}
	}

	// read information from the file
	require_once DEBASER_CLASS.'/getid3/getid3.php';
	$getID3 = new getID3;
	$ThisFileInfo = $getID3->Analyze($upload_path . $cleanfilename);
	getid3_lib::CopyTagsToComments($ThisFileInfo);

	$mimetyplink = (!empty($ThisFileInfo['mime_type']) ? $ThisFileInfo['mime_type'] : '');

	$fileext = (!empty($ThisFileInfo['fileformat']) ? $ThisFileInfo['fileformat'] : '');

	// I trust getid3 more than the delivered file extension so ...
/*	if ($fileext != '' && $fileext != $ending) {
		$length = strlen($ending) + 1;
		$cleanfilename = substr($cleanfilename, 0, -$length);
		$cleanfilename = $cleanfilename.'.'.$fileext;
	}*/

	$length = (!empty($ThisFileInfo['playtime_string']) ? $ThisFileInfo['playtime_string'] : '');
	$bitrate = (!empty($ThisFileInfo['bitrate']) ? round($ThisFileInfo['bitrate'] / 1000) : '');
	$artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode($ThisFileInfo['comments_html']['artist']) : '');
	$title = (!empty($ThisFileInfo['comments_html']['title']) ? implode($ThisFileInfo['comments_html']['title']) : '');
	$frequence = (!empty($ThisFileInfo['audio']['sample_rate']) ? $ThisFileInfo['audio']['sample_rate'] : '');
	$album = (!empty($ThisFileInfo['comments_html']['album']) ? implode($ThisFileInfo['comments_html']['album']) : '');
	$track = (!empty($ThisFileInfo['comments_html']['track']) ? implode($ThisFileInfo['comments_html']['track']) : '');
	$year = (!empty($ThisFileInfo['comments_html']['year']) ? implode($ThisFileInfo['comments_html']['year']) : '');

	if (isset($_POST['artist']) && $_POST['artist'] != '') $artist = $_POST['artist'];

	if (isset($_POST['title']) && $_POST['title'] != '') {
		$title = $_POST['title'];
	} else {
		// fallback for title, because we must add something
		if ($title == '') $title = $fallbacktitle;
	}

	if (isset($_POST['album']) && $_POST['album'] != '') $album = $myts->htmlSpecialChars($_POST['album']);
	if (isset($_POST['year']) && $_POST['year'] != '') $year = $_POST['year'];
	if (isset($_POST['track']) && $_POST['track'] != '') $track = $_POST['track'];
	if (isset($_POST['bitrate']) && $_POST['bitrate'] != '') $bitrate = $_POST['bitrate'];
	if (isset($_POST['frequence']) && $_POST['frequence'] != '') $frequence = $_POST['frequence'];
	if (isset($_POST['length']) && $_POST['length'] != '') $length = $_POST['length'];

	if ($artist == '' && $title == '') $artist = $cleanfilename;

	if ($xoopsModuleConfig['autoapprove'] == 1) $approved = 1;
	else $approved = 0;

	$genreid = $_POST['genrefrom'];

	// fallback for file extension
	if ($fileext == '') $fileext = substr(strrchr($cleanfilename, "."), 1);

	$fileext = strtolower($fileext);

	if ($mimetyplink == '') {
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['MIME_ID'] = XOOPS_URL.'/userinfo.php?uid='.$current_userid;
		$notification_handler->triggerEvent('global', 0, 'mimetype_submit', $tags);
		@unlink(DEBASER_ROOT.'/upload/'.$userpath.'/'.$cleanfilename);
		redirect_header('index.php', 2, _MD_DEBASER_GETNOMIME);
		exit();
	}

	$mimesql = "SELECT mime_admin, mime_user FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mime_types LIKE '%".$mimetyplink."%'";

	$mimeresult = $xoopsDB->query($mimesql);
	list($mimeadmin, $mimeuser) = $xoopsDB->fetchRow($mimeresult);

	if ($use_flash == 1) {
		if ($_POST['xusertype'] == 'mime_admin') $mimeinlist = $mimeadmin;
		else $mimeinlist = $mimeuser;
	} else {
		if ($is_deb_admin == true) $mimeinlist = $mimeadmin;
		else $mimeinlist = $mimeuser;
	}

	if ($xoopsModuleConfig['multilang'] == 1) {
		include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
		$multilang = 1;
		$langlist = XoopsLists::getLangList();
		$aa = implode(',', $langlist);
		$bb = explode(',', $aa);
	} else {
		$multilang = 0;
	}

	if ($mimeinlist == '1' && $mimetyplink != '') {

		if ($xoopsModuleConfig['useffmpeg'] == 1 && $xoopsModuleConfig['useffmpegsingleup'] == 1) {
			$ffmpegfiletype = explode(' ', $xoopsModuleConfig['ffmpegtypes']);
			if (in_array($fileext, $ffmpegfiletype)) {
				require_once DEBASER_CLASS.'/Thumbnail_Extractor.php';
				require_once DEBASER_CLASS.'/Thumbnail_Joiner.php';

				$ffmpeg = $xoopsModuleConfig['pathtoffmpeg']; //'C:/xampp/imagemagick/ffmpeg';
				$video = $upload_path.$cleanfilename;
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
				$imagename = substr($cleanfilename, 0, -$remfileext);
				$stilljoiner->save($upload_path.$imagename.'.gif');
				// now we make the animation
				$joiner = new Thumbnail_Joiner($xoopsModuleConfig['ffmpegdelay']);
				foreach (new Thumbnail_Extractor($video, $frames, $thumbsize, $ffmpeg) as $key => $frame) {
					$joiner->add($frame);
				}
				$joiner->save($upload_path.$imagename.'_hover.gif');
			}
		}

		if ($fileext == 'mp3' && $xoopsModuleConfig['uselame'] == 1 && $xoopsModuleConfig['uselamesingleup'] == 1) {
			$plustime = time();
			$tmp = DEBASER_ROOT.'/tmp/mp3v2c_'.$plustime;
			exec("".$xoopsModuleConfig['pathtolame']." ".$xoopsModuleConfig['resampleto']." ".escapeshellarg($upload_path.$cleanfilename)." ".escapeshellarg($tmp)." ");
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
			$outfile = $upload_path.'lofi_'.$cleanfilename;

			if (@rename($tmp, $outfile)) {
				$haslofi = 1;
				$renameerror = 0;
			} else {
				$haslofi = 0;
				$renameerror = 1;
				@unlink($tmp);
			}
		} else {
			$haslofi = 0;
		}

	// guessing the character encoding and forcing an reencode if no match
	if (function_exists('iconv') && function_exists('mb_detect_encoding')) {
		if (@mb_detect_encoding($title) != _CHARSET && @mb_detect_encoding($artist) != _CHARSET) {
    		$intitle = @mb_detect_encoding($title);
    		$inartist = @mb_detect_encoding($artist);
    		$title = iconv($intitle, _CHARSET, $title);
    		$artist = iconv($inartist, _CHARSET, $artist);
		}
	}

		if ($use_flash == 1) {

			$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('debaser_files')." (filename, added, title, artist, album, year, track, genreid, length, bitrate, frequence, approved, fileext, uid, language, haslofi) VALUES (".$xoopsDB->quoteString($cleanfilename).", ".intval($added).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($artist).", ".$xoopsDB->quoteString($album).", ".intval($year).", ".intval($track).", ".intval($genreid).", ".$xoopsDB->quoteString($length).", ".intval($bitrate).", ".intval($frequence).", ".intval($approved).", ".$xoopsDB->quoteString($fileext).", ".intval($current_userid).", ".$xoopsDB->quoteString($langb).", ".intval($haslofi).")");

			$newid = $xoopsDB->getInsertId();
			$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total+1 WHERE genreid = ".intval($genreid)."");

			if ($multilang == 0) {
			$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST['description']).", ".$xoopsDB->quoteString($langb).")");
			} else {

				foreach ($langlist as $langcontent) {
					$postdescription = $bb[$i].'_description';
					$language = $bb[$i];

					if ($_POST[$postdescription] != '') {
						$xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");
					}
					$i++;
					unset($postdescription);
					unset($language);
				}

			}
		} else {
			$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_files')." (filename, added, title, artist, album, year, track, genreid, length, bitrate, frequence, approved, fileext, uid, language, haslofi) VALUES (".$xoopsDB->quoteString($cleanfilename).", ".intval($added).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($artist).", ".$xoopsDB->quoteString($album).", ".intval($year).", ".intval($track).", ".intval($genreid).", ".$xoopsDB->quoteString($length).", ".intval($bitrate).", ".intval($frequence).", ".intval($approved).", ".$xoopsDB->quoteString($fileext).", ".intval($current_userid).", ".$xoopsDB->quoteString($langb).", ".intval($haslofi).")");

			$newid = $xoopsDB->getInsertId();
			$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total+1 WHERE genreid = ".intval($genreid)."");

			if ($multilang == 0) {
				$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST['description']).", ".$xoopsDB->quoteString($langb).")");
			} else {
				foreach ($langlist as $langcontent) {
					$postdescription = $bb[$i].'_description';
					$language = $bb[$i];

					if ($_POST[$postdescription] != '') {
						$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($newid).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");
					}
					$i++;
					unset($postdescription);
					unset($language);
				}
			}
		}

		$member_handler = &xoops_gethandler('member');
		$poster = &$member_handler->getUser($current_userid);
		$member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);

		if (isset($_FILES['mpupload']) && isset($_FILES['mpupload']['name'])) redirect_header('index.php', 2, _MD_DEBASER_UPSUCCESS);

	} else {
		@unlink(DEBASER_ROOT.'/upload/'.$userpath.'/'.$cleanfilename);
		if (isset($_FILES['mpupload']) && isset($_FILES['mpupload']['name'])) redirect_header('index.php', 2, _MD_DEBASER_UPFAIL);
	}

?>