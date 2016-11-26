<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
//                                                             //
// /demo/demo.write.php - part of getID3()                     //
// sample script for demonstrating writing ID3v1 and ID3v2     //
// tags for MP3, or Ogg comment tags for Ogg Vorbis            //
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
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$TaggingFormat = 'UTF-8';

	xoops_cp_header();
	debaser_adminMenu();

	require_once DEBASER_CLASS.'/getid3/getid3.php';
	// Initialize getID3 engine
	$getID3 = new getID3;
	$getID3->setOption(array('encoding'=>$TaggingFormat));

	getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);

	$browsescriptfilename = 'browse.php';

	function FixTextFields($text) {
		return htmlentities(getid3_lib::SafeStripSlashes($text), ENT_QUOTES);
	}

	$Filename = (isset($_REQUEST['Filename']) ? getid3_lib::SafeStripSlashes($_REQUEST['Filename']) : '');

	if (isset($_POST['WriteTags'])) {

	$TagFormatsToWrite = (isset($_POST['TagFormatsToWrite']) ? $_POST['TagFormatsToWrite'] : array());
	if (!empty($TagFormatsToWrite)) {
		echo _AM_DEBASER_WSTARTWRITE;

		$tagwriter = new getid3_writetags;
		$tagwriter->filename       = $Filename;
		$tagwriter->tagformats     = $TagFormatsToWrite;
		$tagwriter->overwrite_tags = true;
		$tagwriter->tag_encoding   = $TaggingFormat;
		if (!empty($_POST['remove_other_tags'])) {
			$tagwriter->remove_other_tags = true;
		}

		$commonkeysarray = array('Title', 'Artist', 'Album', 'Year', 'Comment');
		foreach ($commonkeysarray as $key) {
			if (!empty($_POST[$key])) {
				$TagData[strtolower($key)][] = getid3_lib::SafeStripSlashes($_POST[$key]);
			}
		}
		if (!empty($_POST['Genre'])) $TagData['genre'][] = getid3_lib::SafeStripSlashes($_POST['Genre']);

		if (!empty($_POST['GenreOther'])) $TagData['genre'][] = getid3_lib::SafeStripSlashes($_POST['GenreOther']);

		if (!empty($_POST['Track'])) $TagData['track'][] = getid3_lib::SafeStripSlashes($_POST['Track'].(!empty($_POST['TracksTotal']) ? '/'.$_POST['TracksTotal'] : ''));

		if (!empty($_FILES['userfile']['tmp_name'])) {
			if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)) {
				if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
					if ($fd = @fopen($_FILES['userfile']['tmp_name'], 'rb')) {
						$APICdata = fread($fd, filesize($_FILES['userfile']['tmp_name']));
						fclose ($fd);

						list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($_FILES['userfile']['tmp_name']);
						$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
						if (isset($imagetypes[$APIC_imageTypeID])) {

							$TagData['attached_picture'][0]['data']          = $APICdata;
							$TagData['attached_picture'][0]['picturetypeid'] = $_POST['APICpictureType'];
							$TagData['attached_picture'][0]['description']   = $_FILES['userfile']['name'];
							$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];

						} else {
							echo _AM_DEBASER_WINVALIDIMAGE;
						}
					} else {
						echo sprintf(_AM_DEBASER_WNOTOPEN, $_FILES['userfile']['tmp_name']);
					}
				} else {
					echo _AM_DEBASER_WNOUPLOAD;
				}
			} else {
				echo _AM_DEBASER_WEMBEDIMAGE;
			}
		} else {
		echo 	$_FILES['userfile']['tmp_name'];
		}

		$tagwriter->tag_data = $TagData;
		if ($tagwriter->WriteTags()) {
			echo _AM_DEBASER_WSUCCWROTE;
			if (!empty($tagwriter->warnings)) {
				echo 'There were some warnings:<blockquote style="background-color:#FFCC33; padding: 10px">'.implode('<br /><br />', $tagwriter->warnings).'</blockquote>';
			}
		} else {
			echo 'Failed to write tags!<blockquote style="background-color:#FF9999; padding: 10px">'.implode('<br /><br />', $tagwriter->errors).'</blockquote>';
		}

	} else {

		echo 'WARNING: no tag formats selected for writing - nothing written';

	}
	echo '<hr />';

}

	$getthepathback = $browsescriptfilename.'?listdirectory='.rawurlencode(realpath(dirname($Filename)));

	if (GETID3_OS_ISWINDOWS) $getthepathback = str_replace('%5C', '/', $getthepathback);

echo '<a href="'.$getthepathback.'">'._AM_DEBASER_WBROWSECURR.'</a><br />';
if (!empty($Filename)) {
	$edform = new XoopsThemeForm(_AM_DEBASER_WEDITWRITE, 'rewritetags', $_SERVER['PHP_SELF']);
	$edform->setExtra('enctype="multipart/form-data"');
	$edform->addElement(new XoopsFormLabel(_AM_DEBASER_BFILENAME, '<a href="'.$browsescriptfilename.'?filename='.rawurlencode($Filename).'">'.$Filename.'</a>'));
	$edform->addElement(new XoopsFormHidden('Filename', FixTextFields($Filename)));

	if (file_exists($Filename)) {

		// Initialize getID3 engine
		$getID3 = new getID3;
		$OldThisFileInfo = $getID3->analyze($Filename);
		getid3_lib::CopyTagsToComments($OldThisFileInfo);

		switch ($OldThisFileInfo['fileformat']) {
			case 'mp3':
			case 'mp2':
			case 'mp1':
				$ValidTagTypes = array('id3v1', 'id3v2.3', 'ape');
				break;

			case 'mpc':
				$ValidTagTypes = array('ape');
				break;

			case 'ogg':
				if (@$OldThisFileInfo['audio']['dataformat'] == 'flac') {
					// metaflac doesn't (yet) work with OggFLAC files
					$ValidTagTypes = array();
				} else {
					$ValidTagTypes = array('vorbiscomment');
				}
				break;

			case 'flac':
				$ValidTagTypes = array('metaflac');
				break;

			case 'real':
				$ValidTagTypes = array('real');
				break;

			default:
				$ValidTagTypes = array();
				break;
		}
		$edform->addElement(new XoopsFormText(_AM_DEBASER_TITLE, 'Title', 40, 255, FixTextFields(@implode(', ', @$OldThisFileInfo['comments']['title']))));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_ARTIST, 'Artist', 40, 255, FixTextFields(@implode(', ', @$OldThisFileInfo['comments']['artist']))));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_ALBUM, 'Album', 40, 255, FixTextFields(@implode(', ', @$OldThisFileInfo['comments']['album']))));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_YEAR, 'Year', 5, 4, FixTextFields(@implode(', ', @$OldThisFileInfo['comments']['year']))));

		$TracksTotal = '';
		$TrackNumber = '';
		if (!empty($OldThisFileInfo['comments']['tracknumber']) && is_array($OldThisFileInfo['comments']['tracknumber'])) {
			$RawTrackNumberArray = $OldThisFileInfo['comments']['tracknumber'];
		} elseif (!empty($OldThisFileInfo['comments']['track']) && is_array($OldThisFileInfo['comments']['track'])) {
			$RawTrackNumberArray = $OldThisFileInfo['comments']['track'];
		} else {
			$RawTrackNumberArray = array();
		}
		foreach ($RawTrackNumberArray as $key => $value) {
			if (strlen($value) > strlen($TrackNumber)) {
				// ID3v1 may store track as "3" but ID3v2/APE would store as "03/16"
				$TrackNumber = $value;
			}
		}
		if (strstr($TrackNumber, '/')) {
			list($TrackNumber, $TracksTotal) = explode('/', $TrackNumber);
		}
		$edform->addElement(new XoopsFormText(_AM_DEBASER_TRACK, 'Track', 3, 3, FixTextFields($TrackNumber)));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_WTRACKOF, 'TracksTotal', 3, 3, FixTextFields($TracksTotal)));

		$ArrayOfGenresTemp = getid3_id3v1::ArrayOfGenres();   // get the array of genres
		foreach ($ArrayOfGenresTemp as $key => $value) {      // change keys to match displayed value
			$ArrayOfGenres[$value] = $value;
		}
		unset($ArrayOfGenresTemp);                            // remove temporary array
		unset($ArrayOfGenres['Cover']);                       // take off these special cases
		unset($ArrayOfGenres['Remix']);
		unset($ArrayOfGenres['Unknown']);
		$ArrayOfGenres['']      = '- Unknown -';              // Add special cases back in with renamed key/value
		$ArrayOfGenres['Cover'] = '-Cover-';
		$ArrayOfGenres['Remix'] = '-Remix-';
		asort($ArrayOfGenres);                                // sort into alphabetical order

		$genreoptions = '';
		$AllGenresArray = (!empty($OldThisFileInfo['comments']['genre']) ? $OldThisFileInfo['comments']['genre'] : array());
		foreach ($ArrayOfGenres as $key => $value) {
			$genreoptions .= '<option value="'.$key.'"';
			if (in_array($key, $AllGenresArray)) {
				$genreoptions .= ' selected="selected"';
				unset($AllGenresArray[array_search($key, $AllGenresArray)]);
				sort($AllGenresArray);
			}
			$genreoptions .= '>'.$value.'</option>';
		}
		$edform->addElement(new XoopsFormLabel(_AM_DEBASER_WGENRE, '<select name="Genre">'.$genreoptions.'</select>'));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_WOTHERGENRE, 'GenreOther', 40, 255, FixTextFields(@$AllGenresArray[0])));

		$writethetags = '';
		foreach ($ValidTagTypes as $ValidTagType) {
			$writethetags .= '<input type="checkbox" name="TagFormatsToWrite[]" value="'.$ValidTagType.'"';
			if (count($ValidTagTypes) == 1) {
				$writethetags .= ' checked="checked"';
			} else {
				switch ($ValidTagType) {
					case 'id3v2.2':
					case 'id3v2.3':
					case 'id3v2.4':
						if (isset($OldThisFileInfo['tags']['id3v2'])) {
							$writethetags .= ' checked="checked"';
						}
						break;

					default:
						if (isset($OldThisFileInfo['tags'][$ValidTagType])) {
							$writethetags .= ' checked="checked"';
						}
						break;
				}
			}
			$writethetags .= ' />'.$ValidTagType.'<br />';
		}

		$edform->addElement(new XoopsFormLabel(_AM_DEBASER_WWRITETAGS, $writethetags));

		if (count($ValidTagTypes) > 1) {
		$remove_checkbox = new XoopsFormCheckBox('', 'remove_other_tags', 1);
	    $remove_checkbox->addOption(0, _AM_DEBASER_REMOVETAGS);
	    $edform->addElement($remove_checkbox);
		}

		$edform->addElement(new XoopsFormTextArea(_AM_DEBASER_COMMENT, 'Comment', isset($OldThisFileInfo['comments']['comment']) ? @implode("\n", $OldThisFileInfo['comments']['comment']) : '', 5, 50));
		$edform->addElement(new XoopsFormFile(_AM_DEBASER_WPICTURE, 'userfile', '1024000'));
		$picturetype = '';
		$picturetype .= '<select name="APICpictureType">';
		$APICtypes = getid3_id3v2::APICPictureTypeLookup('', true);
		$picselected = '';
		foreach ($APICtypes as $key => $value) {
			if (FixTextFields($key) == $OldThisFileInfo['id3v2']['APIC'][0]['picturetypeid']) $picselected = ' selected="selected"';
			else $picselected = '';
			$picturetype .= '<option value="'.FixTextFields($key).'" '.$picselected.'>'.FixTextFields($value).'</option>';
		}
		$picturetype .= '</select>';

		$edform->addElement(new XoopsFormLabel(_AM_DEBASER_WPICTURETYPE, $picturetype));
		$edform->addElement(new XoopsFormLabel('', '<input type="submit" name="WriteTags" value="'._SUBMIT.'" /> <input type="reset" value="Reset" />'));
	} else {

		echo '<br /><b>Error</b></td><td>'.FixTextFields($Filename).' does not exist';

	}
	$edform->display();

}
xoops_cp_footer();
?>