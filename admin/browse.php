<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
//                                                             //
// /demo/demo.browse.php - part of getID3()                     //
// Sample script for browsing/scanning files and displaying    //
// information returned by getID3()                            //
// See readme.txt for more details                             //
//                                                            ///
/////////////////////////////////////////////////////////////////

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

	// set predefined variables as if magic_quotes_gpc was off,
	// whether the server's got it or not:
	UnifyMagicQuotes(false);

	// showfile is used to display embedded images from table_var_dump()
	// md5 of requested file is required to prevent abuse where any
	// random file on the server could be viewed
	if (@$_REQUEST['showfile']) {
		if (is_readable($_REQUEST['showfile'])) {
			if (md5_file($_REQUEST['showfile']) == @$_REQUEST['md5']) {
				readfile($_REQUEST['showfile']);
				exit;
			}
		}
		die('Cannot display "'.$_REQUEST['showfile'].'"');
	}

	if (!function_exists('getmicrotime')) {
		function getmicrotime() {
			list($usec, $sec) = explode(' ', microtime());
			return ((float) $usec + (float) $sec);
		}
	}

	$writescriptfilename = 'write.php';

	require_once DEBASER_CLASS.'/getid3/getid3.php';

	// Needed for windows only // adapt the path should be in modules/debaser/class
	define('GETID3_HELPERAPPSDIR', DEBASER_CLASS.'/helperapps/');

	// Initialize getID3 engine
	$getID3 = new getID3;
	$getID3->setOption(array('encoding' => 'UTF-8'));

	$getID3checkColor_Head = 'ccccdd';
	$getID3checkColor_DirectoryLight = 'ffcccc';
	$getID3checkColor_DirectoryDark = 'eebbbb';
	$getID3checkColor_FileLight = 'eee';
	$getID3checkColor_FileDark = 'ddd';
	$getID3checkColor_UnknownLight = 'ccccff';
	$getID3checkColor_UnknownDark = 'bbbbdd';

	xoops_cp_header();
	debaser_adminMenu();

	if (isset($_REQUEST['deletefile'])) {
		if (file_exists($_REQUEST['deletefile'])) {
			if (unlink($_REQUEST['deletefile'])) {
				$deletefilemessage = sprintf(_AM_DEBASER_BSUCCDEL, addslashes($_REQUEST['deletefile']));
			} else {
				$deletefilemessage = sprintf(_AM_DEBASER_BFAILDEL1, addslashes($_REQUEST['deletefile']));
			}
		} else {
			$deletefilemessage = sprintf(_AM_DEBASER_BFAILDEL2, addslashes($_REQUEST['deletefile']));
		}

		if (isset($_REQUEST['noalert'])) {
			echo '<b><font color="'.(($deletefilemessage{0} == 'F') ? '#ff0000' : '#008000').'">'.$deletefilemessage.'</font></b><hr />';
		} else {
			echo '<script type="text/javascript">alert("'.$deletefilemessage.'");</script>';
		}
	}

	if (isset($_REQUEST['filename'])) {

		if (!file_exists($_REQUEST['filename']) || !is_file($_REQUEST['filename'])) die(getid3_lib::iconv_fallback('ISO-8859-1', 'UTF-8', sprintf(_AM_DEBASER_BNOTEXIST, $_REQUEST['filename'])));

		$starttime = getmicrotime();

		$ThisFileInfo = $getID3->analyze($_REQUEST['filename']);
		$AutoGetHashes = (bool) ((@$ThisFileInfo['filesize'] > 0) && ($ThisFileInfo['filesize'] < (50 * 1048576))); // auto-get md5_data, md5_file, sha1_data, sha1_file if filesize < 50MB, and NOT zero (which may indicate a file>2GB)
		if ($AutoGetHashes) {
			$ThisFileInfo['md5_file']  = getid3_lib::md5_file($_REQUEST['filename']);
			$ThisFileInfo['sha1_file'] = getid3_lib::sha1_file($_REQUEST['filename']);
		}

		getid3_lib::CopyTagsToComments($ThisFileInfo);

		$listdirectory = dirname(getid3_lib::SafeStripSlashes($_REQUEST['filename']));
		$listdirectory = realpath($listdirectory); // get rid of /../../ references

		if (GETID3_OS_ISWINDOWS) $listdirectory = str_replace('\\', '/', $listdirectory.'/');

		if (strstr($_REQUEST['filename'], 'http://') || strstr($_REQUEST['filename'], 'ftp://')) echo _AM_DEBASER_BNOREMOTE;
		else echo _AM_DEBASER_BBROWSE.'<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.urlencode($listdirectory).'">'.getid3_lib::iconv_fallback('ISO-8859-1', 'UTF-8', $listdirectory).'</a><br />';

		echo table_var_dump($ThisFileInfo);
		$endtime = getmicrotime();
		echo sprintf(_AM_DEBASER_BFILESCAN, number_format($endtime - $starttime, 3));

	} else {

		$listdirectory = (isset($_REQUEST['listdirectory']) ? getid3_lib::SafeStripSlashes($_REQUEST['listdirectory']) : '../upload');
		$listdirectory = realpath($listdirectory); // get rid of /../../ references
		$currentfulldir = $listdirectory.'/';

		if (GETID3_OS_ISWINDOWS) {
			$currentfulldir = str_replace('\\', '/', $listdirectory.'/');
			$listdirectory = str_replace('\\', '/', $listdirectory.'/');
		}

		if ($handle = @opendir($listdirectory)) {
			$starttime = getmicrotime();
			$TotalScannedUnknownFiles = 0;
			$TotalScannedKnownFiles = 0;
			$TotalScannedPlaytimeFiles = 0;
			$TotalScannedBitrateFiles = 0;
			$TotalScannedFilesize = 0;
			$TotalScannedPlaytime = 0;
			$TotalScannedBitrate = 0;
			$FilesWithWarnings = 0;
			$FilesWithErrors = 0;

			while ($file = readdir($handle)) {
				$currentfilename = $listdirectory.'/'.$file;
				// allocate another 30 seconds to process this file - should go much
				// quicker than this unless intense processing (like bitrate histogram analysis) is enabled
				// works only with safe_mode = off
				if (!@ini_get('safe_mode')) set_time_limit(30);

				switch ($file) {
					case '..':
					$ParentDir = realpath($file.'/..').'/';

					if (GETID3_OS_ISWINDOWS) $ParentDir = str_replace('\\', '/', $ParentDir);

					$DirectoryContents[$currentfulldir]['dir'][$file]['filename'] = $ParentDir;
					continue 2;
					break;

					case '.':
					// ignore
					continue 2;
					break;
				}

			// symbolic-link-resolution enhancements by davidbullock״ech-center*com
			$TargetObject     = realpath($currentfilename);  // Find actual file path, resolve if it's a symbolic link
			$TargetObjectType = filetype($TargetObject);     // Check file type without examining extension

			if ($TargetObjectType == 'dir') {

				$DirectoryContents[$currentfulldir]['dir'][$file]['filename'] = $file;

			} elseif ($TargetObjectType == 'file') {

				$getID3->setOption(array('option_md5_data' => isset($_REQUEST['ShowMD5'])));
				$fileinformation = $getID3->analyze($currentfilename);

				getid3_lib::CopyTagsToComments($fileinformation);

				$TotalScannedFilesize += @$fileinformation['filesize'];

				if (isset($_REQUEST['ShowMD5'])) {
					$fileinformation['md5_file'] = md5($currentfilename);
					$fileinformation['md5_file']  = getid3_lib::md5_file($currentfilename);
				}

				if (!empty($fileinformation['fileformat'])) {
					$DirectoryContents[$currentfulldir]['known'][$file] = $fileinformation;
					$TotalScannedPlaytime += @$fileinformation['playtime_seconds'];
					$TotalScannedBitrate  += @$fileinformation['bitrate'];
					$TotalScannedKnownFiles++;
				} else {
					$DirectoryContents[$currentfulldir]['other'][$file] = $fileinformation;
					$DirectoryContents[$currentfulldir]['other'][$file]['playtime_string'] = '-';
					$TotalScannedUnknownFiles++;
				}

				if (isset($fileinformation['playtime_seconds']) && ($fileinformation['playtime_seconds'] > 0)) $TotalScannedPlaytimeFiles++;

				if (isset($fileinformation['bitrate']) && ($fileinformation['bitrate'] > 0)) $TotalScannedBitrateFiles++;

			}
		}
		$endtime = getmicrotime();
		closedir($handle);

		echo sprintf(_AM_DEBASER_BDIRSCAN, number_format($endtime - $starttime, 2));

		$columnsintable = 14;
		echo '<table class="browsetable" cellspacing="0" cellpadding="0" border="0">';

		echo '<tr bgcolor="#'.$getID3checkColor_Head.'"><th colspan="'.$columnsintable.'">Files in '.getid3_lib::iconv_fallback('ISO-8859-1', 'UTF-8', $currentfulldir).'</th></tr>';
		$rowcounter = 0;
		foreach ($DirectoryContents as $dirname => $val) {
			if (isset($DirectoryContents[$dirname]['dir']) && is_array($DirectoryContents[$dirname]['dir'])) {
				uksort($DirectoryContents[$dirname]['dir'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['dir'] as $filename => $fileinfo) {
					echo '<tr bgcolor="#'.(($rowcounter++ % 2) ? $getID3checkColor_DirectoryLight : $getID3checkColor_DirectoryDark).'">';
					if ($filename == '..') {
						echo '<td colspan="'.$columnsintable.'">';
						echo '<form action="'.$_SERVER['PHP_SELF'].'" method="get">';
						echo _AM_DEBASER_BPARDIR;
						echo '<input type="text" name="listdirectory" size="100" style="background-color: '.$getID3checkColor_DirectoryDark.';" value="';

						if (GETID3_OS_ISWINDOWS) echo htmlentities(str_replace('\\', '/', realpath($dirname.$filename)), ENT_QUOTES);
						else echo htmlentities(realpath($dirname.$filename), ENT_QUOTES);

						echo '"> <input type="submit" value="Go" />';
						echo '</form></td>';
					} else {
						echo '<td colspan="'.$columnsintable.'"><a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.urlencode($dirname.$filename).'"><b>'.FixTextFields($filename).'</b></a></td>';
					}
					echo '</tr>';
				}
			}

			echo '<tr bgcolor="#'.$getID3checkColor_Head.'">';
			echo '<th>'._AM_DEBASER_BFILENAME.'</th>';
			echo '<th align="right">'._AM_DEBASER_BFILESIZE.'</th>';
			echo '<th align="center">'._AM_DEBASER_BFORMAT.'</th>';
			echo '<th align="center">'._AM_DEBASER_BPLAYTIME.'</th>';
			echo '<th align="center">'._AM_DEBASER_BBITRATE.'</th>';
			echo '<th>'._AM_DEBASER_BARTIST.'</th>';
			echo '<th>'._AM_DEBASER_BTITLE.'</th>';
			if (isset($_REQUEST['ShowMD5'])) {
				echo '<th>MD5&nbsp;'._AM_DEBASER_BFILEFILE.' (<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.rawurlencode(isset($_REQUEST['listdirectory']) ? $_REQUEST['listdirectory'] : '.').'">'._AM_DEBASER_BDISABLE.'</a>)</th>';
				echo '<th>MD5&nbsp;Data (File) (<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.rawurlencode(isset($_REQUEST['listdirectory']) ? $_REQUEST['listdirectory'] : '.').'">'._AM_DEBASER_BDISABLE.'</a>)</th>';
				echo '<th>MD5&nbsp;Data (Source) (<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.rawurlencode(isset($_REQUEST['listdirectory']) ? $_REQUEST['listdirectory'] : '.').'">'._AM_DEBASER_BDISABLE.'</a>)</th>';
			} else {
				echo '<th colspan="3">MD5&nbsp;Data (<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.rawurlencode(isset($_REQUEST['listdirectory']) ? $_REQUEST['listdirectory'] : '.').'&amp;ShowMD5=1">'._AM_DEBASER_BENABLE.'</a>)</th>';
			}
			echo '<th>Tags</th>';
			echo '<th>'._AM_DEBASER_BERRWARN.'</th>';
			echo '<th>'._EDIT.'</th>';
			echo '<th>'._DELETE.'</th>';
			echo '</tr>';

			if (isset($DirectoryContents[$dirname]['known']) && is_array($DirectoryContents[$dirname]['known'])) {
				uksort($DirectoryContents[$dirname]['known'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['known'] as $filename => $fileinfo) {
					echo '<tr bgcolor="#'.(($rowcounter++ % 2) ? $getID3checkColor_FileDark : $getID3checkColor_FileLight).'">';
					echo '<td><a href="'.$_SERVER['PHP_SELF'].'?filename='.urlencode($dirname.$filename).'" title="'._AM_DEBASER_BVIEWDETAIL.'">'.FixTextFields(getid3_lib::SafeStripSlashes($filename)).'</a></td>';
					echo '<td align="right">'.number_format($fileinfo['filesize']).'</td>';
					echo '<td align="center">'.NiceDisplayFiletypeFormat($fileinfo).'</td>';
					echo '<td align="center">'.(isset($fileinfo['playtime_string']) ? $fileinfo['playtime_string'] : '-').'</td>';
					echo '<td align="center">'.(isset($fileinfo['bitrate']) ? BitrateText($fileinfo['bitrate'] / 1000, 0, ((@$fileinfo['audio']['bitrate_mode'] == 'vbr') ? true : false)) : '-').'</td>';
					echo '<td align="left">'.(isset($fileinfo['comments_html']['artist']) ? implode(' ', $fileinfo['comments_html']['artist']) : '').'</td>';
					echo '<td align="left">'.(isset($fileinfo['comments_html']['title']) ? implode('<br>', $fileinfo['comments_html']['title']) : '').'</td>';
					if (isset($_REQUEST['ShowMD5'])) {
						echo '<td align="left"><tt>'.(isset($fileinfo['md5_file'])        ? $fileinfo['md5_file']        : '&nbsp;').'</tt></td>';
						echo '<td align="left"><tt>'.(isset($fileinfo['md5_data'])        ? $fileinfo['md5_data']        : '&nbsp;').'</tt></td>';
						echo '<td align="left"><tt>'.(isset($fileinfo['md5_data_source']) ? $fileinfo['md5_data_source'] : '&nbsp;').'</tt></td>';
					} else {
						echo '<td align="center" colspan="3">-</td>';
					}
					echo '<td align="left">'.@implode(', ', array_keys($fileinfo['tags'])).'</td>';

					echo '<td align="left">';
					if (!empty($fileinfo['warning'])) {
						$FilesWithWarnings++;
						echo '<a href="#" onclick="alert(\''.FixTextFields(implode('\\n', $fileinfo['warning'])).'\'); return false;" title="'.FixTextFields(implode("\n", $fileinfo['warning'])).'">'._AM_DEBASER_BWARNING.'</a><br />';
					}
					if (!empty($fileinfo['error'])) {
						$FilesWithErrors++;
						echo '<a href="#" onclick="alert(\''.FixTextFields(implode('\\n', $fileinfo['error'])).'\'); return false;" title="'.FixTextFields(implode("\n", $fileinfo['error'])).'">'._AM_DEBASER_BERROR.'</a><br />';
					}
					echo '</td>';

					echo '<td align="center">&nbsp;';
					switch (@$fileinfo['fileformat']) {
						case 'mp3':
						case 'mp2':
						case 'mp1':
						case 'flac':
						case 'mpc':
						case 'real':
							echo '<a href="'.$writescriptfilename.'?Filename='.urlencode($dirname.$filename).'" title="'._EDIT.'">'.$imagearray['editimg'].'</a>';
							break;
						case 'ogg':
							switch (@$fileinfo['audio']['dataformat']) {
								case 'vorbis':
									echo '<a href="'.$writescriptfilename.'?Filename='.urlencode($dirname.$filename).'" title="'._EDIT.'">'.$imagearray['editimg'].'</a>';
									break;
							}
							break;
						default:
							break;
					}
					echo '</td>';
					echo '<td align="center">&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.urlencode($listdirectory).'&amp;deletefile='.urlencode($dirname.$filename).'" onclick="return confirm(\'Are you sure you want to delete '.addslashes(htmlentities($dirname.$filename)).'? \n(this action cannot be un-done)\');" title="Permanently delete '."\n".FixTextFields($filename)."\n".' from'."\n".' '.FixTextFields($dirname).'">'.$imagearray['deleteimg'].'</a></td>';
					echo '</tr>';
				}
			}

			if (isset($DirectoryContents[$dirname]['other']) && is_array($DirectoryContents[$dirname]['other'])) {
				uksort($DirectoryContents[$dirname]['other'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['other'] as $filename => $fileinfo) {
					echo '<tr bgcolor="#'.(($rowcounter++ % 2) ? $getID3checkColor_UnknownDark : $getID3checkColor_UnknownLight).'">';
					echo '<td><a href="'.$_SERVER['PHP_SELF'].'?filename='.urlencode($dirname.$filename).'"><i>'.$filename.'</i></a></td>';
					echo '<td align="right">'.(isset($fileinfo['filesize']) ? number_format($fileinfo['filesize']) : '-').'</td>';
					echo '<td align="center">'.NiceDisplayFiletypeFormat($fileinfo).'</td>';
					echo '<td align="center">'.(isset($fileinfo['playtime_string']) ? $fileinfo['playtime_string'] : '-').'</td>';
					echo '<td align="center">'.(isset($fileinfo['bitrate']) ? BitrateText($fileinfo['bitrate'] / 1000) : '-').'</td>';
					echo '<td align="left">&nbsp;</td>'; // Artist
					echo '<td align="left">&nbsp;</td>'; // Title
					echo '<td align="left" colspan="3">&nbsp;</td>'; // MD5_data
					echo '<td align="left">&nbsp;</td>'; // Tags

					echo '<td align="left">';
					if (!empty($fileinfo['warning'])) {
						$FilesWithWarnings++;
						echo '<a href="#" onclick="alert(\''.FixTextFields(implode('\\n', $fileinfo['warning'])).'\'); return false;" title="'.FixTextFields(implode("\n", $fileinfo['warning'])).'">'._AM_DEBASER_BWARNING.'</a><br />';
					}
					if (!empty($fileinfo['error'])) {
						if ($fileinfo['error'][0] != 'unable to determine file format') {
							$FilesWithErrors++;
							echo '<a href="#" onclick="alert(\''.FixTextFields(implode('\\n', $fileinfo['error'])).'\'); return false;" title="'.FixTextFields(implode("\n", $fileinfo['error'])).'">'._AM_DEBASER_BERROR.'</a><br />';
						}
					}
					echo '</td>';

					echo '<td align="left">&nbsp;</td>'; // Edit
					echo '<td align="center">&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?listdirectory='.urlencode($listdirectory).'&amp;deletefile='.urlencode($dirname.$filename).'" onclick="return confirm(\'Are you sure you want to delete '.addslashes($dirname.$filename).'? \n(this action cannot be un-done)\');" title="Permanently delete '.addslashes($dirname.$filename).'">'.$imagearray['deleteimg'].'</a></td>';
					echo '</tr>';
				}
			}

			echo '<tr bgcolor="#'.$getID3checkColor_Head.'">';
			echo '<td><b>'._AM_DEBASER_BAVERAGE.'</b></td>';
			echo '<td align="right">'.number_format($TotalScannedFilesize / max($TotalScannedKnownFiles, 1)).'</td>';
			echo '<td>&nbsp;</td>';
			echo '<td align="center">'.getid3_lib::PlaytimeString($TotalScannedPlaytime / max($TotalScannedPlaytimeFiles, 1)).'</td>';
			echo '<td align="center">'.BitrateText(round(($TotalScannedBitrate / 1000) / max($TotalScannedBitrateFiles, 1))).'</td>';
			echo '<td rowspan="2" colspan="'.($columnsintable - 5).'"><table class="browsetable" border="0" cellspacing="0" cellpadding="2"><tr><th align="right" style="font-weight:bold; color:#000; background-color:transparent">'._AM_DEBASER_BIDENTFILES.'</th><td align="left">'.number_format($TotalScannedKnownFiles).'</td><td>&nbsp;&nbsp;&nbsp;</td><th align="right" style="font-weight:bold; color:#000; background-color:transparent">'._AM_DEBASER_BERRORS.'</th><td align="left">'.number_format($FilesWithErrors).'</td></tr><tr><th align="right" style="font-weight:bold; color:#000; background-color:transparent">'._AM_DEBASER_BUNKNOWNFILES.'</th><td align="left">'.number_format($TotalScannedUnknownFiles).'</td><td>&nbsp;&nbsp;&nbsp;</td><th align="right" style="font-weight:bold; color:#000; background-color:transparent">'._AM_DEBASER_BWARNINGS.'</th><td align="left">'.number_format($FilesWithWarnings).'</td></tr></table>';
			echo '</tr>';
			echo '<tr bgcolor="#'.$getID3checkColor_Head.'">';
			echo '<td><b>'._AM_DEBASER_BTOTAL.'</b></td>';
			echo '<td align="right">'.number_format($TotalScannedFilesize).'</td>';
			echo '<td>&nbsp;</td>';
			echo '<td align="center">'.getid3_lib::PlaytimeString($TotalScannedPlaytime).'</td>';
			echo '<td>&nbsp;</td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo '<b>ERROR: Could not open directory: <u>'.$currentfulldir.'</u></b><br />';
	}
}
echo PoweredBygetID3();
xoops_cp_footer();

function RemoveAccents($string) {
	// Revised version by markstewardרotmail*com
	// Again revised by James Heinrich (19-June-2006)
	return strtr(
		strtr(
			$string,
			"\x8A\x8E\x9A\x9E\x9F\xC0\xC1\xC2\xC3\xC4\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xE0\xE1\xE2\xE3\xE4\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFF",
			'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'
		),
		array("\xDE" => 'TH', "\xFE" => 'th', "\xD0" => 'DH', "\xF0" => 'dh', "\xDF" => 'ss', "\x8C" => 'OE', "\x9C" => 'oe', "\xC6" => 'AE', "\xE6" => 'ae', "\xB5" => 'u'
		)
	);
}

function BitrateText($bitrate, $decimals=0, $vbr=false) {
	return number_format($bitrate, $decimals).' kbps</span>';
}

function FixTextFields($text) {
	$text = getid3_lib::SafeStripSlashes($text);
	$text = htmlentities($text, ENT_QUOTES);
	return $text;
}

function string_var_dump($variable) {
	ob_start();
	var_dump($variable);
	$dumpedvariable = ob_get_contents();
	ob_end_clean();
	return $dumpedvariable;
}

function table_var_dump($variable, $wrap_in_td=false) {
	$returnstring = '';
	switch (gettype($variable)) {
		case 'array':
			$returnstring .= ($wrap_in_td ? '<td>' : '');
			$returnstring .= '<table class="dump" cellspacing="0" cellpadding="2">';
			foreach ($variable as $key => $value) {
				$returnstring .= '<tr><td valign="top"><b>'.str_replace("\x00", ' ', $key).'</b></td>';
				$returnstring .= '<td valign="top">'.gettype($value);
				if (is_array($value)) {
					$returnstring .= '&nbsp;('.count($value).')';
				} elseif (is_string($value)) {
					$returnstring .= '&nbsp;('.strlen($value).')';
				}
				if (($key == 'data') && isset($variable['image_mime']) && isset($variable['dataoffset'])) {
					$imageinfo = array();
					$imagechunkcheck = getid3_lib::GetDataImageSize($value, $imageinfo);
					$DumpedImageSRC = (!empty($_REQUEST['filename']) ? $_REQUEST['filename'] : '.getid3').'.'.$variable['dataoffset'].'.'.getid3_lib::ImageTypesLookup($imagechunkcheck[2]);
					if ($tempimagefile = @fopen($DumpedImageSRC, 'wb')) {
						fwrite($tempimagefile, $value);
						fclose($tempimagefile);
					}
					$returnstring .= '</td><td><img src="'.$_SERVER['PHP_SELF'].'?showfile='.urlencode($DumpedImageSRC).'&md5='.md5_file($DumpedImageSRC).'" width="'.$imagechunkcheck[0].'" height="'.$imagechunkcheck[1].'" /></td></tr>';
				} else {
					$returnstring .= '</td>'.table_var_dump($value, true).'</tr>';
				}
			}
			$returnstring .= '</table>';
			$returnstring .= ($wrap_in_td ? '</td>' : '');
			break;

		case 'boolean':
			$returnstring .= ($wrap_in_td ? '<td class="dump_boolean">' : '').($variable ? 'TRUE' : 'FALSE').($wrap_in_td ? '</td>' : '');
			break;

		case 'integer':
			$returnstring .= ($wrap_in_td ? '<td class="dump_integer">' : '').$variable.($wrap_in_td ? '</td>' : '');
			break;

		case 'double':
		case 'float':
			$returnstring .= ($wrap_in_td ? '<td class="dump_double">' : '').$variable.($wrap_in_td ? '</td>' : '');
			break;

		case 'object':
		case 'null':
			$returnstring .= ($wrap_in_td ? '<td>' : '').string_var_dump($variable).($wrap_in_td ? '</td>' : '');
			break;

		case 'string':
			$variable = str_replace("\x00", ' ', $variable);
			$varlen = strlen($variable);
			for ($i = 0; $i < $varlen; $i++) {
				if (preg_match('/['."\x0A\x0D".' -;0-9A-Za-z]/', $variable{$i})) {
					$returnstring .= $variable{$i};
				} else {
					$returnstring .= '&#'.str_pad(ord($variable{$i}), 3, '0', STR_PAD_LEFT).';';
				}
			}
			$returnstring = ($wrap_in_td ? '<td class="dump_string">' : '').nl2br($returnstring).($wrap_in_td ? '</td>' : '');
			break;

		default:
			$imageinfo = array();
			$imagechunkcheck = getid3_lib::GetDataImageSize($variable, $imageinfo);
			if (($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)) {
				$returnstring .= ($wrap_in_td ? '<td>' : '');
				$returnstring .= '<table class="dump" cellspacing="0" cellpadding="2">';
				$returnstring .= '<tr><td><b>type</b></td><td>'.getid3_lib::ImageTypesLookup($imagechunkcheck[2]).'</td></tr>';
				$returnstring .= '<tr><td><b>width</b></td><td>'.number_format($imagechunkcheck[0]).' px</td></tr>';
				$returnstring .= '<tr><td><b>height</b></td><td>'.number_format($imagechunkcheck[1]).' px</td></tr>';
				$returnstring .= '<tr><td><b>size</b></td><td>'.number_format(strlen($variable)).' bytes</td></tr></table>';
				$returnstring .= ($wrap_in_td ? '</td>' : '');
			} else {
				$returnstring .= ($wrap_in_td ? '<td>' : '').nl2br(htmlspecialchars(str_replace("\x00", ' ', $variable))).($wrap_in_td ? '</td>' : '');
			}
			break;
	}
	return $returnstring;
}

	function NiceDisplayFiletypeFormat(&$fileinfo) {

		if (empty($fileinfo['fileformat'])) return '-';

		$output  = $fileinfo['fileformat'];
		if (empty($fileinfo['video']['dataformat']) && empty($fileinfo['audio']['dataformat'])) return $output;  // 'gif'

		if (empty($fileinfo['video']['dataformat']) && !empty($fileinfo['audio']['dataformat'])) {
			if ($fileinfo['fileformat'] == $fileinfo['audio']['dataformat']) return $output; // 'mp3'
			$output .= '.'.$fileinfo['audio']['dataformat']; // 'ogg.flac'
			return $output;
		}
		if (!empty($fileinfo['video']['dataformat']) && empty($fileinfo['audio']['dataformat'])) {
			if ($fileinfo['fileformat'] == $fileinfo['video']['dataformat']) return $output; // 'mpeg'
			$output .= '.'.$fileinfo['video']['dataformat']; // 'riff.avi'
			return $output;
		}
		if ($fileinfo['video']['dataformat'] == $fileinfo['audio']['dataformat']) {
			if ($fileinfo['fileformat'] == $fileinfo['video']['dataformat']) return $output; // 'real'
			$output .= '.'.$fileinfo['video']['dataformat']; // any examples?
			return $output;
		}
		$output .= '.'.$fileinfo['video']['dataformat'];
		$output .= '.'.$fileinfo['audio']['dataformat']; // asf.wmv.wma
		return $output;
	}

function MoreNaturalSort($ar1, $ar2) {
	if ($ar1 === $ar2) {
		return 0;
	}
	$len1     = strlen($ar1);
	$len2     = strlen($ar2);
	$shortest = min($len1, $len2);
	if (substr($ar1, 0, $shortest) === substr($ar2, 0, $shortest)) {
		// the shorter argument is the beginning of the longer one, like "str" and "string"
		if ($len1 < $len2) {
			return -1;
		} elseif ($len1 > $len2) {
			return 1;
		}
		return 0;
	}
	$ar1 = RemoveAccents(strtolower(trim($ar1)));
	$ar2 = RemoveAccents(strtolower(trim($ar2)));
	$translatearray = array('\''=>'', '"'=>'', '_'=>' ', '('=>'', ')'=>'', '-'=>' ', '  '=>' ', '.'=>'', ','=>'');
	foreach ($translatearray as $key => $val) {
		$ar1 = str_replace($key, $val, $ar1);
		$ar2 = str_replace($key, $val, $ar2);
	}

	if ($ar1 < $ar2) {
		return -1;
	} elseif ($ar1 > $ar2) {
		return 1;
	}
	return 0;
}

function PoweredBygetID3($string='<br /><hr noshade="noshade" /><div style="font-size: 8pt; font-face: sans-serif;">Powered by <a href="http://getid3.sourceforge.net" target="_blank"><b>getID3() v<!--GETID3VER--></b><br>http://getid3.sourceforge.net</a></div>') {
	return str_replace('<!--GETID3VER-->', GETID3_VERSION, $string);
}

	function AddStripSlashesArray($input, $addslashes=false) {
		if (is_array($input)) {
			$output = $input;
			foreach ($input as $key => $value) {
				$output[$key] = AddStripSlashesArray($input[$key]);
			}
			return $output;
		} elseif ($addslashes) {
			return addslashes($input);
		}
		return stripslashes($input);
	}

function UnifyMagicQuotes($turnon=false) {
	global $_GET, $_POST, $_COOKIE;

	if (get_magic_quotes_gpc() && !$turnon) {

		// magic_quotes_gpc is on and we want it off!
		$_GET    = AddStripSlashesArray($_GET,    true);
		$_POST   = AddStripSlashesArray($_POST,   true);
		$_COOKIE = AddStripSlashesArray($_COOKIE, true);

		unset($_REQUEST);
		$_REQUEST = array_merge_recursive($_GET, $_POST, $_COOKIE);

	} elseif (!get_magic_quotes_gpc() && $turnon) {

		// magic_quotes_gpc is off and we want it on (why??)
		$_GET    = AddStripSlashesArray($_GET,    true);
		$_POST   = AddStripSlashesArray($_POST,   true);
		$_COOKIE = AddStripSlashesArray($_COOKIE, true);

		unset($_REQUEST);
		$_REQUEST = array_merge_recursive($_GET, $_POST, $_COOKIE);

	}

	return true;
}

?>