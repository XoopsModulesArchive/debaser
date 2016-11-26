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

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'main';
	else $op = $_POST['op'];

	function new_mimetype() {

	$sform = new XoopsThemeForm(_AM_DEBASER_MIME_CREATEF, 'newmime', 'mimetypes.php');
	$sform->addElement(new XoopsFormText(_AM_DEBASER_MIME_EXTF, 'mime_ext', 50, 60, ''), true);
	$sform->addElement(new XoopsFormText(_AM_DEBASER_MIME_NAMEF, 'mime_name', 50, 255, ''));
	$sform->addElement(new XoopsFormTextArea(_AM_DEBASER_MIME_TYPEF, 'mime_type', '', 7, 60), true);
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_ADMINF, 'mime_admin', '', ' '._YES.'', ' '._NO.''));
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_USERF, 'mime_user', '', ' '._YES.'', ' '._NO.''));
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_INLIST, 'mimeinlist', '', ' '._YES.'', ' '._NO.''));
	$button_tray = new XoopsFormElementTray('', '');
	$hidden = new XoopsFormHidden('op', 'save');
	$button_tray->addElement($hidden);
	$butt_create = new XoopsFormButton('', 'submit', _AM_DEBASER_MIME_CREATE, 'submit');
	$butt_create->setExtra(' onclick="this.form.elements.op.value=\'save\'"');
	$button_tray->addElement($butt_create);
	$butt_clear = new XoopsFormButton('', 'reset', _AM_DEBASER_MIME_CLEAR, 'reset');
	$button_tray->addElement($butt_clear);
	$butt_cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
	$butt_cancel->setExtra(' onclick="history.go(-1)"');
	$button_tray->addElement($butt_cancel);
	$sform->addElement($button_tray);
	$sform->display();
	}

	function edit_mimetype($mime_id = 0) {

	global $xoopsDB;

	$mime_arr = array();
	$mime_arr['mime_id'] = 0;
	$mime_arr['mime_ext'] = '';
	$mime_arr['mime_name'] = '';
	$mime_arr['mime_types'] = '';
	$mime_arr['mime_admin'] = 1;
	$mime_arr['mime_user'] = 0;
	$mime_arr['mimeinlist'] = 0;

		if ($mime_id != 0) {
		$query = "SELECT * FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mime_id = ".intval($mime_id)."";
		$mime_arr = $xoopsDB->fetchArray($xoopsDB->query($query));

	$sform = new XoopsThemeForm(_AM_DEBASER_MIME_MODIFYF, 'modmime', 'mimetypes.php');
	$sform->addElement(new XoopsFormText(_AM_DEBASER_MIME_EXTF, 'mime_ext', 50, 60, $mime_arr['mime_ext']), true);
	$sform->addElement(new XoopsFormText(_AM_DEBASER_MIME_NAMEF, 'mime_name', 50, 255, $mime_arr['mime_name']));
	$sform->addElement(new XoopsFormTextArea(_AM_DEBASER_MIME_TYPEF, 'mime_type', $mime_arr['mime_types'], 7, 60), true);
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_ADMINF, 'mime_admin', $mime_arr['mime_admin'], ' '._YES.'', ' '._NO.''));
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_USERF, 'mime_user', $mime_arr['mime_user'], ' '._YES.'', ' '._NO.''));
	$sform->addElement(new XoopsFormRadioYN(_AM_DEBASER_MIME_INLIST, 'mimeinlist', $mime_arr['mimeinlist'], ' '._YES.'', ' '._NO.''));
	$sform -> addElement(new XoopsFormHidden('mime_id', $mime_arr['mime_id']));
	$button_tray = new XoopsFormElementTray('', '');
	$hidden = new XoopsFormHidden('op', 'save');
	$button_tray -> addElement($hidden);

		$butt_create = new XoopsFormButton('', 'submit', _AM_DEBASER_MIME_CREATE, 'submit');
		$butt_create -> setExtra(' onclick="this.form.elements.op.value=\'save\'"');
		$button_tray -> addElement($butt_create);
		$butt_clear = new XoopsFormButton('', 'reset', _AM_DEBASER_MIME_CLEAR, 'reset');
		$button_tray -> addElement($butt_clear);
		$butt_cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
		$butt_cancel -> setExtra(' onclick="history.go(-1)"');
		$button_tray -> addElement($butt_cancel);

	$sform -> addElement($button_tray);
	$sform -> display();
		}
	}



	switch ($op) {

	case 'new_mimetype':
	xoops_cp_header();
	debaser_adminmenu();
	new_mimetype();
	xoops_cp_footer();
	break;

	case 'update':
	$mime_id = (isset($_GET['mime_id'])) ? $_GET['mime_id'] : $mime_id;

	$query = "SELECT * FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE mime_id = ".intval($mime_id)."";

	$mime_arr = $xoopsDB -> fetchArray($xoopsDB -> query($query));

		if (isset($_GET['admin']) && $_GET['admin'] == 1) {
		$mime_arr['mime_admin'] = ($mime_arr['mime_admin'] == 1) ? 0 : 1;
		}

		if (isset($_GET['user']) && $_GET['user'] == 1) {
		$mime_arr['mime_user'] = ($mime_arr['mime_user'] == 1) ? 0 : 1;
		}

	$query = "UPDATE ".$xoopsDB->prefix('debaser_mimetypes')." SET mime_ext = '".$mime_arr['mime_ext']."', mime_types = '".$mime_arr['mime_types']."', mime_name = '".$mime_arr['mime_name']."', mime_admin = ".$mime_arr['mime_admin'].", mime_user = ".$mime_arr['mime_user'].", mimeinlist = ".intval($mime_arr['mimeinlist'])." WHERE mime_id = ".intval($mime_id)."";

	$error = _AM_DEBASER_MIMENOUPUS;
	$error .= "<br /><br />" . $query;
	$result = $xoopsDB -> queryF($query);

		if (!$result) {
		trigger_error($error, E_USER_ERROR);
		}

	redirect_header("mimetypes.php?start=" . $_GET['start'] . "", 0, _AM_DEBASER_MIME_MODIFIED);
	break;

	case 'save':
	$mime_id = (isset($_POST['mime_id']) && $_POST['mime_id'] > 0) ? $_POST['mime_id'] : 0;

		if ($mime_id == 0) {
		$query = "INSERT INTO ".$xoopsDB->prefix('debaser_mimetypes')." (mime_id, mime_ext, mime_types, mime_name, mime_admin, mime_user, mimeinlist) VALUES ('', ".$xoopsDB->quoteString($_POST['mime_ext']).", ".$xoopsDB->quoteString($_POST['mime_type']).", ".$xoopsDB->quoteString($_POST['mime_name']).", ".intval($_POST['mime_admin']).", ".intval($_POST['mime_user']).", ".intval($_POST['mimeinlist']).")";
		}
		else {
		$query = "UPDATE ".$xoopsDB->prefix('debaser_mimetypes')." SET mime_ext = ".$xoopsDB->quoteString($_POST['mime_ext']).", mime_types = ".$xoopsDB->quoteString($_POST['mime_type']).", mime_name = ".$xoopsDB->quoteString($_POST['mime_name']).", mime_admin = ".intval($_POST['mime_admin']).", mime_user = ".intval($_POST['mime_user']).", mimeinlist = ".intval($_POST['mimeinlist'])." WHERE mime_id = ".intval($mime_id)."";
		}

	$error = _AM_DEBASER_MIMENOUPMIM;
	$error .= "<br /><br />" . $query;
	$result = $xoopsDB -> queryF($query);

		if (!$result) {
		trigger_error($error, E_USER_ERROR);
		}

	$dbupted = ($mime_id == 0) ? _AM_DEBASER_MIME_CREATED : _AM_DEBASER_MIME_MODIFIED;
	redirect_header("mimetypes.php", 1, $dbupted);
	break;

	case 'saveall':
	$mime_admin = (isset($_GET['admin']) && $_GET['admin'] == 1 ) ? $_GET['admin'] : 0;
	$mime_user = (isset($_GET['user']) && $_GET['user'] == 1) ? $_GET['user'] : 0;
	$type_all = intval($_GET['type_all']);

	$query = "UPDATE ".$xoopsDB->prefix('debaser_mimetypes')."
		SET ";
			if ($mime_admin == 1) {
			$query .= " mime_admin = $type_all";
			}
			else {
			$query .= "mime_user = $type_all";
			}

	$error = _AM_DEBASER_MIMENOUPMIM;
	$error .= "<br /><br />" . $query;
	$result = $xoopsDB -> queryF($query);

		if (!$result) {
		trigger_error($error, E_USER_ERROR);
		}

	redirect_header('mimetypes.php', 2, _AM_DEBASER_MIME_MODIFIED);
	break;

	case 'delete':
	$confirm = (isset($_POST['confirm'])) ? 1 : 0;

		if ($confirm) {
		$sql = "DELETE FROM " . $xoopsDB -> prefix('debaser_mimetypes') . " WHERE mime_id = " . $_POST['mime_id'] . "";

		$result = $xoopsDB -> query($sql);

			if ($result) {
			redirect_header('mimetypes.php', 2, sprintf(_AM_DEBASER_MIME_MIMEDELETED, $_POST['mime_name']));
			}
			else {
			$error = "" . _AM_DEBASER_DBERROR . ": <br /><br />" . $sql;
			trigger_error($error, E_USER_ERROR);
			}
		exit();
		}
		else {
		$mime_id = (isset($_POST['mime_id'])) ? $_POST['mime_id'] : $mime_id;

		$result = $xoopsDB -> query("SELECT mime_id, mime_name FROM ".$xoopsDB->prefix('debaser_mimetypes' )." WHERE mime_id = ".intval($mime_id)."");

		list($mime_id, $mime_name) = $xoopsDB -> fetchrow($result);
		xoops_cp_header();
		xoops_confirm(array('op' => 'delete', 'mime_id' => $mime_id, 'confirm' => 1, 'mime_name' => $mime_name), 'mimetypes.php', _AM_DEBASER_MIME_DELETETHIS . "<br /><br />" . $mime_name, _DELETE);
		xoops_cp_footer();
		}

	break;

	case 'edit':
	xoops_cp_header();
	debaser_adminMenu();
	edit_mimetype($_GET['mime_id']);
	xoops_cp_footer();
	break;

	case 'main':
	default:
	global $xoopsUser, $xoopsDB, $xoopsModuleConfig;
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

	$query = "SELECT * FROM ".$xoopsDB->prefix('debaser_mimetypes')." ORDER BY mime_name";

	$mime_array = $xoopsDB -> query($query, $xoopsModuleConfig['indexperpage'], $start);
	$mime_num = $xoopsDB -> getRowsNum($xoopsDB -> query($query));

	xoops_cp_header();
	debaser_adminMenu();

	echo '<table border="0" width="100%" cellpadding="0" cellspacing="1" class="outer" id="listmimes"><tr>';

	$headingarray = array(_AM_DEBASER_MIME_ID, _AM_DEBASER_MIME_NAME, _AM_DEBASER_MIME_EXT, _AM_DEBASER_MIME_ADMIN, _AM_DEBASER_MIME_USER, _AM_DEBASER_MINDEX_ACTION);

		for($i = 0; $i <= count($headingarray)-1; $i++) {
		$align = ($i == 1) ? "left" : "center";
		echo "<td align='$align' class='head'><strong>" . $headingarray[$i] . "</strong></td>";
		}

	echo "</tr>";

		while ($mimetypes = $xoopsDB -> fetchArray($mime_array)) {
		echo "<tr>";
		$image_array = array("<a href='mimetypes.php?op=edit&amp;mime_id=" . $mimetypes['mime_id'] . "'>" . $imagearray['editimg'] . "</a>
		<a href='mimetypes.php?op=delete&amp;mime_id=" . $mimetypes['mime_id'] . "'>" . $imagearray['deleteimg'] . "</a>");
		echo "<td align='center' class='head'>" . $mimetypes['mime_id'] . "</td>";
		echo "<td class='even'>" . $mimetypes['mime_name'] . "</td>";
		echo "<td align='center' class='even'>." . $mimetypes['mime_ext'] . "</td>";

		$yes_admin_image = ($mimetypes['mime_admin']) ? $imagearray['online'] : $imagearray['offline'];
		$image_admin = "<a href='mimetypes.php?op=update&amp;admin=1&amp;mime_id=" . $mimetypes['mime_id'] . "&amp;start=" . $start . "'>" . $yes_admin_image . "</a>";
		echo "<td align='center' width='10%' class='even'>" . $image_admin . "</td>";

		$yes_user_image = ($mimetypes['mime_user']) ? $imagearray['online'] : $imagearray['offline'];
		$image_user = "<a href='mimetypes.php?op=update&amp;user=1&amp;mime_id=" . $mimetypes['mime_id'] . "&amp;start=" . $start . "'>" . $yes_user_image . "</a>";
		echo "<td align='center' width='10%' class='even'>" . $image_user . "</td>";
		echo "<td align='center' class='even'>";

			foreach ($image_array as $images) {
			echo $images;
			}

		echo "</td></tr>";
		}

	echo '<tr><td align="center" class="head"></td><td class="even"></td><td align="center" class="even"></td>';

	$admin_imgon = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=1'>".$imagearray['online']."</a>";
	$admin_imgoff = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=0'>".$imagearray['offline']."</a>";
	echo "<td align='center' width='10%' class='even'>" . $admin_imgon ." ". $admin_imgoff. "</td>";

	$user_imgon = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=1'>" . $imagearray['online'] . "</a>";
	$user_imgoff = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=0'>" . $imagearray['offline'] . "</a>";
	echo "
		<td align='center' width='10%' class='even'>" . $user_imgon ." ". $user_imgoff. "</td>\n
		<td align='center' class='even'>\n
		</td></tr>\n
		</table>\n
		";

	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
	$page = ($mime_num > $xoopsModuleConfig['indexperpage']) ? _AM_DEBASER_MINDEX_PAGE : '';
	$pagenav = new XoopsPageNav($mime_num, $xoopsModuleConfig['indexperpage'], $start, 'start');
	echo "<div align='right' style='padding: 8px;'>" . $page . '' . $pagenav -> renderNav() . '</div>';
	xoops_cp_footer();
	}

?>