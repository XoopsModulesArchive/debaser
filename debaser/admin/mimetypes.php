<?php
/**
 * $Id: mimetypes.php v 1.01 02 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.4
 * Release Date: 11 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

	include 'admin_header.php';
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include XOOPS_ROOT_PATH.'/modules/debaser/class/debasertree.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';

	$op = '';

		if (isset($_POST)) {
			foreach ($_POST as $k => $v) {
			${$k} = $v;
			} 
		} 

		if (isset($_GET)) {
			foreach ($_GET as $k => $v) {
			${$k} = $v;
			} 
		} 

		if (isset($_GET['op'])) $op = $_GET['op'];

		if (isset($_POST['op'])) $op = $_POST['op'];

/**
 * edit_mimetype()
 * 
 * @param integer $mime_id 
 * @return 
 */
	function edit_mimetype($mime_id = 0) {

	global $xoopsDB;

	$mime_arr = array();
	$mime_arr['mime_id'] = 0;
	$mime_arr['mime_ext'] = '';
	$mime_arr['mime_name'] = '';
	$mime_arr['mime_types'] = '';
	$mime_arr['mime_admin'] = 1;
	$mime_arr['mime_user'] = 0;
	$mime_arr['mime_preselect'] = 0;

		if ($mime_id != 0) {
		$query = "
		SELECT * 
		FROM ".$xoopsDB->prefix('debaser_mimetypes')."
		WHERE mime_id = ".intval($mime_id)."";
		
		$mime_arr = $xoopsDB->fetchArray($xoopsDB->query($query));
		}

	echo '<div style="float: left; width:100%;">';

	$forminfo = ($mime_id == 0) ? _AM_DEBASER_MIME_CREATEF : _AM_DEBASER_MIME_MODIFYF;
	$sform = new XoopsThemeForm($forminfo, "op", xoops_getenv('PHP_SELF'));
	$sform -> addElement(new XoopsFormText(_AM_DEBASER_MIME_EXTF, 'mime_ext', 5, 60, $mime_arr['mime_ext']), true);
	$sform -> addElement(new XoopsFormText(_AM_DEBASER_MIME_NAMEF, 'mime_name', 50, 255, $mime_arr['mime_name']), true);
	$sform -> addElement(new XoopsFormTextArea(_AM_DEBASER_MIME_TYPEF, 'mime_type', $mime_arr['mime_types'], 7, 60));
	$madmin_radio = new XoopsFormRadioYN(_AM_DEBASER_MIME_ADMINF, 'mime_admin', $mime_arr['mime_admin'], ' '._YES.'', ' '._NO.'');
	$sform -> addElement($madmin_radio);
	$muser_radio = new XoopsFormRadioYN(_AM_DEBASER_MIME_USERF, 'mime_user', $mime_arr['mime_user'], ' '._YES.'', ' '._NO.'');
	$sform -> addElement($muser_radio);
	$sform -> addElement(new XoopsFormHidden('mime_id', $mime_arr['mime_id']));
	$button_tray = new XoopsFormElementTray('', '');
	$hidden = new XoopsFormHidden('op', 'save');
	$button_tray -> addElement($hidden);

	$mytreechose = new debaserTree($xoopsDB->prefix("debaser_player"), 'xpid', 'name');
	ob_start();
	$mytreechose->debaserSelBox("name", "name", $mime_arr['mime_preselect'], 1, "preselectplayer");
	$formplayer = new XoopsFormLabel(_AM_DEBASER_PLAYERPRESELECT, ob_get_contents());
	ob_end_clean();
	$sform->addElement($formplayer);

		if (!$mime_id) {
		$butt_create = new XoopsFormButton('', '', _AM_DEBASER_MIME_CREATE, 'submit');
		$butt_create -> setExtra('onclick="this.form.elements.op.value=\'save\'"');
		$button_tray -> addElement($butt_create);
		$butt_clear = new XoopsFormButton('', '', _AM_DEBASER_MIME_CLEAR, 'reset');
		$button_tray -> addElement($butt_clear);
		$butt_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
		$butt_cancel -> setExtra('onclick="history.go(-1)"');
		$button_tray -> addElement($butt_cancel);
		}
		else {
		$butt_create = new XoopsFormButton('', '', _AM_DEBASER_MIME_MODIFY, 'submit');
		$butt_create -> setExtra('onclick="this.form.elements.op.value=\'save\'"');
		$button_tray -> addElement($butt_create);
		$butt_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
		$butt_cancel -> setExtra('onclick="history.go(-1)"');
		$button_tray -> addElement($butt_cancel);
		}

	$sform -> addElement($button_tray);
	$sform -> display();
	echo '</div>';

	echo '<div style="float: left; width:100%;">';
	$iform = new XoopsThemeForm(_AM_DEBASER_MIME_FINDMIMETYPE, "op", xoops_getenv('PHP_SELF'));
	$iform -> addElement(new XoopsFormText(_AM_DEBASER_MIME_EXTFIND, 'fileext', 5, 60, ""), true);
	$button_tray = new XoopsFormElementTray('', '');
	$hidden = new XoopsFormHidden('op', 'openurl');
	$button_tray -> addElement($hidden);
	$butt_create = new XoopsFormButton('', '', _AM_DEBASER_MIME_FINDIT, 'submit');
	$butt_create -> setExtra('onclick="this.form.elements.op.value=\'openurl\'"');
	$button_tray -> addElement($butt_create);
	$iform -> addElement($button_tray);
	$iform -> display();
	echo '</div>';
	}

	switch ($op) {

	case 'openurl':
	$fileext = trim($_POST['fileext']);
	$url = "http://filext.com/detaillist.php?extdetail=" . $fileext . "";

		if (!headers_sent()) {
		header("Location: $url");
		}
		else {
		echo "<meta http-equiv='refresh' content='0;url=$url target='_blank''>\r\n";
		}
	break;

	case 'update';
	$mime_id = (isset($_GET['mime_id'])) ? $_GET['mime_id'] : $mime_id;

	$query = "
		SELECT * 
		FROM ".$xoopsDB->prefix('debaser_mimetypes')."
		WHERE mime_id = ".intval($mime_id)."";

	$mime_arr = $xoopsDB -> fetchArray($xoopsDB -> query($query));

		if (isset($_GET['admin']) && $_GET['admin'] == 1) {
		$mime_arr['mime_admin'] = ($mime_arr['mime_admin'] == 1) ? 0 : 1;
		} 

		if (isset($_GET['user']) && $_GET['user'] == 1) {
		$mime_arr['mime_user'] = ($mime_arr['mime_user'] == 1) ? 0 : 1;
		} 

	$query = "
		UPDATE ".$xoopsDB->prefix('debaser_mimetypes')."
		SET 
			mime_ext = '".$mime_arr['mime_ext']."',
			mime_types = '".$mime_arr['mime_types']."',
			mime_name = '".$mime_arr['mime_name']."', 
			mime_admin = ".$mime_arr['mime_admin'].",
			mime_user = ".$mime_arr['mime_user']."
		WHERE mime_id = ".intval($mime_id)."";
		
	$error = "Could not update user information:";
	$error .= "<br /><br />" . $query;
	$result = $xoopsDB -> queryF($query);

		if (!$result) {
		trigger_error($error, E_USER_ERROR);
		} 

	redirect_header("mimetypes.php?start=" . $_GET['start'] . "", 0, _AM_DEBASER_MIME_MODIFIED);
	break;

	case 'save':
	$mime_id = (isset($_POST['mime_id']) && $_POST['mime_id'] > 0) ? $_POST['mime_id'] : 0;
	$mime_ext = $myts -> addslashes($_POST['mime_ext']);
	$mime_name = $myts -> addslashes($_POST['mime_name']);
	$mime_types = $myts -> addslashes($_POST['mime_type']);
	$mime_admin = intval($_POST['mime_admin']);
	$mime_user = intval($_POST['mime_user']);
	$mime_preselect = intval($_POST['preselectplayer']);

		if ($mime_id == 0) {
		$query = "
			INSERT INTO ".$xoopsDB->prefix('debaser_mimetypes')." 
				(mime_id, mime_ext, mime_types, mime_name, mime_admin, mime_user, mime_preselect) 
			VALUES 
				('', '$mime_ext', '$mime_types', '$mime_name', $mime_admin, $mime_user, $mime_preselect)";
		}
		else {
		$query = "
			UPDATE ".$xoopsDB->prefix('debaser_mimetypes')." 
			SET 
				mime_ext = '$mime_ext', mime_types = '$mime_types', mime_name = '$mime_name', 
				mime_admin = $mime_admin, mime_user = $mime_user, mime_preselect = $mime_preselect WHERE mime_id = '$mime_id'";
		}

	$error = "Could not update mimetype information:";
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
		
	$query = "
		UPDATE ".$xoopsDB->prefix('debaser_mimetypes')." 
		SET ";            
			if ($mime_admin == 1) {
			$query .= " mime_admin = $type_all";            
			}
			else {
			$query .= "mime_user = $type_all";
			}

	$error = "Could not update mimetype information:";
	$error .= "<br /><br />" . $query;
	$result = $xoopsDB -> queryF($query);

		if (!$result) {
		trigger_error($error, E_USER_ERROR);
		} 

	redirect_header("mimetypes.php", 2, _AM_DEBASER_MIME_MODIFIED);
	break;
		
	case 'delete':
	$confirm = (isset($_POST['confirm'])) ? 1 : 0;

		if ($confirm) {
		$sql = "
		DELETE 
		FROM " . $xoopsDB -> prefix('debaser_mimetypes') . " 
		WHERE mime_id = " . $_POST['mime_id'] . "";

		$result = $xoopsDB -> query($sql);

			if ($result) {
			redirect_header("mimetypes.php", 1, sprintf(_AM_DEBASER_MIME_MIMEDELETED, $_POST['mime_name']));
			} 
			else {
			$error = "" . _AM_DEBASER_DBERROR . ": <br /><br />" . $sql;
			trigger_error($error, E_USER_ERROR);
			} 
		exit();
		} 
		else {
		$mime_id = (isset($_POST['mime_id'])) ? $_POST['mime_id'] : $mime_id;
		
		$result = $xoopsDB -> query("
		SELECT mime_id, mime_name 
		FROM ".$xoopsDB->prefix('debaser_mimetypes' )." 
		WHERE mime_id = ".intval($mime_id)."");

		list($mime_id, $mime_name) = $xoopsDB -> fetchrow($result);
		xoops_cp_header();
		xoops_confirm(array('op' => 'delete', 'mime_id' => $mime_id, 'confirm' => 1, 'mime_name' => $mime_name), 'mimetypes.php', _AM_DEBASER_MIME_DELETETHIS . "<br /><br />" . $mime_name, _AM_DEBASER_MIME_DELETE);
		xoops_cp_footer();
		} 

	break;

	case 'edit':
	xoops_cp_header();
	debaseradminMenu(_AM_DEBASER_MMIMETYPES);
	edit_mimetype($_GET['mime_id']);
	xoops_cp_footer();
	break;

	case 'main':
	default:
	global $xoopsUser, $xoopsDB, $xoopsModuleConfig;
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

	$query = "
	SELECT * 
	FROM " . $xoopsDB -> prefix('debaser_mimetypes') . " 
	ORDER BY mime_name";

	$mime_array = $xoopsDB -> query($query, 20, $start);
	$mime_num = $xoopsDB -> getRowsNum($xoopsDB -> query($query));

	xoops_cp_header();
	debaseradminMenu(_AM_DEBASER_MMIMETYPES);

	echo '<div style="float:left; width:100%">';
	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_DEBASER_MIME_MODIFYF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_DEBASER_MIME_INFOTEXT . "</div>\n
		</fieldset>\n
		";

	edit_mimetype();

	echo "
		<div style='float:left; width:100%;'><fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_DEBASER_MIME_ADMINF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_DEBASER_MIME_ADMINFINFO . "</div>\n
		";

	$query = "
	SELECT mime_ext FROM " . $xoopsDB -> prefix('debaser_mimetypes') . " 
	WHERE mime_admin = 1 
	ORDER by mime_ext";

	$result = $xoopsDB -> query($query);
	$allowmimetypes = '';

		while ($mime_arr = $xoopsDB -> fetchArray($result)) {
		$allowmimetypes .= $mime_arr['mime_ext'] . " | ";
		} 

		if ($allowmimetypes) {
		echo "<div style='padding: 8px;'>" . $allowmimetypes . "</div>";
		} 
		else {
		echo "<div style='padding: 8px;'>" . _AM_DEBASER_MIME_NOMIMEINFO . "</div>";
		} 

	echo "
		</fieldset></div><br /><div style='float:left; width:100%;'>\n
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_DEBASER_MIME_USERF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_DEBASER_MIME_USERFINFO . "</div>\n
		";

	$query = "
	SELECT mime_ext 
	FROM " . $xoopsDB -> prefix('debaser_mimetypes') . " 
	WHERE mime_user = 1 
	ORDER by mime_ext";

	$result = $xoopsDB -> query($query);
	$allowmimetypes = '';

		while ($mime_arr = $xoopsDB -> fetchArray($result)) {
		$allowmimetypes .= $mime_arr['mime_ext'] . " | ";
		} 

		if ($allowmimetypes) {
		echo "<div style='padding: 8px;'>" . $allowmimetypes . "</div>";
		} 
		else {
		echo "<div style='padding: 8px;'>" . _AM_DEBASER_MIME_NOMIMEINFO . "</div>";
		} 

	echo "
		</fieldset></div><br />\n
		<div style='float: left; width:100%;'><table border='0' width='100%' cellpadding='2' cellspacing='1' class='outer'>\n
		<tr>\n
		";

	$headingarray = array(_AM_DEBASER_MIME_ID, _AM_DEBASER_MIME_NAME, _AM_DEBASER_MIME_EXT, _AM_DEBASER_MIME_ADMIN, _AM_DEBASER_MIME_USER, _AM_DEBASER_MINDEX_ACTION);

		for($i = 0; $i <= count($headingarray)-1; $i++) {
		$align = ($i == 1) ? "left" : "center";
		echo "<td align='$align' class='bg3'><strong>" . $headingarray[$i] . "</strong></td>";
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

	echo "
		<tr>\n
		<td align='center' class='head'></td>\n
		<td class='even'></td>\n
		<td align='center' class='even'></td>\n
		";

	$admin_imgon = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=1'>".$imagearray['online']."</a>";
	$admin_imgoff = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=0'>".$imagearray['offline']."</a>";
	echo "<td align='center' width='10%' class='even'>" . $admin_imgon ." ". $admin_imgoff. "</td>";

	$user_imgon = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=1'>" . $imagearray['online'] . "</a>";
	$user_imgoff = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=0'>" . $imagearray['offline'] . "</a>";
	echo "
		<td align='center' width='10%' class='even'>" . $user_imgon ." ". $user_imgoff. "</td>\n
		<td align='center' class='even'>\n
		</td></tr>\n
		</table></div>\n
		";

	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
	$page = ($mime_num > 20) ? _AM_DEBASER_MINDEX_PAGE : '';
	$pagenav = new XoopsPageNav($mime_num, 20, $start, 'start');
	echo "<div align='right' style='padding: 8px;'>" . $page . '' . $pagenav -> renderNav() . '</div>';
	xoops_cp_footer();
	}

?>