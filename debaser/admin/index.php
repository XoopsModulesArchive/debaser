<?php
// $Id: admin/index.php,v 0.50 2004/06/30 10:00:00 frankblack Exp $
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
	include_once '../include/functions.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/class/debasertree.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	include '../class/uploader.php';

	/* assigning get-variables to work with register_globals off */

	if (isset($_GET['op']) && $_GET['op'] == 'playermanager') {
	$op = 'playermanager';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'editplayer') {
	$op = 'editplayer';
	$playertype = $_GET['playertype'];
	}

	if (isset($_POST['op']) && $_POST['op'] == 'showmpegs') {
	$op = 'showmpegs';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'editmpegs') {
	$op = 'editmpegs';
	$mpegid = $_GET['mpegid'];
	}

	if (isset($_GET['op']) && $_GET['op'] == 'batchadd') {
	$op = 'batchadd';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'singleupload') {
	$op = 'singleupload';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'approve') {
	$op = 'approve';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'saveapprove') {
	$op = 'saveapprove';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'deleteplayer') {
	$op = 'deleteplayer';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'deletesong') {
	$op = 'deletesong';
	}
	
	if (isset($_POST['op']) && $_POST['op'] == 'deletesong') {
	$op = 'deletesong';
	}	

	if (isset($_POST['op']) && $_POST['op'] == 'changeplayer') {
	$op = 'changeplayer';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'newplayer') {
	$op = 'newplayer';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'saveeditmpegs') {
	$op = 'saveeditmpegs';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'movesongs') {
	$op = 'movesongs';
	}

/* -- */

/* function for displaying debaser administration */
	function debaseradmin() {

	global $xoopsDB;

	debaseradminMenu(0, _AM_DEBASER_SHOWFILES);

	echo '<div style="float:left; width:100%;">';
	echo '<table class="outer" width="100%">
			<tr>
			<td class="odd"><strong>'._AM_DEBASER_TOAPPROVE.'</strong>&nbsp;<a href="index.php?op=approve" style="color:#ff0000; text-decoration:underline;">';

	$sql = "SELECT xfid from ".$xoopsDB->prefix('debaser_files')." WHERE approved = 0";
	$result = $xoopsDB->query($sql);
	$toapprove = $xoopsDB->getRowsNum($result);

	echo ''.$toapprove.'</a></td></tr></table><br />';

	$catform = new XoopsThemeForm(_AM_DEBASER_SHOWSORT, "genrelist", "index.php");
	$genre_tray = new XoopsFormElementTray( _AM_DEBASER_GENRE, '' );
	$genre_tray->addElement( new XoopsFormHidden('op', 'showmpegs') );
	$mytreechose = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid');
	ob_start();
	$mytreechose->debaserSelBox("genretitle", "genretitle", 0, 0, "genrefrom");
	$genre_tray->addElement(new XoopsFormLabel('', ob_get_contents()));
	ob_end_clean();
	$genre_tray->addElement(new XoopsFormButton('', 'genrelistsubmit', _AM_DEBASER_GO, 'submit'));
	$catform->addElement($genre_tray);
	$catform->display();

	echo '</div>';

	}
/* -- */



/* function for moving songs from one genre to another */
	function movesongs() {

	global $xoopsDB;

	$sqla = "SELECT genretitle FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid = ".intval($_POST['genreto'])."";
	$resulta = $xoopsDB->query($sqla);
	list($togenre) = $xoopsDB->fetchRow($resulta);

	$sqlb = "SELECT genretitle FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid = ".intval($_POST['genrefrom'])."";
	$resultb = $xoopsDB->query($sqlb);
	list($fromgenre) = $xoopsDB->fetchRow($resultb);


	$sql = "UPDATE ".$xoopsDB->prefix('debaser_files')." SET genre=".$xoopsDB->quoteString($togenre)." WHERE genre=".$xoopsDB->quoteString($fromgenre)." ";
	$result = $xoopsDB->query($sql);

		if ($result) {
		redirect_header('index.php', 2, _AM_DEBASER_MOVED);
	 	}
		else {
		echo "hatt nicht geklappt";
		}
	}
/* -- */

/* function for listing mpegs of a specific genre */
	function showmpegs() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';
	if (!isset($xoopsTpl)) {
		$xoopsTpl = new XoopsTpl();
	}

	global $xoopsDB, $genrelist, $xoopsModule;

	$filelist = array();
	$sql = "SELECT d.xfid, d.filename, d.artist, d.title, d.genre, d.approved, t.genreid, t.genretitle FROM ".$xoopsDB->prefix('debaser_files')." d, ".$xoopsDB->prefix('debaser_genre')." t WHERE t.genreid=".intval($_POST['genrefrom'])." AND t.genretitle=d.genre AND d.approved = 1 ORDER BY d.artist ASC ";

	$result = $xoopsDB->query($sql);

	$hasitems = $xoopsDB -> getRowsNum($result);

		if ($hasitems > 0) {

			while ($sqlfetch = $xoopsDB->fetchArray($result)) {
			$filelist['id'] = $sqlfetch["xfid"];
			$filelist['filename'] = $sqlfetch["filename"];
			$filelist['artist'] = $sqlfetch["artist"];
			$filelist['title'] = $sqlfetch["title"];
			$filelist['genretitle'] = $sqlfetch["genre"];
			$xoopsTpl->append('filelist', $filelist);
			}
		}
		else {
		$xoopsTpl->assign('noavailsong', true);
		}

	$moveform = new XoopsThemeForm(_AM_DEBASER_GENREMOVE, "movesongs", "index.php");
	$move_tray = new XoopsFormElementTray( '', '' );
	$move_tray->addElement( new XoopsFormHidden( 'op', 'movesongs' ) );
	$mytreechose = new debaserTree($xoopsDB->prefix("debaser_genre"), "genreid", "subgenreid");
	ob_start();
	$mytreechose->debaserSelBox("genretitle", "genretitle", $_POST['genrefrom'], 0, "genrefrom");
	$move_tray->addElement(new XoopsFormLabel(_AM_DEBASER_GENREFROM, ob_get_contents()));
	ob_end_clean();
	$mytreechose2 = new debaserTree($xoopsDB->prefix("debaser_genre"), "genreid", "subgenreid");
	ob_start();
	$mytreechose2->debaserSelBox("genretitle", "genretitle", 0 , 0, "genreto");
	$move_tray->addElement(new XoopsFormLabel(_AM_DEBASER_GENRETO, ob_get_contents()));
	ob_end_clean();
	$move_tray->addElement(new XoopsFormButton('', 'movesubmit', _SUBMIT, 'submit'));
	$moveform->addElement($move_tray);
	$xoopsTpl->assign('movesongs', $moveform->render());

	$xoopsTpl->assign('adminmenu', debaseradminMenu(0, _AM_DEBASER_SHOWFILES.' : '._AM_DEBASER_GENRE.' '.$filelist['genretitle']));
	$xoopsTpl->display( 'db:debaser_amshowmpegs.html' );
	}
/* -- */

/* function for editing mpegs */
	function editmpegs() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if ( !isset($xoopsTpl) ) {
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $mpegid, $artist, $genrelist, $xoopsModule, $xoopsModuleConfig;

	$sql =
	"SELECT d.xfid, d.filename, d.title, d.artist, d.album, d.year, d.addinfo, d.track, d.genre, d.length, d.link, d.approved, d.bitrate, d.frequence, d.fileext, d.weight, t.genreid, t.genretitle
	FROM ".$xoopsDB->prefix('debaser_files')." d, ".$xoopsDB->prefix('debaser_genre')." t
	WHERE t.genretitle=d.genre AND xfid=".intval($mpegid)."";

	$result = $xoopsDB->query($sql);

	list($xfid, $filename, $title, $artist, $album, $year, $addinfo, $track, $genre, $length, $link, $approved, $bitrate, $frequence, $fileext, $weight, $genreid, $genretitle) = $xoopsDB->fetchRow($result);

	$edform = new XoopsThemeForm(_AM_DEBASER_EDITMPEG, 'editmpegform', xoops_getenv('PHP_SELF'));

	$member_handler = & xoops_gethandler('member');
	$group_list = & $member_handler -> getGroupList();
	$gperm_handler = & xoops_gethandler('groupperm');
	$groups = $gperm_handler -> getGroupIds('DebaserFilePerm', $xfid, $xoopsModule -> getVar('mid'));
	$groups = $groups;

		if ($xoopsModuleConfig['usefileperm'] == 1) {
		$edform -> addElement(new XoopsFormSelectGroup(_AM_DEBASER_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));
		}	
	
	$create_tray = new XoopsFormElementTray( '', '' );
	$create_tray->addElement( new XoopsFormHidden( 'op', 'deletesong' ) );
	$create_tray->addElement( new XoopsFormHidden( 'mpegid', $xfid) );
	$create_tray->addElement( new XoopsFormHidden( 'delfile', $filename) );
	$butt_play = new XoopsFormButton( '', '', _AM_DEBASER_PLAY, 'button' );
	$butt_play->setExtra( 'onclick="javascript:openWithSelfMain(\''.XOOPS_URL.'/modules/debaser/player.php?id='.$xfid.'\',\'player\',10,10)"' );
	$create_tray->addElement( $butt_play );
	$butt_delete = new XoopsFormButton( '', '', _DELETE, 'submit' );
	$butt_delete->setExtra( 'onclick="this.form.elements.op.value=\'deletesong\'"' );
	$create_tray->addElement( $butt_delete );
	$save_tray = new XoopsFormElementTray( '', '' );
	$butt_save = new XoopsFormButton( '', '', _SUBMIT, 'submit' );
	$butt_save->setExtra( 'onclick="this.form.elements.op.value=\'saveeditmpegs\'"' );
	$save_tray->addElement( $butt_save );
	$mytreefileext = new debaserTree($xoopsDB->prefix('debaser_mimetypes'), "mime_ext", "mime_ext");
	ob_start();
	$mytreefileext->debaserSelBox("mime_ext", "mime_ext", $fileext, 0, "fileext");
	$formfileext = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
	ob_end_clean();
	$formlink = new XoopsFormText(_MD_DEBASER_EXTLINK, 'link', 50, 150, $link);
	$formartist = new XoopsFormText(_AM_DEBASER_ARTIST, 'artist', 50, 50, $artist);
	$formtitle = new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 50, $title);
	$formalbum = new XoopsFormText(_AM_DEBASER_ALBUM, 'album', 50, 50, $album);
	$formyear = new XoopsFormText(_AM_DEBASER_YEAR, 'year', 4, 4, $year);
	$formcomment = new XoopsFormDhtmlTextArea(_AM_DEBASER_COMMENT, 'addinfo', $addinfo, 10, 50);
	$formtrack = new XoopsFormText(_AM_DEBASER_TRACK, 'track', 3, 3, $track);

	$mytreechose = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid');
	ob_start();
	$mytreechose->debaserSelBox("genretitle", "genretitle", $genreid, 0, "genrefrom");
	$formgenre = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
	ob_end_clean();
	$formlength = new XoopsFormText(_AM_DEBASER_LENGTH, 'length', 5, 5, $length);
	$formbitrate = new XoopsFormText(_MD_DEBASER_BITRATE, 'bitrate', 3, 3, $bitrate);
	$formfrequence = new XoopsFormText(_MD_DEBASER_FREQUENCY, 'frequence', 5, 5, $frequence);
	$formweight = new XoopsFormText(_AM_DEBASER_WEIGHT, 'weight', 4, 4, $weight);
	$formapproved = new XoopsFormRadioYN(_AM_DEBASER_APPROVE2, 'approved', 1, _YES, _NO);
	$edform->addElement($create_tray);
	$edform->addElement($formfileext);
	$edform->addElement($formlink);
	$edform->addElement($formartist);
	$edform->addElement($formtitle);
	$edform->addElement($formalbum);
	$edform->addElement($formyear);
	$edform->addElement($formcomment);
	$edform->addElement($formgenre);
	$edform->addElement($formtrack);
	$edform->addElement($formlength);
	$edform->addElement($formbitrate);
	$edform->addElement($formfrequence);
	$edform->addElement($formweight);
	$edform->addElement($formapproved);
	$edform->addElement($save_tray);
	$xoopsTpl->assign('editmpeg', $edform->render());
	$xoopsTpl->assign('adminmenu', debaseradminMenu(0, _AM_DEBASER_EDITMPEG.' : '.$artist.' : '.$title));
	$xoopsTpl->display('db:debaser_ameditmpegs.html');
	}
/* -- */

/* function for approving mpegs */
	function approve() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if (!isset($xoopsTpl)) {
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $genrelist, $xoopsModule, $xoopsModuleConfig;

	$filelist = array();
	$sql = "
	SELECT
	d.xfid, d.filename, d.artist, d.title, d.album, d.year, d.addinfo, d.track, d.genre, d.length, d.link, d.bitrate, d.frequence, d.approved, d.fileext, d.weight, t.genreid, t.genretitle
	FROM ".$xoopsDB->prefix('debaser_files')." d, ".$xoopsDB->prefix('debaser_genre')." t
	WHERE t.genretitle=d.genre AND d.approved = 0
	ORDER BY d.artist ASC ";

	$result = $xoopsDB->query($sql);

	$hasitems = $xoopsDB -> getRowsNum($result);

		if ($hasitems > 0) {

			while (list($xfid, $filename, $artist, $title, $album, $year, $addinfo, $track, $genre, $length, $link, $bitrate, $frequence, $approved, $fileext, $weight, $genreid, $genretitle) = $xoopsDB->fetchRow($result)) {

			$edform = new XoopsThemeForm(_AM_DEBASER_EDITMPEG, 'approveform', xoops_getenv('PHP_SELF'));
			
			$member_handler = & xoops_gethandler('member');
			$group_list = & $member_handler -> getGroupList();
			$gperm_handler = & xoops_gethandler('groupperm');
			$groups = $gperm_handler -> getGroupIds('DebaserFilePerm', $xfid, $xoopsModule -> getVar('mid'));
			$groups = $groups;

				if ($xoopsModuleConfig['usefileperm'] == 1) {
				$edform -> addElement(new XoopsFormSelectGroup(_AM_DEBASER_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));
				}
			
			$create_tray = new XoopsFormElementTray( '', '' );
			$create_tray->addElement( new XoopsFormHidden( 'op', 'deletesong' ) );
			$create_tray->addElement( new XoopsFormHidden( 'mpegid', $xfid) );
			$create_tray->addElement( new XoopsFormHidden( 'delfile', $filename) );
			$butt_play = new XoopsFormButton( '', '', _AM_DEBASER_PLAY, 'button' );
			$butt_play->setExtra( 'onclick="javascript:openWithSelfMain(\''.XOOPS_URL.'/modules/debaser/player.php?id='.$xfid.'\',\'player\',10,10)"' );
    		$create_tray->addElement( $butt_play );
			$butt_delete = new XoopsFormButton( '', '', _DELETE, 'submit' );
			$butt_delete->setExtra( 'onclick="this.form.elements.op.value=\'deletesong\'"' );
			$create_tray->addElement( $butt_delete );
			$save_tray = new XoopsFormElementTray( '', '' );
			$butt_save = new XoopsFormButton( '', '', _SUBMIT, 'submit' );
			$butt_save->setExtra( 'onclick="this.form.elements.op.value=\'saveapprove\'"' );
			$save_tray->addElement( $butt_save );

			$mytreefileext = new debaserTree($xoopsDB->prefix("debaser_mimetypes"), 'mime_ext', 'mime_ext');
			ob_start();
			$mytreefileext->debaserSelBox("mime_ext", "mime_ext", $fileext, 0, "fileext");
			$formfileext = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
			ob_end_clean();	
		
			$formlink = new XoopsFormText(_MD_DEBASER_EXTLINK, 'link', 50, 150, $link);
			$formartist = new XoopsFormText(_AM_DEBASER_ARTIST, 'artist', 50, 50, $artist);
			$formtitle = new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 50, $title);
			$formalbum = new XoopsFormText(_AM_DEBASER_ALBUM, 'album', 50, 50, $album);
			$formyear = new XoopsFormText(_AM_DEBASER_YEAR, 'year', 4, 4, $year);
			$formcomment = new XoopsFormDhtmlTextArea(_AM_DEBASER_COMMENT, 'addinfo', $addinfo, 10, 50);
			$formtrack = new XoopsFormText(_AM_DEBASER_TRACK, 'track', 3, 3, $track);

			$mytreechose = new debaserTree($xoopsDB->prefix("debaser_genre"), 'genreid', 'subgenreid');
			ob_start();
			$mytreechose->debaserSelBox("genretitle", "genretitle", $genreid, 0, "genrefrom");
			$formgenre = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
			ob_end_clean();
			$formlength = new XoopsFormText(_AM_DEBASER_LENGTH, 'length', 5, 5, $length);
			$formbitrate = new XoopsFormText(_MD_DEBASER_BITRATE, 'bitrate', 3, 3, $bitrate);
			$formfrequence = new XoopsFormText(_MD_DEBASER_FREQUENCY, 'frequence', 5, 5, $frequence);
			$formweight = new XoopsFormText(_AM_DEBASER_WEIGHT, 'weight', 4, 4, $weight);
			//$formweight->setDescription(_AM_DEBASER_WEIGHT_DSC);
			$formapproved = new XoopsFormRadioYN(_AM_DEBASER_APPROVE2, 'approved', $approved, _YES, _NO);
			$edform->addElement($create_tray);

			$edform->addElement($formfileext);
				
			$edform->addElement($formlink);
			$edform->addElement($formartist);
			$edform->addElement($formtitle);
			$edform->addElement($formalbum);
			$edform->addElement($formyear);
			$edform->addElement($formcomment);
			$edform->addElement($formgenre);
			$edform->addElement($formtrack);
			$edform->addElement($formlength);
			$edform->addElement($formbitrate);
			$edform->addElement($formfrequence);
			$edform->addElement($formweight);
			$edform->addElement($formapproved);
			$edform->addElement($save_tray);
			$xoopsTpl->append('filelist', $edform->render());
			}

		$xoopsTpl->assign('adminmenu', debaseradminMenu(6, _AM_DEBASER_APPROVE));
		$xoopsTpl->display('db:debaser_amapprove.html');
		}
		else {
		redirect_header('index.php',1,_AM_DEBASER_NOAPPROVE);
		}
	}
/* -- */

/* function for saving edited mpegs */
	function saveeditmpegs() {

	global $xoopsDB, $xoopsModuleConfig;
	$groups = isset($_POST['groups']) ? $_POST['groups'] : array();
	$sqlb = "SELECT genretitle FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid = ".intval($_POST['genrefrom'])."";
	$resultb = $xoopsDB->query($sqlb);
	list($fromgenre) = $xoopsDB->fetchRow($resultb);

	$sql = "
	UPDATE ".$xoopsDB->prefix('debaser_files')."
	SET
		title=".$xoopsDB->quoteString($_POST['title']).",
		artist=".$xoopsDB->quoteString($_POST['artist']).",
		album=".$xoopsDB->quoteString($_POST['album']).",
		year=".intval($_POST['year']).",
		addinfo=".$xoopsDB->quoteString($_POST['addinfo']).",
		track=".intval($_POST['track']).",
		genre=".$xoopsDB->quoteString($fromgenre).",
		length=".$xoopsDB->quoteString($_POST['length']).",
		link=".$xoopsDB->quoteString($_POST['link']).",
		approved=".intval($_POST['approved']).",
		bitrate=".intval($_POST['bitrate']).",
		frequence=".intval($_POST['frequence']).",
		fileext = ".$xoopsDB->quoteString($_POST['fileext']).",
		weight = ".intval($_POST['weight'])."
	WHERE xfid=".intval($_POST['mpegid'])." ";

	$result = $xoopsDB->query($sql);

	if ($xoopsModuleConfig['usefileperm'] == 1) {
	debaser_save_Perm($groups, $_POST['mpegid'], 'DebaserFilePerm');
	}
	redirect_header('index.php',1,_AM_DEBASER_DBUPDATE);

	}
/* -- */

	/* function for saving edited mpegs */
	function saveapprove() {

	global $xoopsDB, $xoopsModuleConfig;
	$groups = isset($_POST['groups']) ? $_POST['groups'] : array();
	
		if (isset($_POST['approved']) && $_POST['approved'] == 1) {
		$approved = 1;
		// Notification
		global $xoopsModule;
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$tags['SONG_NAME'] = $_POST['artist']." - ".$_POST['title'];
		$tags['SONG_URL'] = XOOPS_URL. '/modules/'.$xoopsModule->getVar('dirname').'/singlefile.php?id='.$_POST['mpegid'];
		$notification_handler->triggerEvent('global', 0, 'new_song', $tags);
		}
		else {
		$approved = 0;
		}

	$sqlb = "SELECT genretitle FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid = ".intval($_POST['genrefrom'])."";
	$resultb = $xoopsDB->query($sqlb);
	list($fromgenre) = $xoopsDB->fetchRow($resultb);

	$sql = "
	UPDATE ".$xoopsDB->prefix('debaser_files')."
	SET
		title=".$xoopsDB->quoteString($_POST['title']).",
		artist=".$xoopsDB->quoteString($_POST['artist']).",
		album=".$xoopsDB->quoteString($_POST['album']).",
		year=".intval($_POST['year']).",
		addinfo=".$xoopsDB->quoteString($_POST['addinfo']).",
		track=".intval($_POST['track']).",
		genre=".$xoopsDB->quoteString($fromgenre).",
		length=".$xoopsDB->quoteString($_POST['length']).",
		bitrate=".intval($_POST['bitrate']).",
		frequence=".intval($_POST['frequence']).",
		link=".$xoopsDB->quoteString($_POST['link']).",
		approved=".intval($approved).",
		fileext=".$xoopsDB->quoteString($_POST['fileext']).",
		weight = ".intval($_POST['weight'])."
	WHERE xfid=".intval($_POST['mpegid'])." ";

	$result = $xoopsDB->query($sql);

	if ($xoopsModuleConfig['usefileperm'] == 1) {
	debaser_save_Perm($groups, $_POST['mpegid'], 'DebaserFilePerm');
	}
	redirect_header('index.php?op=approve',1,_AM_DEBASER_DBUPDATE);
	}
	/* -- */

/* function for displaying available players */
	function playermanager() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if (!isset($xoopsTpl)) {
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $genrelist;
	$sql = 'SELECT name FROM '.$xoopsDB->prefix('debaser_player').' ORDER BY name';
	$result = $xoopsDB->query($sql);

		while (list($player) = $xoopsDB->fetchRow($result)) {
		$xoopsTpl->append('player', $player);
		}

	$nuform = new XoopsThemeForm(_AM_DEBASER_NEWPLAYER, "newplayerform", "index.php");
	$formplayername = new XoopsFormText(_AM_DEBASER_NAME, "newplayername", 50, 50);
	$formplayercode = new XoopsFormTextArea (_AM_DEBASER_CODE, 'newplayer', '', 15, 30);
	$formplayerheight = new XoopsFormText (_AM_DEBASER_HEIGHT, 'playerheight', 4, 4);
	$formplayerwidth = new XoopsFormText(_AM_DEBASER_WIDTH, 'playerwidth', 4, 4);
	$formplayerautostart = new XoopsFormRadioYN(_AM_DEBASER_AUTOSTART, 'autostart', 1, _YES, _NO);
	$op_hidden = new XoopsFormHidden("op", "newplayer");
	$submit_button = new XoopsFormButton("", "dbsubmit", _SUBMIT, "submit");
	$nuform->addElement($formplayername);
	$nuform->addElement($formplayercode);
	$nuform->addElement($formplayerheight);
	$nuform->addElement($formplayerwidth);
	$nuform->addElement($formplayerautostart);
	$nuform->addElement($op_hidden);
	$nuform->addElement($submit_button);
	$xoopsTpl->assign('newplayer', $nuform->render());
	$xoopsTpl->assign('adminmenu', debaseradminMenu(3, _AM_DEBASER_EDITPLAYERS));
	$xoopsTpl->display( 'db:debaser_amplaymanage.html' );
	}
/* -- */

	/* function for deleting player when confirmed */
	function deleteplayer($del=0) {

	global $xoopsDB;

		if (isset($_POST['del']) && $_POST['del'] == 1) {
		$sql = "DELETE FROM ".$xoopsDB->prefix('debaser_player')." WHERE name=".$xoopsDB->quoteString($_POST['playertype'])." ";

			if ($xoopsDB->query($sql)) {
			redirect_header('index.php', 1, $_POST['playertype']._AM_DEBASER_DELETED);
			}
			else {
			redirect_header('index.php', 1, $_POST['playertype']._AM_DEBASER_NOTDELETED);
			}
		exit();
		}
		else {
		echo "<h4>"._AM_DEBASER_PLAYERADMIN."</h4>";
		xoops_confirm(array('playertype' => $_GET['playertype'], 'del' => 1), 'index.php?op=deleteplayer', _AM_DEBASER_SUREDELETEPLAYER);
		}
	}
	/* -- */

	/* function for deleting mp3 when confirmed */
	function deletesong($del=0) {

	global $xoopsDB, $xoopsModule;

		if (isset($_POST['del']) && $_POST['del'] == 1) {

		$sql = "
		DELETE FROM ".$xoopsDB->prefix('debaser_files')."
		WHERE xfid=".intval($_POST['mpegid'])."";

			if ($xoopsDB->query($sql)) {
			@unlink(XOOPS_ROOT_PATH.'/modules/debaser/upload/'.$_POST['delfile']);
			xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['mpegid']);
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'song', $_POST['mpegid']);
			redirect_header('index.php', 2, $_POST['delfile']._AM_DEBASER_DELETED);
			}
			else {
			redirect_header('index.php', 2, $_POST['delfile']._AM_DEBASER_NOTDELETED);
			}
		exit();
		}
		else {
		echo "<h4>"._AM_DEBASER_FILEADMIN."</h4>";
		if (isset($_POST['delfile']) && ($_POST['mpegid'])) {
		$delfile = $_POST['delfile'];
		$mpegid = $_POST['mpegid'];
		}
		else {
		$delfile = $_GET['delfile'];
		$mpegid = $_GET['mpegid'];
		}
		xoops_confirm(array('delfile' => $delfile, 'mpegid' => $mpegid, 'del' => 1), 'index.php?op=deletesong', _AM_DEBASER_SUREDELETEFILE);
		}
	}
	/* -- */

	/* function for saving player changes */
	function changeplayer() {

	global $xoopsDB, $myts;

	$xoopsDB->query("
	UPDATE ".$xoopsDB->prefix('debaser_player')."
	SET
		html_code = '".$myts->stripSlashesGPC($_POST['playernew'])."',
		name = ".$xoopsDB->quoteString($_POST['namenew']).",
		height = ".intval($_POST['playerheight']).",
		width = ".intval($_POST['playerwidth']).",
		autostart = ".intval($_POST['autostart'])."
	WHERE name=".$xoopsDB->quoteString($_POST['name'])." ");

	redirect_header('index.php',2,_AM_DEBASER_DBUPDATE);
	}
	/* -- */

	/* function editing players */
	function editplayer() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if (!isset($xoopsTpl)) {
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $playertype, $myts;

	$result = $xoopsDB->query("
	SELECT name, html_code, height, width, autostart FROM ".$xoopsDB->prefix('debaser_player')."
	WHERE name=".$xoopsDB->quoteString($playertype)." ");

	list($name, $html_code, $height, $width, $autostart) = $xoopsDB->fetchRow($result);

	$edform = new XoopsThemeForm(_AM_DEBASER_EDITPLAYER, "editplayerform", "index.php");
	$formplayername = new XoopsFormText(_AM_DEBASER_NAME, "namenew", 50, 50, $name);
	$formplayercode = new XoopsFormTextArea (_AM_DEBASER_CODE, 'playernew', $myts->htmlSpecialChars($html_code), 20, 50);
	$formplayerheight = new XoopsFormText (_AM_DEBASER_HEIGHT, 'playerheight', 4, 4, $height);
	$formplayerwidth = new XoopsFormText(_AM_DEBASER_WIDTH, 'playerwidth', 4, 4, $width);
	$formplayerautostart = new XoopsFormRadioYN(_AM_DEBASER_AUTOSTART, 'autostart', $autostart, _YES, _NO);
	$op_hidden = new XoopsFormHidden("op", "changeplayer");
	$name_hidden = new XoopsFormHidden('name', $name);
	$submit_button = new XoopsFormButton("", "dbsubmit", _SUBMIT, "submit");
	$edform->addElement($formplayername);
	$edform->addElement($formplayercode);
	$edform->addElement($formplayerheight);
	$edform->addElement($formplayerwidth);
	$edform->addElement($formplayerautostart);	
	$edform->addElement($op_hidden);
	$edform->addElement($name_hidden);
	$edform->addElement($submit_button);
	$xoopsTpl->assign('adminmenu', debaseradminMenu(3, _AM_DEBASER_EDITPLAYER.' : '.$name));
	$xoopsTpl->assign('editplayer', $edform->render());
	$xoopsTpl->display('db:debaser_ameditplay.html');
	}
	/* -- */

	/* function for saving new players */
	function newplayer() {

	global $xoopsDB, $myts;

	$xoopsDB->query("
	INSERT INTO ".$xoopsDB->prefix('debaser_player')." (name, html_code, width, height, autostart)
	VALUES (".$xoopsDB->quoteString($_POST['newplayername']).", '".$myts->stripSlashesGPC($_POST['newplayer'])."', ".intval($_POST['playerheight']).", ".intval($_POST['playerwidth']).", ".intval($_POST['autostart'])." ) ");

	redirect_header('index.php',1,_AM_DEBASER_NEWPLAYADD);
	}
	/* -- */

	xoops_cp_header();

	if (!isset($op)) {
	$op = '';
	}

	switch ($op) {

		case 'batchadd':
		batchadd();
		break;

		case 'showmpegs':
		showmpegs();
		break;

		case 'playermanager':
		playermanager();
		break;

		case 'deleteplayer':
		deleteplayer();
		break;

		case 'newplayer':
		newplayer();
		break;

		case 'deletesong':
		deletesong();
		break;

		case 'editmpegs':
		editmpegs();
		break;

		case 'saveeditmpegs':
		saveeditmpegs();
		break;

		case 'saveapprove':
		saveapprove();
		break;

		case 'editplayer':
		editplayer();
		break;

		case 'singleupload':
		singleupload ();
		break;

		case 'changeplayer':
		changeplayer();
		break;

		case 'movesongs':
		movesongs();
		break;

		case 'approve':
		approve();
		break;

		case 'default':
		default:
		debaseradmin();
		break;
	}

	xoops_cp_footer();

?>