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

    include 'admin_header.php';
    include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	include_once DEBASER_CLASS.'/debasertree.php';
	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
	include_once DEBASER_RINC.'/functions.php';

	// function for displaying debaser administration
	function debaseradmin() {

		global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

		$result = $xoopsDB->query("SELECT xfid FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 0");
		$toapprove = $xoopsDB->getRowsNum($result);
		$resultgenre = $xoopsDB->query("SELECT genreid FROM ".$xoopsDB->prefix('debaser_genre')."");
		$anycats = $xoopsDB->getRowsNum($resultgenre);
		$resultbroken = $xoopsDB->query("SELECT brokenid FROM ".$xoopsDB->prefix('debaser_broken')."");
		$anybroken = $xoopsDB->getRowsNum($resultbroken);

		if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
			if (function_exists('apache_get_modules')) {
				if (in_array('mod_security', apache_get_modules()) && !file_exists(DEBASER_ROOT.'/.htaccess') && !empty($xoopsModuleConfig['allowflashupload'])) echo _AM_DEBASER_MODSEC;
			}
		}

		echo '<table class="outer" width="100%" id="indexshowme"><tr>';

		if ($toapprove < 1) $approve = '<td class="odd" style="width:50%;"><strong>'._AM_DEBASER_TOAPPROVE.'</strong>&nbsp;<span style="color:#ff0000; font-weight:bold;">0</span></td>';
		else $approve = '<td class="odd" style="width:50%;"><strong>'._AM_DEBASER_TOAPPROVE.'</strong>&nbsp;<a href="index.php?op=approve" style="color:#ff0000; text-decoration:underline;">'.$toapprove.'</a></td>';

		if ($anybroken < 1) $tofix = '<td class="odd" style="width:50%;"><strong>'._AM_DEBASER_TOFIX.'</strong>&nbsp;<span style="color:#ff0000; font-weight:bold;">0</span></td>';
		else $tofix = '<td class="odd" style="width:50%;"><strong>'._AM_DEBASER_TOFIX.'</strong>&nbsp;<a href="maintenance.php?op=listbroken" style="color:#ff0000; text-decoration:underline;">'.$anybroken.'</a></td>';

		echo $approve.$tofix.'</tr></table><br />';
		debaser_adminMenu();
		echo '<br />';
		if ($anycats) {
			$catform = new XoopsThemeForm(_AM_DEBASER_SHOWSORT, 'genrelist', 'index.php');
			$genre_tray = new XoopsFormElementTray( _AM_DEBASER_GENRE, '' );
			$genre_tray->addElement( new XoopsFormHidden('op', 'showmpegs') );
			$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
			ob_start();
			$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', 0, 0, 'genrefrom');
			$genre_tray->addElement(new XoopsFormLabel('', ob_get_contents()));
			ob_end_clean();
			$genre_tray->addElement(new XoopsFormButton('', 'genrelistsubmit', _SUBMIT, 'submit'));
			$catform->addElement($genre_tray);
			$catform->display();
		}
	}

	// function for moving songs from one genre to another
	function movesongs() {

		global $xoopsDB;

		$resulta = $xoopsDB->query("SELECT xfid, FROM ".$xoopsDB->prefix('debaser_files')." WHERE genreid = ".intval($_POST['genrefrom'])."");
		$counted = $xoopsDB->getRowsNum($resulta);

		$resultb = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET genreid=".intval($_POST['genreto'])." WHERE genreid = ".intval($_POST['genrefrom'])."");

		$resultc = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total+".intval($counted)." WHERE genreid = ".intval($_POST['genreto'])."");

		$resultc = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total-".intval($counted)." WHERE genreid = ".intval($_POST['genrefrom'])."");

		if ($resultb) redirect_header('index.php', 2, _AM_DEBASER_MOVED);
		else redirect_header('index.php', 2, _AM_DEBASER_DBERROR);
	}

	// function for listing mpegs of a specific genre
	function showmpegs() {

		global $xoopsDB, $genrelist, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $imagearray, $xoTheme;

		if ($xoopsModuleConfig['uselame'] == 1) $uselame = 1;
		else $uselame = 0;

		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$thisgenreid = isset($_POST['genrefrom']) ? intval($_POST['genrefrom']) : intval($_GET['genrefrom']);

		$result = "SELECT xfid, filename, artist, title, genreid, approved, fileext, uid, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE genreid=".intval($thisgenreid)." AND approved = 1 ORDER BY artist ASC ";

		$file_array = $xoopsDB -> query($result, $xoopsModuleConfig['indexperpage'], $start);
		$file_num = $xoopsDB -> getRowsNum($xoopsDB->query($result));

		debaser_adminMenu();
		echo '<div id="loading">Processing... </div>';
		$moveform = new XoopsThemeForm(_AM_DEBASER_GENREMOVE, 'movesongs', 'index.php');
		$move_tray = new XoopsFormElementTray('', '');
		$move_tray->addElement( new XoopsFormHidden( 'op', 'movesongs' ) );
		$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
		ob_start();
		$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', $thisgenreid, 0, 'genrefrom');
		$move_tray->addElement(new XoopsFormLabel(_AM_DEBASER_GENREFROM, ob_get_contents()));
		ob_end_clean();
		$mytreechose2 = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
		ob_start();
		$mytreechose2->makeDebaserMySelBox('textcattitle', 'textcattitle', 0 , 0, 'genreto');
		$move_tray->addElement(new XoopsFormLabel(_AM_DEBASER_GENRETO, ob_get_contents()));
		ob_end_clean();
		$move_tray->addElement(new XoopsFormButton('', 'movesubmit', _SUBMIT, 'submit'));
		$moveform->addElement($move_tray);
		$moveform->display();

		if ($file_num > 0) {
			echo '<br /><form name="reencodeform" id="reencodeform" action="javascript:void(null);" method="post"><table width="100%" class="outer" cellspacing="1" cellpadding="0" align="center" id="listfiles"> ';
			while ($sqlfetch = $xoopsDB->fetchArray($file_array)) {

				if ($sqlfetch['haslofi'] != '' && $sqlfetch['haslofi'] != 0) {
					$haslofi = '<span style="font-weight:bold; color:#ff0000"> + LoFi</span>';
					$lofi = 1;
				} else {
					$haslofi = '';
					$lofi = 0;
				}

				echo '<tr>
				<td class="odd" width="70" align="center">'._AM_DEBASER_ID.' '.$sqlfetch['xfid'].'</td>
				<td class="even">'.$sqlfetch['artist'].' - '.$sqlfetch['title'].$haslofi.'<br /><div id="rewritewarn'.$sqlfetch['xfid'].'"></div></td>';

				if ($uselame == 1 && $sqlfetch['fileext'] == 'mp3') echo '<td class="even" style="width:60px;text-align:center"><div style="float:left" id="showlofiok'.$sqlfetch['xfid'].'"></div> <input title="'._AM_DEBASER_MAKELOFI.'" type="checkbox" name="reencode'.$sqlfetch['xfid'].'" id="reencode'.$sqlfetch['xfid'].'" value="'.$sqlfetch['xfid'].'" class="reencodeclass" style="float:right" /></td>';
				else echo '<td class="even" style="width:60px;text-align:center"></td>';

				echo '<td class="even" align="center" width="40"><a href="index.php?op=editmpegs&amp;mpegid='.$sqlfetch['xfid'].'">'.$imagearray['editimg'].'</a></td><td class="odd" align="center" width="40"><a href="index.php?op=deletesong&amp;mpegid='.$sqlfetch['xfid'].'&amp;delfile='.$sqlfetch['filename'].'&amp;uid='.$sqlfetch['uid'].'&amp;lofi='.$lofi.'&amp;genreid='.$sqlfetch['genreid'].'">'.$imagearray['deleteimg'].'</a></td></tr>';
			}
			echo '</table></form>';

				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$page = ($file_num > $xoopsModuleConfig['indexperpage']) ? _AM_DEBASER_MINDEX_PAGE : '';
	$pagenav = new XoopsPageNav($file_num, $xoopsModuleConfig['indexperpage'], $start, 'op=showmpegs&amp;genrefrom='.$thisgenreid.'&amp;start');
	echo "<div align='right' style='padding: 8px;'>" . $page . '' . $pagenav -> renderNav() . '</div>';

	$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function () { $("input.reencodeclass").click(function () { $("#showlofiok"+this.value).load("'.DEBASER_URL.'/ajaxed.php", { action : "transcode", id : this.value }); }); }); $(\'#loading\').ajaxStart(function() {
$(this).show(); }).ajaxStop(function() { $(this).hide(); });');
		}
		else {
			redirect_header('index.php', 2, _AM_DEBASER_NOSONGAVAIL);
		}
	}

	function editmpegs() {

		global $xoopsDB, $mpegid, $artist, $genrelist, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $xoopsUser, $xoTheme;

		$result = $xoopsDB->query("SELECT xfid, filename, title, artist, album, year, track, genreid, length, approved, bitrate, frequence, fileext, uid, linkcode FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid=".intval($_GET['mpegid'])."");

		list($xfid, $filename, $title, $artist, $album, $year, $track, $genreid, $length, $approved, $bitrate, $frequence, $fileext, $uid, $linkcode) = $xoopsDB->fetchRow($result);

		$result2 = $xoopsDB->query("SELECT mime_id FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mime_ext = ".$xoopsDB->quote($fileext)."");
		list($fileextid) = $xoopsDB->fetchRow($result2);

		debaser_adminMenu();
		$edform = new XoopsThemeForm(_AM_DEBASER_EDITMPEG, 'editmpegform', 'index.php');

		$mytreefileext = new debaserTree($xoopsDB->prefix('debaser_mimetypes'), 'mime_id', 'mime_pid', '', '', false);
		ob_start();
		$mytreefileext->makeDebaserMySelBox('mime_ext', 'mime_ext', $fileextid, 0, 'fileext');
		$formfileext = new XoopsFormLabel(_AM_DEBASER_FILETYPE, ob_get_contents());
		ob_end_clean();
		$edform->addElement($formfileext);
		$edform->addElement(new XoopsFormText(_AM_DEBASER_ARTIST, 'artist', 50, 50, $artist));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 50, $title));

		if ($linkcode != '') {
			if (strlen($linkcode) > 255) {
				$edform->addElement(new XoopsFormTextArea(_AM_DEBASER_TYPEOFLINK, 'linkcode', str_replace('\\', '', $linkcode), 10, 50));
			} else {
				$edform->addElement(new XoopsFormText(_AM_DEBASER_TYPEOFLINK, 'linkcode', 50, 255, $linkcode));
			}
		} else {
			$edform->addElement(new XoopsFormHidden('linkcode', ''));
		}

		$edform->addElement(new XoopsFormText(_AM_DEBASER_ALBUM, 'album', 50, 50, $album));
		$edform->addElement(new XoopsFormText(_AM_DEBASER_YEAR, 'year', 4, 4, $year));
		$edform->addElement(new XoopsFormHidden('olduid', $uid));
		$edform->addElement(new XoopsFormHidden('mpegid', $_GET['mpegid']));
		$edform->addElement(new XoopsFormHidden('filename', $filename));

	if ($xoopsModuleConfig['multilang'] == 0) {
		$result = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($xfid)." AND language = ".$xoopsDB->quote($xoopsConfig['language'])."");
		list($description) = $xoopsDB->fetchRow($result);
		$edform->addElement(get_debaserwysiwyg(_AM_DEBASER_COMMENT, 'description', $description, 15, 60));
		} else {
   		$langlist = XoopsLists::getLangList();
			$flaglist = '';
			foreach ($langlist as $flags) {
				$flaglist .= '<img onclick="toggleMe3(\'editmpegform\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
			}
			$edform->addElement(new XoopsFormLabel(_AM_DEBASER_LANGSELECT, $flaglist));

			foreach ($langlist as $key => $languagedescription) {
				$langresult = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = '$languagedescription' AND textfileid = ".intval($xfid)."");
				list ($description) = $xoopsDB->fetchRow($langresult);
				$languagedescription = get_debaserwysiwyg(_AM_DEBASER_DESCLANGUAGE.$languagedescription, $languagedescription .'_description', $description, '100%', '400px', 'hiddentext');
				$edform->addElement($languagedescription);
				unset($languagedescription);
			}
	}


	$edform->addElement(new XoopsFormText(_AM_DEBASER_TRACK, 'track', 3, 3, $track));

	$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsConfig['language']), 'AND textfileid = 0', false);
	ob_start();
	$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', $genreid, 0, 'genrefrom');
	$formgenre = new XoopsFormLabel(_AM_DEBASER_GENRE, ob_get_contents());
	ob_end_clean();
	$edform->addElement($formgenre);
	$edform->addElement(new XoopsFormText(_AM_DEBASER_LENGTH, 'length', 5, 5, $length));
	$edform->addElement(new XoopsFormText(_AM_DEBASER_BITRATE, 'bitrate', 3, 3, $bitrate));
	$edform->addElement(new XoopsFormText(_AM_DEBASER_FREQUENCY, 'frequence', 5, 5, $frequence));
	$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_APPROVE2, 'approved', 1, _YES, _NO));
	$edform->addElement(new XoopsFormHidden('op', 'saveeditmpegs'));
	$edform->addElement(new XoopsFormSelectUser(_AM_DEBASER_OWNER, 'uid', false, $uid, '1', false));
	$edform->addElement(new XoopsFormButton( '', '', _SUBMIT, 'submit' ));
	$edform->display();

		if ($xoopsModuleConfig['multilang'] == 1) {
			$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
			$multijs = 'window.onload = function() {';

		foreach ($langlist as $wotever) {
			$trnodedesc = $wotever.'_description';
			$multijs .= 'var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
			'.$trnodedesc.'.style.display="none";';
		}
		$multijs .= '}';
		$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
	}

	}
/* -- */

	/* function for approving mpegs */
	function approve() {

	global $xoopsDB, $genrelist, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $xoTheme;

	$filelist = array();
	$sql = "SELECT xfid, filename, artist, title, album, year, track, genreid, length, bitrate, frequence, approved, fileext, uid, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 0 ORDER BY artist ASC ";

	$result = $xoopsDB->query($sql);

	$hasitems = $xoopsDB -> getRowsNum($result);

		if ($hasitems > 0) {
			debaser_adminMenu();
			while (list($xfid, $filename, $artist, $title, $album, $year, $track, $genreid, $length, $bitrate, $frequence, $approved, $fileext, $uid, $haslofi) = $xoopsDB->fetchRow($result)) {

			$edform = new XoopsThemeForm(_AM_DEBASER_EDITMPEG, 'approveform', xoops_getenv('PHP_SELF'));

			$create_tray = new XoopsFormElementTray('ID '.$xfid, '');
			$create_tray->addElement( new XoopsFormHidden( 'op', 'deletesong' ) );
			$create_tray->addElement( new XoopsFormHidden( 'mpegid', $xfid) );
			$create_tray->addElement( new XoopsFormHidden( 'delfile', $filename) );
			$butt_delete = new XoopsFormButton( '', '', _DELETE, 'submit' );
			$butt_delete->setExtra( 'onclick="this.form.elements.op.value=\'deletesong\'"' );
			$create_tray->addElement( $butt_delete );
			$edform->addElement($create_tray);
			$save_tray = new XoopsFormElementTray( '', '' );
			$butt_save = new XoopsFormButton( '', '', _SUBMIT, 'submit' );
			$butt_save->setExtra( 'onclick="this.form.elements.op.value=\'saveapprove\'"' );
			$save_tray->addElement( $butt_save );

      		$file_ext = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')."");
      		$formfileext = new XoopsFormSelect(_AM_DEBASER_FILETYPE, 'fileext', $fileext, 1, false);
      		while(list($mime_ext) = $xoopsDB->fetchRow($file_ext)) {
      		$formfileext->addOption($mime_ext);
      		}
      		$edform->addElement($formfileext);
			$edform->addElement(new XoopsFormText(_AM_DEBASER_ARTIST, 'artist', 50, 50, $artist));
			$edform->addElement(new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 50, $title));
			$edform->addElement(new XoopsFormText(_AM_DEBASER_ALBUM, 'album', 50, 50, $album));
			$edform->addElement(new XoopsFormText(_AM_DEBASER_YEAR, 'year', 4, 4, $year));
			if ($xoopsModuleConfig['multilang'] == 0) {
			$result = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($xfid)." AND language = ".$xoopsDB->quote($xoopsConfig['language'])."");
			list($description) = $xoopsDB->fetchRow($result);
			$edform->addElement(get_debaserwysiwyg(_AM_DEBASER_COMMENT, 'description', $description, 15, 60));
			} else {
   		$langlist = XoopsLists::getLangList();
			$flaglist = '';
			foreach ($langlist as $flags) {
				$flaglist .= '<img onclick="toggleMe3(\'editmpegform\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
			}
			$edform->addElement(new XoopsFormLabel(_AM_DEBASER_LANGSELECT, $flaglist));

			foreach ($langlist as $key => $languagedescription) {
				$langresult = $xoopsDB->query("SELECT textfiletext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = '$languagedescription' AND textfileid = ".intval($xfid)."");
				list ($description) = $xoopsDB->fetchRow($langresult);
				$languagedescription = get_debaserwysiwyg(_AM_DEBASER_DESCLANGUAGE.$languagedescription, $languagedescription .'_description', $description, '100%', '400px', 'hiddentext');
				$edform->addElement($languagedescription);
				unset($languagedescription);
			}
			}
			$edform->addElement(new XoopsFormText(_AM_DEBASER_TRACK, 'track', 3, 3, $track));

			$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsConfig['language']), 'AND textfileid = 0', false);
			ob_start();
			$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', $genreid, 0, "genreid");
			$formgenre = new XoopsFormLabel(_AM_DEBASER_GENRE, ob_get_contents());
			ob_end_clean();
			$edform->addElement($formgenre);
      $edform->addElement(new XoopsFormText(_AM_DEBASER_LENGTH, 'length', 5, 5, $length));
			$edform->addElement(new XoopsFormText(_AM_DEBASER_BITRATE, 'bitrate', 3, 3, $bitrate));
			$edform->addElement(new XoopsFormText(_AM_DEBASER_FREQUENCY, 'frequence', 5, 5, $frequence));
			$edform->addElement(new XoopsFormRadioYN(_AM_DEBASER_APPROVE2, 'approved', $approved, _YES, _NO));
			$edform->addElement(new XoopsFormSelectUser(_AM_DEBASER_OWNER, 'uid', false, $uid, '1', false));
			$edform->addElement(new XoopsFormHidden('olduid', $uid));
			if ($xoopsModuleConfig['uselame'] == 1) {
				if ($haslofi == 1) {
					$yesorno = _YES;
					$edform->addElement(new XoopsFormHidden('lofi', 'lofi'));
				} else {
					$yesorno = _NO;
				}
				$edform->addElement(new XoopsFormLabel('lofi', $yesorno));
			} else {
				$edform->addElement(new XoopsFormHidden('lofi', 0));
			}
			$edform->addElement($save_tray);
			}

		$edform->display();
		if ($xoopsModuleConfig['multilang'] == 1) {
			$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
			$multijs = 'window.onload = function() {';

		foreach ($langlist as $wotever) {
			$trnodedesc = $wotever.'_description';
			$multijs .= 'var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
			'.$trnodedesc.'.style.display="none";';
		}
		$multijs .= '}';
		$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
	}
		}
		else {
		redirect_header('index.php', 2, _AM_DEBASER_NOAPPROVE);
		}
	}
/* -- */

/* function for saving edited mpegs */
	function saveeditmpegs() {

	global $xoopsDB, $xoopsModuleConfig, $module_id, $xoopsConfig, $xoopsUser;

	$olduid = intval($_POST['olduid']);
	$uid = intval($_POST['uid']);

	// Has the userid changed? Does the user is allowed to have his own upload directory? Then we move the file
	$oldgroups = $xoopsUser->getGroups($olduid);
	$newgroups = $xoopsUser->getGroups($uid);
	if ($olduid != $uid && @array_intersect($xoopsModuleConfig['owndir'], $newgroups) && @array_intersect($xoopsModuleConfig['owndir'], $oldgroups)) {

			if (!is_dir(DEBASER_RUP.'/user_'.$uid.'_')) {
				@mkdir(DEBASER_RUP.'/user_'.$uid.'_', 0777);
				@copy(DEBASER_RUP.'/index.html', DEBASER_RUP.'/user_'.$uid.'_/index.html');
				@chmod(DEBASER_RUP.'/user_'.$uid.'_/index.html', 0644);

				if ($xoopsModuleConfig['nohotlink'] == 1) {
					makehtaccess('/user_'.$uid.'_');
				} elseif ($xoopsModuleConfig['nohotlink'] == 0 && file_exists(DEBASER_RUP.'/user_'.$uid.'_/.htaccess')) {
					@unlink(DEBASER_RUP.'/user_'.$uid.'_/.htaccess');
				}
			}

		@copy(DEBASER_RUP.'/user_'.$olduid.'_/'.$_POST['filename'], DEBASER_RUP.'/user_'.$uid.'_/'.$_POST['filename']);
		@unlink(DEBASER_RUP.'/user_'.$olduid.'_/'.$_POST['filename']);
	}
	if ($olduid != $uid && !@array_intersect($xoopsModuleConfig['owndir'], $newgroups) && @array_intersect($xoopsModuleConfig['owndir'], $oldgroups)) {
		@copy(DEBASER_RUP.'/user_'.$olduid.'_/'.$_POST['filename'], DEBASER_RUP.'/'.$_POST['filename']);
		@unlink(DEBASER_RUP.'/user_'.$olduid.'_/'.$_POST['filename']);
	}
	if ($olduid != $uid && @array_intersect($xoopsModuleConfig['owndir'], $newgroups) && !@array_intersect($xoopsModuleConfig['owndir'], $oldgroups)) {

			if (!is_dir(DEBASER_RUP.'/user_'.$uid.'_')) {
				@mkdir(DEBASER_RUP.'/user_'.$uid.'_', 0777);
				@copy(DEBASER_RUP.'/index.html', DEBASER_RUP.'/user_'.$uid.'_/index.html');
				@chmod(DEBASER_RUP.'/user_'.$uid.'_/index.html', 0644);

				if ($xoopsModuleConfig['nohotlink'] == 1) {
					makehtaccess('/user_'.$uid.'_');
				} elseif ($xoopsModuleConfig['nohotlink'] == 0 && file_exists(DEBASER_RUP.'/user_'.$uid.'_/.htaccess')) {
					@unlink(DEBASER_RUP.'/user_'.$uid.'_/.htaccess');
				}
			}

		@copy(DEBASER_RUP.'/'.$_POST['filename'], DEBASER_RUP.'/user_'.$olduid.'_/'.$_POST['filename']);
		@unlink(DEBASER_RUP.'/'.$_POST['filename']);
	}

		$result2 = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mime_id = ".intval($_POST['fileext'])."");
		list($fileext) = $xoopsDB->fetchRow($result2);

	$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET title=".$xoopsDB->quoteString($_POST['title']).", artist=".$xoopsDB->quoteString($_POST['artist']).", album=".$xoopsDB->quoteString($_POST['album']).", year=".intval($_POST['year']).", track=".intval($_POST['track']).", genreid=".intval($_POST['genrefrom']).", length=".$xoopsDB->quoteString($_POST['length']).", approved=".intval($_POST['approved']).", bitrate=".intval($_POST['bitrate']).", frequence=".intval($_POST['frequence']).", fileext = ".$xoopsDB->quoteString($fileext).", uid = ".intval($uid).", language = ".$xoopsDB->quoteString($xoopsConfig['language']).", linkcode = ".$xoopsDB->quoteString($_POST['linkcode'])." WHERE xfid=".intval($_POST['mpegid'])." ");

	if ($xoopsModuleConfig['multilang'] == 0) {
		$sql = $xoopsDB->query("SELECT textfileid FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_POST['mpegid'])."");
		$hasitems = $xoopsDB->getRowsNum($sql);
		if ($hasitems == 1)
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quoteString($_POST['description'])." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quoteString($xoopsConfig['language'])."");
		else
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($_POST['mpegid']).", ".$xoopsDB->quoteString($_POST['description']).", ".$xoopsDB->quoteString($xoopsConfig['language']).")");
	} else {
			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
			$i = 0;
			foreach ($langlist as $langcontent) {
				$postdescription = $bb[$i].'_description';
				$language = $bb[$i];

				if ($_POST[$postdescription] != '') {
					$getlang = $xoopsDB->query("SELECT textfileid FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quote("$bb[$i]")."");
					$getlangs = $xoopsDB->getRowsNum($getlang);

					if ($getlangs == 1) {
					$result2 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quoteString($_POST[$postdescription]).", language = ".$xoopsDB->quoteString("$language")." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quoteString("$language")."");
					} else {
					$result3 = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($_POST['mpegid']).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");
					}
				}
				$i++;

				unset($postdescription);
				unset($language);
			}
	}

	redirect_header('index.php', 2, _AM_DEBASER_DBUPDATE);

	}
/* -- */

	// function for saving edited or formerly unapproved files
	function saveapprove() {

	global $xoopsDB, $xoopsModuleConfig, $xoopsConfig;

	if (isset($_POST['approved']) && $_POST['approved'] == 1) {
		$approved = 1;
		// Notification
		global $xoopsModule;
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['SONG_NAME'] = $_POST['artist']." - ".$_POST['title'];
		$tags['SONG_URL'] = DEBASER_URL. '/singlefile.php?id='.$_POST['mpegid'];
		$notification_handler->triggerEvent('global', 0, 'new_song', $tags);
	} else {
		$approved = 0;
	}

	if ($_POST['uid'] != $_POST['olduid']) {
		$member_handler = &xoops_gethandler('member');
		$newowner = &$member_handler->getUser($_POST['uid']);
		$oldowner = &$member_handler->getUser($_POST['olduid']);
		$member_handler->updateUserByField($newowner, 'posts', $newowner->getVar('posts') + 1);
		$member_handler->updateUserByField($oldowner, 'posts', $oldowner->getVar('posts') - 1);
		$olddir = getthedir($_POST['olduid']);
		$newdir = getthedir($_POST['uid']);
		if ($olddir == '' && $newdir == '') {
			// nothing to move
		} else {
			if (!is_dir(DEBASER_RUP.'/'.$newdir)) @mkdir(DEBASER_RUP.'/'.$newdir, 0777);

			if (isset($_POST['haslofi']) && $_POST['haslofi'] == 'haslofi') @rename(DEBASER_RUP.'/'.$olddir.'lofi_'.$_POST['delfile'], DEBASER_RUP.'/'.$newdir.'lofi_'.$_POST['delfile']);

			@rename(DEBASER_RUP.'/'.$olddir.$_POST['delfile'], DEBASER_RUP.'/'.$newdir.$_POST['delfile']);
		}
	}

	$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_files')." SET title = ".$xoopsDB->quoteString($_POST['title']).", artist = ".$xoopsDB->quoteString($_POST['artist']).", album = ".$xoopsDB->quoteString($_POST['album']).", year = ".intval($_POST['year']).", track = ".intval($_POST['track']).", genreid = ".intval($_POST['genreid']).", length = ".$xoopsDB->quoteString($_POST['length']).", bitrate = ".intval($_POST['bitrate']).", frequence = ".intval($_POST['frequence']).", approved = ".intval($approved).", fileext = ".$xoopsDB->quoteString($_POST['fileext']).", uid = ".intval($_POST['uid'])." WHERE xfid = ".intval($_POST['mpegid'])."");

	if ($xoopsModuleConfig['multilang'] == 0) {
		$sql = $xoopsDB->query("SELECT textfileid FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid = ".intval($_POST['mpegid'])."");
		$hasitems = $xoopsDB->getRowsNum($sql);
		if ($hasitems == 1)
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quoteString($_POST['description'])." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quoteString($xoopsConfig['language'])."");
		else
		$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($_POST['mpegid']).", ".$xoopsDB->quoteString($_POST['description']).", ".$xoopsDB->quoteString($xoopsConfig['language']).")");
	} else {
			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
			$i = 0;
			foreach ($langlist as $langcontent) {
				$postdescription = $bb[$i].'_description';
				$result1 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textfiletext = ".$xoopsDB->quoteString($_POST[$postdescription]).", language = ".$xoopsDB->quoteString("$bb[$i]")." WHERE textfileid = ".intval($_POST['mpegid'])." AND language = ".$xoopsDB->quoteString("$bb[$i]")."");

				if (!$result1)
					$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textfileid, textfiletext, language) VALUES (".intval($_POST['mpegid']).", ".$xoopsDB->quoteString($_POST[$postdescription]).", language = ".$xoopsDB->quoteString("$bb[$i]").")");

				$i++;
			}
	}

	redirect_header('index.php?op=approve', 2, _AM_DEBASER_DBUPDATE);
	}
	/* -- */


	/* function for deleting mp3 when confirmed */
	function deletesong($del=0) {

	global $xoopsDB, $xoopsModule, $member_handler;

		if (isset($_POST['del']) && $_POST['del'] == 1) {
		$userpath = getthedir($_POST['uid']);
		$sql = "DELETE FROM ".$xoopsDB->prefix('debaser_files')." WHERE xfid=".intval($_POST['mpegid'])."";
		$sql2 = "UPDATE ".$xoopsDB->prefix('debaser_genre')." SET total = total-1 WHERE genreid = ".intval($_POST['genreid'])."";
		$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix('debaser_text')." WHERE textfileid=".intval($_POST['mpegid'])."");

			if ($xoopsDB->query($sql) && $xoopsDB->query($sql2)) {
			@unlink(DEBASER_RUP.'/'.$userpath.'/'.$_POST['delfile']);
			if ($_POST['lofi'] == 1) @unlink(DEBASER_RUP.'/'.$userpath.'/lofi_'.$_POST['delfile']);
			xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['mpegid']);
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'song', $_POST['mpegid']);
			$member_handler = &xoops_gethandler('member');
			$poster = &$member_handler->getUser($_POST['uid']);
			$member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') - 1);
			redirect_header('index.php', 2, $_POST['delfile']._AM_DEBASER_DELETED);
			}
			else {
			redirect_header('index.php', 2, $_POST['delfile']._AM_DEBASER_NOTDELETED);
			}
		exit();
		}
		else {
		xoops_cp_header();
		echo "<h4>"._AM_DEBASER_FILEADMIN."</h4>";
		$delfile = isset($_GET['delfile']) ? $_GET['delfile'] : $_POST['delfile'];
		$mpegid = isset($_GET['mpegid']) ? $_GET['mpegid'] : $_POST['mpegid'];
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $_POST['uid'];
		$lofi = isset($_GET['lofi']) ? $_GET['lofi'] : $_POST['lofi'];
		$genreid = isset($_GET['genreid']) ? $_GET['genreid'] : $_POST['genreid'];
		xoops_confirm(array('delfile' => $delfile, 'genreid' => $genreid, 'lofi' => $lofi, 'mpegid' => $mpegid, 'uid' => $uid, 'del' => 1), 'index.php?op=deletesong', _AM_DEBASER_SUREDELETEFILE);
		xoops_cp_footer();
		}
	}
	/* -- */


	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'showmpegs':
		xoops_cp_header();
		showmpegs();
		xoops_cp_footer();
		break;

		case 'deletesong':
		deletesong();
		break;

		case 'editmpegs':
		xoops_cp_header();
		editmpegs();
		xoops_cp_footer();
		break;

		case 'saveeditmpegs':
		saveeditmpegs();
		break;

		case 'saveapprove':
		saveapprove();
		break;

		case 'movesongs':
		movesongs();
		break;

		case 'approve':
		xoops_cp_header();
		approve();
		xoops_cp_footer();
		break;

		case 'default':
		default:
		xoops_cp_header();
		debaseradmin();
		xoops_cp_footer();
		break;
	}

?>