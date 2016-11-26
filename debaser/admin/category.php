<?php

	include 'admin_header.php';
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once '../include/functions.php';
	include_once XOOPS_ROOT_PATH.'/modules/debaser/class/debasertree.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

	/* assigning get-variables to work with register_globals off */
	if (isset($_GET['op']) && $_GET['op'] == 'genremanager') {
	$op = 'genremanager';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'editgenre') {
	$op = 'editgenre';
	$genreid = $_GET['genreid'];
	}

	if (isset($_POST['op']) && $_POST['op'] == 'newgenresave') {
	$op = 'newgenresave';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'editgenresave') {
	$op = 'editgenresave';
	}

	if (isset($_GET['op']) && $_GET['op'] == 'deletegenre') {
	$op = 'deletegenre';
	}

	if (isset($_POST['op']) && $_POST['op'] == 'newsubgenresave') {
	$op = 'newsubgenresave';
	}
	/* ------------------------------------------ */

	/* function for displaying available categories */
	function genremanager() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if (!isset($xoopsTpl)) { 
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $filelist, $genrelist, $genretitle, $xoopsModuleConfig, $xoopsModule, $myts;
	$myts =& MyTextSanitizer::getInstance();
	$mytree = new debaserTree($xoopsDB->prefix('debaser_genre'),"genreid","subgenreid");

	$count = 1;
	$chcount = 0;
	$countin = 0;

	$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix('debaser_genre') . " WHERE subgenreid = 0 ");

		while ($myrow = $xoopsDB->fetchArray($result)) {
		$countin++;

				$title = $myts->htmlSpecialChars($myrow['genretitle']);
				$arr = array();
				$mytree = new debaserTree($xoopsDB->prefix('debaser_genre'), "genreid", "subgenreid");
				$arr = $mytree->getdebaserChildTreeArray($myrow['genreid'], "genretitle");
				$space = 0;
				$chcount = 0;
				$subcategories = "";

					foreach($arr as $ele) {

						$chtitle = $myts->htmlSpecialChars($ele['genretitle']);

							if ($space > 0) $subcategories .= "<br />";

						$ele['prefix'] = str_replace(".","-",$ele['prefix']);
						$subcategories .= $ele['prefix']."&nbsp;". $chtitle."&nbsp;&nbsp;<a href='category.php?op=editgenre&amp;genreid=" . $ele['genreid'] . "'><img src='../images/edit.gif' align='middle' /></a>&nbsp;<a href='category.php?op=deletegenre&amp;genreid=" . $ele['genreid'] . "&amp;genrecat=" . $ele['genretitle'] . "'><img src='../images/delete.gif' align='middle' /></a><br />";
						$space++;
						$chcount++;
					}

				$xoopsTpl->append('categories', array('id' => $myrow['genreid'], 'title' => $title, 'subcategories' => $subcategories, 'count' => $count));
				$count++;

				}

	$genreid = (isset($_POST['genreid'])) ? $_POST['genreid'] : 0;	

	$member_handler = & xoops_gethandler('member');
	$group_list = & $member_handler -> getGroupList();
	$gperm_handler = & xoops_gethandler('groupperm');
	$groups = $gperm_handler -> getGroupIds('DebaserCatPerm', $genreid, $xoopsModule -> getVar('mid'));
	$groups = $groups;

	$nuform = new XoopsThemeForm(_AM_DEBASER_ADDNEWGENRE, "addnewgenre", "category.php");
		if ($xoopsModuleConfig['usecatperm'] == 1) {
		$nuform -> addElement(new XoopsFormSelectGroup(_AM_DEBASER_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));
		}
	$formgenrename = new XoopsFormText(_AM_DEBASER_GENRE, "genrenew", 50, 50);
	$graph_array = &XoopsLists::getFileListAsArray(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['catimage']);
	$indeximage_select = new XoopsFormSelect('', 'imgurl');
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $xoopsModuleConfig['catimage'] . "\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_FCATEGORY_CIMAGE, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);

		if (!empty($imgurl)) {
		$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['catimage'] . "/" . $imgurl . "' name='image' id='image' alt='' />"));
		} 
		else {
		$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
		}
	$formgenreweight = new XoopsFormText(_AM_DEBASER_WEIGHT, 'catweight', 4, 4, '0');
	$op_hidden = new XoopsFormHidden("op", "newgenresave");
	$submit_button = new XoopsFormButton("", "dbsubmit", _SUBMIT, "submit");
	$nuform->addElement($formgenrename);
	$nuform->addElement($indeximage_tray);
	$nuform->addElement($formgenreweight);
	$nuform->addElement($op_hidden);
	$nuform->addElement($submit_button);	
	$xoopsTpl->assign('addnewgenre', $nuform->render());

	$subcatform = new XoopsThemeForm(_AM_DEBASER_ADDNEWSUBGENRE, "addnewsubgenre", "category.php");	
	$subgenre_tray = new XoopsFormElementTray( _AM_DEBASER_SUBGENRE, '' );
	$subgenre_tray->addElement( new XoopsFormHidden( 'op', 'newsubgenresave' ) );	
	$subgenrename = new XoopsFormText('', "subgenrenew", 50, 50);
	$subgenre_tray->addElement($subgenrename);
	$mytreechose = new debaserTree($xoopsDB->prefix("debaser_genre"), "genreid", "subgenreid");
	ob_start();
	$mytreechose->debaserSelBox("genretitle", "genretitle", 0, 1, "subgenrefrom");
	$subgenre_tray->addElement(new XoopsFormLabel(_AM_DEBASER_GENREIN, ob_get_contents()));
	ob_end_clean();
	$subgenre_tray->addElement(new XoopsFormButton('', 'subgenresubmit', _SUBMIT, 'submit'));
	$subcatform->addElement($subgenre_tray);
	$xoopsTpl->assign('addsubcat', $subcatform->render());

	$xoopsTpl->assign('adminmenu', debaseradminMenu(2, _AM_DEBASER_EDITGENRES));
	$xoopsTpl->display('db:debaser_amgenremanage.html');
	}
	/* ------------------------------------------ */

	/* function for deleting genre when confirmed */
	function deletegenre($del=0) {

	global $xoopsDB;

		if (isset($_POST['del']) && $_POST['del'] == 1) {
		$sql1 = "
		DELETE 
		FROM ".$xoopsDB->prefix('debaser_genre')." 
		WHERE genretitle=".$xoopsDB->quoteString($_POST['genrecat'])." ";

		$sql2 = "
		DELETE 
		FROM ".$xoopsDB->prefix('debaser_files')." 
		WHERE genre=".$xoopsDB->quoteString($_POST['genrecat'])." ";

		$sql3 = 
		"SELECT filename 
		FROM ".$xoopsDB->prefix('debaser_files')." 
		WHERE genreid=".$xoopsDB->quoteString($_POST['genrecat'])." ";
		$result3 = $xoopsDB->query($sql3);

			if (($xoopsDB->query($sql1)) && ($xoopsDB->query($sql2))) {
				while (list($filename) = $xoopsDB->fetchRow($result3)) {
				@unlink(XOOPS_ROOT_PATH.'/modules/debaser/upload/'.$filename);
				xoops_groupperm_deletebymoditem ($xoopsModule -> getVar('mid'), 'WF3DownCatPerm', $genreid);
				}
				redirect_header('index.php', 2, $_POST['genrecat']._AM_DEBASER_DELETED);
			}
			else {
			redirect_header('index.php', 2, $_POST['genrecat']._AM_DEBASER_NOTDELETED);
			}
		exit();
		} 
		else {
		echo "<h4>"._AM_DEBASER_GENREADMIN."</h4>";
		xoops_confirm(array('genreid' => $_GET['genreid'], 'genrecat' => $_GET['genrecat'], 'del' => 1), 'category.php?op=deletegenre', _AM_DEBASER_SUREDELETEGENRE);	
		}
  //xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'song', $_POST['mpegid']);
	//xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $_POST['genrecat']);

	}
	/* ------------------------------------------ */

	/* function for modifying category */
	function editgenre() {

	require_once XOOPS_ROOT_PATH.'/class/template.php';

		if (!isset($xoopsTpl)) { 
		$xoopsTpl = new XoopsTpl();
		}

	global $xoopsDB, $genrecat, $xoopsModuleConfig, $xoopsModule;

	$sql = "SELECT genreid, subgenreid, genretitle, imgurl, catweight FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid=".intval($_GET['genreid'])."";
	$result = $xoopsDB->query($sql);
	list($genreid, $subgenreid, $genretitle, $imgurl, $catweight) = $xoopsDB->fetchRow($result);	

	$member_handler = & xoops_gethandler('member');
	$group_list = & $member_handler -> getGroupList();
	$gperm_handler = & xoops_gethandler('groupperm');
	$groups = $gperm_handler -> getGroupIds('DebaserCatPerm', $genreid, $xoopsModule -> getVar('mid'));
	$groups = $groups;

	$edform = new XoopsThemeForm(_AM_DEBASER_EDITGENRE, "editgenre", "category.php");
	
		if ($xoopsModuleConfig['usecatperm'] == 1) {	
		$edform -> addElement(new XoopsFormSelectGroup(_AM_DEBASER_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));
		}

	$formgenrename = new XoopsFormText(_AM_DEBASER_GENRE, "genrenew", 50, 50, $genretitle);

	if ($subgenreid == 0) {
	$graph_array = &XoopsLists::getFileListAsArray(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['catimage']);
	$indeximage_select = new XoopsFormSelect('', 'imgurl', $imgurl);
	$indeximage_select -> addOption ('', '----------');
	$indeximage_select->addOptionArray($graph_array);
	$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $xoopsModuleConfig['catimage'] . "\", \"\", \"" . XOOPS_URL . "\")'");
	$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_FCATEGORY_CIMAGE, '&nbsp;');
	$indeximage_tray->addElement($indeximage_select);

		if (!empty($imgurl)) {
		$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['catimage'] . "/" . $imgurl . "' name='image' id='image' alt='' />"));
		} 
		else {
		$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
		}
	}

	$formgenreweight = new XoopsFormText(_AM_DEBASER_WEIGHT, 'catweight', 4, 4, $catweight);
	$op_hidden = new XoopsFormHidden("op", "editgenresave");
	$genreid_hidden = new XoopsFormHidden('genreid', $genreid);
	$genrecat_hidden = new XoopsFormHidden('genrecat', $genretitle);
	$submit_button = new XoopsFormButton("", "dbsubmit", _SUBMIT, "submit");
	$edform->addElement($formgenrename);
	$edform->addElement($indeximage_tray);
	$edform->addElement($formgenreweight);
	$edform->addElement($op_hidden);
	$edform->addElement($genreid_hidden);
	$edform->addElement($genrecat_hidden);
	$edform->addElement($submit_button);	
	$xoopsTpl->assign('editgenre', $edform->render());
	$xoopsTpl->assign('genrecat', $genrecat);
	$xoopsTpl->assign('adminmenu', debaseradminMenu(2, _AM_DEBASER_EDITGENRE.' : '.$genretitle));
	$xoopsTpl->display('db:debaser_ameditgenre.html');
	}
	/* ------------------------------------------ */

	/* function for saving renamed genres */
	function editgenresave() {

	global $xoopsDB;
	$groups = isset($_POST['groups']) ? $_POST['groups'] : array();	
	$xoopsDB->query("
	UPDATE ".$xoopsDB->prefix('debaser_genre')." 
	SET 
		genretitle = ".$xoopsDB->quoteString($_POST['genrenew']).", 
		imgurl = ".$xoopsDB->quoteString($_POST['imgurl']).",
		catweight = ".intval($_POST['catweight'])."  
	WHERE genretitle = ".$xoopsDB->quoteString($_POST['genrecat'])." ");

	debaser_save_Perm($groups, $_POST['genreid'], 'DebaserCatPerm');
	$newid = $_POST['genreid'];

	$xoopsDB->query("
	UPDATE ".$xoopsDB->prefix('debaser_files')." 
	SET genre = ".$xoopsDB->quoteString($_POST['genrenew'])." 
	WHERE genre = ".$xoopsDB->quoteString($_POST['genrecat'])."");

	redirect_header('index.php', 1, _AM_DEBASER_DBUPDATE);
	}
	/* ------------------------------------------ */

	/* function for saving new categories */
	function newgenresave() {

	global $xoopsDB, $groups, $xoopsModule, $newid;
	$groups = isset($_POST['groups']) ? $_POST['groups'] : array();
	$xoopsDB->query("
	INSERT INTO ".$xoopsDB->prefix('debaser_genre')." (genretitle, imgurl, catweight) 
	VALUES (".$xoopsDB->quoteString($_POST['genrenew']).", ".$xoopsDB->quoteString($_POST['imgurl']).", ".intval($_POST['catweight']).") ");

	$newid = $xoopsDB->getInsertId();

	debaser_save_Perm($groups, $newid, 'DebaserCatPerm');

	$notification_handler =& xoops_gethandler('notification');
	$tags = array();
	$tags['GENRE_NAME'] = $_POST['genrenew'];
	$notification_handler->triggerEvent('global', 0, 'new_genre', $tags);
	redirect_header('index.php', 1, _AM_DEBASER_DBUPDATE);
	}
/* ------------------------------------------ */

	/* function for saving new subcategories */
	function newsubgenresave() {

	global $xoopsDB;

	$xoopsDB->query("
	INSERT INTO ".$xoopsDB->prefix('debaser_genre')." (subgenreid, genretitle) 
	VALUES (".intval($_POST['subgenrefrom']).", ".$xoopsDB->quoteString($_POST['subgenrenew']).") ");

	$notification_handler =& xoops_gethandler('notification');
	$tags = array();
	$tags['GENRE_NAME'] = $_POST['subgenrenew'];
	$notification_handler->triggerEvent('global', 0, 'new_genre', $tags);

	redirect_header('index.php', 1, _AM_DEBASER_DBUPDATE);
	}
/* ------------------------------------------ */

	xoops_cp_header();

	if (!isset($op)) {
	$op = '';
	} 

	switch ($op) {

		case 'default':
		default:
		genremanager();
		break;
		
		case 'deletegenre':
		deletegenre();
		break;

		case 'editgenre':
		editgenre();
		break;

		case 'editgenresave':
		editgenresave();
		break;

		case 'newgenresave':
		newgenresave();
		break;
	
		case 'newsubgenresave':
		newsubgenresave();
		break;
	}

	xoops_cp_footer();
?>