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

	// default function
	function debaserCatIndex() {

		global $xoopsDB, $myts, $xoopsModule, $xoopsModuleConfig, $editor, $xoopsConfig, $xoTheme;
		// Add a New Main Category
		debaser_adminMenu();

		$resultgenre = $xoopsDB->query("SELECT genreid FROM ".$xoopsDB->prefix('debaser_genre')."");
		$anycats = $xoopsDB->getRowsNum($resultgenre);

		$nuform = new XoopsThemeForm(_AM_DEBASER_ADDNEWGENRE, 'addnewgenre', 'category.php');
		$nuform->setExtra('enctype="multipart/form-data"');

		if ($xoopsModuleConfig['multilang'] == 1) {
			$langlist = XoopsLists::getLangList();
						$flaglist = '';
			foreach ($langlist as $flags) {
				$flaglist .= '<img onclick="toggleMe2(\'addnewgenre\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
			}
			$nuform->addElement(new XoopsFormLabel(_AM_DEBASER_LANGSELECT, $flaglist));

			foreach ($langlist as $languagetitle) {
				if ($languagetitle == $xoopsConfig['language'].'_title') {
					$languagetitle = new XoopsFormText(_AM_DEBASER_TITLANGUAGE.$languagetitle, $languagetitle.'_title', 50, 255);
					$nuform->addElement($languagetitle, true);
				} else {
					$languagetitle = new XoopsFormText(_AM_DEBASER_TITLANGUAGE.$languagetitle, $languagetitle.'_title', 50, 255);
					$nuform->addElement($languagetitle, true);
				}
				unset($languagetitle);
			}
		} else {
			$nuform->addElement(new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 255), true);
		}

		if ($anycats) {
		$subtree = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
		ob_start();
		$subtree->makeDebaserMySelBox('textcattitle', 'textcattitle', 0, 1, 'genreid');
		$subgenre = new XoopsFormLabel(_AM_DEBASER_SUBCAT, ob_get_contents());
		ob_end_clean();
		$nuform->addElement($subgenre);
		}

		$path = 'modules/debaser/images/category';
		$graph_array = &XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/" . $path);
		$indeximage_select = new XoopsFormSelect('', 'imgurl');
		$indeximage_select -> addOption ('', '----------');
		$indeximage_select->addOptionArray($graph_array);
		$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $path . "\", \"\", \"" . XOOPS_URL . "\")'");
		$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_FCATEGORY_CIMAGE, '&nbsp;');
		$indeximage_tray->addElement($indeximage_select);
		$indeximage_tray->addElement(new XoopsFormFile('', 'catfile', 0));

		if (!empty($imgurl))
			$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='".DEBASER_UIMG."/category/" . $imgurl . "' name='image' id='image' alt='' />"));
		else
			$indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));


		$nuform->addElement($indeximage_tray);


		if ($xoopsModuleConfig['multilang'] == 1) {
			foreach ($langlist as $languagedesc) {
				$languagedesc = get_debaserwysiwyg(_AM_DEBASER_DESCLANGUAGE.$languagedesc, $languagedesc.'_description', '', '100%', '400px', 'hiddentext');
				$nuform->addElement($languagedesc);
				unset($languagedesc);
			}
		} else {
			$nuform->addElement(get_debaserwysiwyg(_AM_DEBASER_CATDESCRIPTION, 'description', '', '100%', '400px', 'hiddentext'));
		}

		$nuform->addElement(new XoopsFormText(_AM_DEBASER_WEIGHT, 'catweight', 4, 4, '0'));
		$nuform->addElement(new XoopsFormHidden('op', 'addCat'));
		$nuform->addElement(new XoopsFormHidden('cid', '0'));
		$nuform->addElement(new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit'));
		$nuform->display();

		if ($xoopsModuleConfig['multilang'] == 1) {
			$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
			$multijs = 'window.onload = function() {';

			foreach ($langlist as $wotever) {
				$trnodetitle = $wotever.'_title';
				$trnodedesc = $wotever.'_description';
				$multijs .= 'var '.$trnodetitle.' = document.getElementById("'.$trnodetitle.'").parentNode.parentNode;
				'.$trnodetitle.'.style.display="none";
				var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
				'.$trnodedesc.'.style.display="none";';
			}
			$multijs .= '}';
			$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
		}
	}

	function editCat() {

		global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

		debaser_adminMenu();

		$resultgenre = $xoopsDB->query("SELECT genreid FROM ".$xoopsDB->prefix('debaser_genre')."");
		$anycats = $xoopsDB->getRowsNum($resultgenre);

		if ($anycats) {

			// Modify Category
			$modform = new XoopsThemeForm(_AM_DEBASER_MODCAT, 'modcat', 'category.php');
			$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
			ob_start();
			$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle');
			$modgenre = new XoopsFormLabel(_AM_DEBASER_MODIFY, ob_get_contents());
			ob_end_clean();
			$modform->addElement($modgenre);
			$modform->addElement(new XoopsFormHidden('op', 'modCat'));
			$modform->addElement(new XoopsFormButton('', 'modsubmit', _AM_DEBASER_MODIFY, 'submit'));
			$modform->display();
		} else {
			echo _AM_DEBASER_NOCAT2EDIT;
		}
	}

	// function for adding new categories
	function addCat() {

		global $xoopsDB, $myts, $xoopsModuleConfig, $xoopsConfig;


		if (isset($_FILES['catfile']) && !empty($_FILES['catfile']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_ROOT.'/images/category/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, $xoopsModuleConfig['catimagefsize'], $xoopsModuleConfig['shotwidth'], $xoopsModuleConfig['shotheight']);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$imgurl = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$imgurl = $_POST['imgurl'];
		}

		if (isset($_POST['genreid']))
			$pid = $_POST['genreid'];
		else
			$pid = 0;

		if ($xoopsModuleConfig['multilang'] == 0) {
			$title = $_POST['title'];
			$description = $_POST['description'];
		} else {
			$titleadder = $xoopsConfig['language'].'_title';
			$title = $_POST[$titleadder];
			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
		}

		$newid = $xoopsDB->genId($xoopsDB->prefix('debaser_genre')."_genreid_seq");
		$genreresult = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_genre')." (genreid, subgenreid, genretitle, imgurl, catweight, language) VALUES (".intval($newid).", ".intval($pid).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($imgurl).", ".intval($_POST['catweight']).", ".$xoopsDB->quoteString($xoopsConfig['language']).")");

		if ($newid == 0) $newid = $xoopsDB->getInsertId();

		if ($xoopsModuleConfig['multilang'] == 1) {
			$i = 0;
			foreach ($langlist as $langcontent) {
				$posttitle = $bb[$i].'_title';
				$postdescription = $bb[$i].'_description';

				if ($_POST[$posttitle] != '' && $_POST[$postdescription] != '') {
					$result = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textcatid, textcatsubid, textcattitle, textcattext, language) VALUES (".intval($newid).", ".intval($pid).", ".$xoopsDB->quoteString($_POST[$posttitle]).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$bb[$i]").")");
				}
				$i++;
			}
		} else {
			$result = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textcatid, textcatsubid, textcattitle, textcattext, language) VALUES (".intval($newid).", ".intval($pid).", ".$xoopsDB->quoteString($title).", ".$xoopsDB->quoteString($description).", ".$xoopsDB->quoteString($xoopsConfig['language']).")");
		}

		// Notify of new category
		global $xoopsModule;
		$tags = array();
		$tags['GENRE_NAME'] = $title;
		$notification_handler =& xoops_gethandler('notification');
		$notification_handler->triggerEvent('global', 0, 'new_genre', $tags);
		redirect_header('category.php?op=debaserCatIndex', 2, _AM_DEBASER_CATADDED);
	}

	// function for modifying existing categories
	function modCat() {

		global $xoopsDB, $myts, $xoopsModuleConfig, $xoopsConfig, $xoTheme;

		if ($xoopsModuleConfig['multilang'] == 0) $singlelang = ' AND b.language = '.$xoopsConfig['language'].'';
		else $singlelang = '';

		$result = $xoopsDB->query("SELECT genreid, subgenreid, imgurl, catweight FROM ".$xoopsDB->prefix('debaser_genre')." WHERE genreid = ".intval($_POST['textcatid'])."");
		list ($catid, $subgenreid, $imgurl, $catweight) = $xoopsDB->fetchRow($result);

		debaser_adminMenu();

		$modform = new XoopsThemeForm(_AM_DEBASER_MODCAT, 'modcat', 'category.php');
		$modform->setExtra('enctype="multipart/form-data"');

		if ($xoopsModuleConfig['multilang'] == 1) {
    		$langlist = XoopsLists::getLangList();
			$flaglist = '';
			foreach ($langlist as $flags) {
				$flaglist .= '<img onclick="toggleMe2(\'addnewgenre\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
			}
			$modform->addElement(new XoopsFormLabel(_AM_DEBASER_LANGSELECT, $flaglist));

			foreach ($langlist as $key => $languagetitle) {
				$langresult = $xoopsDB->query("SELECT textcattitle FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = '$languagetitle' AND textcatid = ".intval($catid)."");
				list ($title) = $xoopsDB->fetchRow($langresult);
				$languagetitle = new XoopsFormText(_AM_DEBASER_TITLANGUAGE.$languagetitle, $languagetitle.'_title', 50, 50, $title);
				$modform->addElement($languagetitle, true);
				unset($languagetitle);
			}
		} else {
			$langresult = $xoopsDB->query("SELECT textcattitle FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = ".$xoopsDB->quote($xoopsConfig['language'])." AND textcatid = ".intval($catid)."");
			list ($title) = $xoopsDB->fetchRow($langresult);
			$modform->addElement(new XoopsFormText(_AM_DEBASER_TITLE, 'title', 50, 50, $title), true);
		}

		$path = 'modules/debaser/images/category';

		$graph_array = &XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/" . $path);
		$indeximage_select = new XoopsFormSelect('', 'imgurl', $imgurl);
		$indeximage_select -> addOption ('', '----------');
		$indeximage_select->addOptionArray($graph_array);
		$indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $path . "\", \"\", \"" . XOOPS_URL . "\")'");
		$indeximage_tray = new XoopsFormElementTray(_AM_DEBASER_FCATEGORY_CIMAGE, '&nbsp;');
		$indeximage_tray->addElement($indeximage_select);
		$indeximage_tray->addElement(new XoopsFormFile('', 'catfile', 0));

		if (!empty($imgurl)) $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/" . $path . "/" . $imgurl . "' name='image' id='image' alt='' />"));
		else $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));

		$modform->addElement($indeximage_tray);
		$subtree = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$xoopsDB->quoteString($xoopsModuleConfig['masterlang']), 'AND textfileid = 0', false);
		ob_start();
		$subtree->makeDebaserMySelBox('textcattitle', 'textcattitle', $subgenreid, 1, 'subgenreid');
		$subgenre = new XoopsFormLabel(_AM_DEBASER_SUBCAT, ob_get_contents());
		ob_end_clean();
		$modform->addElement($subgenre);
		$modform->addElement(new XoopsFormHidden('genreid', $catid));
		$modform->addElement(new XoopsFormHidden('op', 'modCatS'));

		if ($xoopsModuleConfig['multilang'] == 1) {
			foreach ($langlist as $languagedesc) {
				$langresult = $xoopsDB->query("SELECT textcattext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = '$languagedesc' AND textcatid = ".intval($catid)."");
				list ($description) = $xoopsDB->fetchRow($langresult);
				$languagedesc = get_debaserwysiwyg(_AM_DEBASER_DESCLANGUAGE.$languagedesc, $languagedesc.'_description', $description, '100%', '400px', 'hiddentext');
				$modform->addElement($languagedesc);
				unset($languagedesc);
			}
  		} else {
			$langresult = $xoopsDB->query("SELECT textcattext FROM ".$xoopsDB->prefix('debaser_text')." WHERE language = ".$xoopsDB->quote($xoopsConfig['language'])." AND textcatid = ".intval($catid)."");
			list ($description) = $xoopsDB->fetchRow($langresult);
  			$modform->addElement(get_debaserwysiwyg(_AM_DEBASER_CATDESCRIPTION, 'description', $description, 15, 60));
		}

		$modform->addElement(new XoopsFormText(_AM_DEBASER_WEIGHT, 'catweight', 4, 4, $catweight));
		$button_tray = new XoopsFormElementTray( '', '' );
		$submitmod = new XoopsFormButton('', 'modsubmit', _SUBMIT, 'submit');
		$deletemod = new XoopsFormButton('', 'delsubmit', _DELETE, 'submit');
		$deletemod->setExtra('onclick="this.form.elements.op.value=\'delCat\'"');
		$cancelmod = new XoopsFormButton('', 'cancelsubmit', _CANCEL, 'cancel');
		$button_tray->addElement($submitmod);
		$button_tray->addElement($deletemod);
		$button_tray->addElement($cancelmod);
		$modform->addElement($button_tray);
		$modform->display();

		if ($xoopsModuleConfig['multilang'] == 1) {
			$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
			$multijs = 'window.onload = function() {';

		foreach ($langlist as $wotever) {
			$trnodetitle = $wotever.'_title';
			$trnodedesc = $wotever.'_description';
			$multijs .= 'var '.$trnodetitle.' = document.getElementById("'.$trnodetitle.'").parentNode.parentNode;
			'.$trnodetitle.'.style.display="none";
			var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
			'.$trnodedesc.'.style.display="none";';
		}
		$multijs .= '}';
		$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
	}
	}

	// function for saving modified categories
	function modCatS() {

		global $xoopsDB, $myts, $xoopsModuleConfig, $xoopsConfig;

		if (isset($_FILES['catfile']) && !empty($_FILES['catfile']['name'])) {
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';

		$uploaddir = DEBASER_ROOT.'/images/category/';

		$allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

		$uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, $xoopsModuleConfig['catimagefsize'], $xoopsModuleConfig['shotwidth'], $xoopsModuleConfig['shotheight']);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				echo $uploader->getErrors();
			} else {
				$imgurl = $uploader->getSavedFileName();
			}
		} else {
			echo $uploader->getErrors();
		}
		} else {
			$imgurl = $_POST['imgurl'];
		}

		// the genre cannot be subcategory of itself so we have to set is as main category
		if ($_POST['genreid'] == $_POST['subgenreid'])
			$subgenreid = 0;
		else
			$subgenreid = $_POST['subgenreid'];

		if ($xoopsModuleConfig['multilang'] == 0) {
			$title = $_POST['title'];
			$description = $_POST['description'];
		} else {
			$titleadder = $xoopsConfig['language'].'_title';
			$title = $_POST[$titleadder];
			$langlist = XoopsLists::getLangList();
			$aa = implode(',', $langlist);
			$bb = explode(',', $aa);
		}

		$result = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_genre')." SET genretitle = ".$xoopsDB->quoteString($title).", imgurl = ".$xoopsDB->quoteString($imgurl).", subgenreid = ".intval($subgenreid).", catweight = ".intval($_POST['catweight'])." WHERE genreid = ".intval($_POST['genreid'])." ");

		if ($xoopsModuleConfig['multilang'] == 0) {
		$result2 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textcattitle = ".$xoopsDB->quoteString($title).", textcattext = ".$xoopsDB->quoteString($description).", textcatid = ".intval($_POST['genreid']).", textcatsubid = ".intval($subgenreid)." WHERE textcatid = ".intval($_POST['genreid'])." ");
		} else {
			$i = 0;
			foreach ($langlist as $langcontent) {
				$posttitle = $bb[$i].'_title';
				$postdescription = $bb[$i].'_description';
				$language = $bb[$i];

				if ($_POST[$posttitle] != '' && $_POST[$postdescription] != '') {
					$getlang = $xoopsDB->query("SELECT textcatid FROM ".$xoopsDB->prefix('debaser_text')." WHERE textcatid = ".intval($_POST['genreid'])." AND language = ".$xoopsDB->quote("$bb[$i]")."");
					$getlangs = $xoopsDB->getRowsNum($getlang);

					if ($getlangs == 1) {
					$result2 = $xoopsDB->query("UPDATE ".$xoopsDB->prefix('debaser_text')." SET textcatid = ".intval($_POST['genreid']).", textcatsubid = ".intval($subgenreid).", textcattitle = ".$xoopsDB->quoteString($_POST[$posttitle]).", textcattext = ".$xoopsDB->quoteString($_POST[$postdescription]).", language = ".$xoopsDB->quoteString("$language")." WHERE textcatid = ".intval($_POST['genreid'])." AND language = ".$xoopsDB->quoteString("$language")."");
					} else {
					$result3 = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix('debaser_text')." (textcatid, textcatsubid, textcattitle, textcattext, language) VALUES (".intval($_POST['genreid']).", ".intval($subgenreid).", ".$xoopsDB->quoteString($_POST[$posttitle]).", ".$xoopsDB->quoteString($_POST[$postdescription]).", ".$xoopsDB->quoteString("$language").")");
					}
				}
				$i++;

				unset($posttitle);
				unset($postdescription);
				unset($language);
			}
		}

		if (!$result || !$result2) redirect_header('category.php?op=debaserCatIndex', 2, _AM_DEBASER_DBERROR);
		else redirect_header('category.php?op=debaserCatIndex', 2, _AM_DEBASER_DBUPDATE);
	}

	// function for deleting categories
	function delCat() {

		global $xoopsDB, $xoopsModuleConfig, $xoopsModule, $groups, $module_id;

		$mytree = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid', '', '');

		$cid =  isset($_POST['genreid']) ? intval($_POST['genreid']) : intval($_GET['genreid']);
		$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

		if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) $owndir = 'yes';
		else $owndir = 'no';

		if ($ok == 1) {
			//get all subcategories under the specified category
			$arr = $mytree->getDebaserAllChildId($cid);
			$lcount = count($arr);

			for ($i = 0; $i < $lcount; $i++) {
			//get all downloads in each subcategory
			$result = $xoopsDB->query("SELECT xfid, uid, filename, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE genreid = ".$arr[$i]."");
			//now for each download, delete the text data and vote ata associated with the download
				while (list($lid, $uid, $filename, $haslofi) = $xoopsDB->fetchRow($result)) {
					$xoopsDB->query(sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix('debaservotedata'), $lid));
					$xoopsDB->query(sprintf("DELETE FROM %s WHERE xfid = %u", $xoopsDB->prefix('debaser_files'), $lid));
					// delete the files
					if ($owndir == 'yes') $dir = 'user_'.$uid.'_/';
					else $dir = '';

					@unlink(DEBASER_RUP.'/'.$dir.$filename);
					if ($haslofi == 1) @unlink(DEBASER_RUP.'/'.$dir.'lofi_'.$filename);
					// delete comments
					xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
				}

			//all downloads for each subcategory is deleted, now delete the subcategory data
			$xoopsDB->query(sprintf("DELETE FROM %s WHERE genreid = %u", $xoopsDB->prefix('debaser_genre'), $arr[$i]));
			$xoopsDB->query(sprintf("DELETE FROM %s WHERE textcatid = %u", $xoopsDB->prefix('debaser_text'), $arr[$i]));
			}

			//all subcategory and associated data are deleted, now delete category data and its associated data
			$result = $xoopsDB->query("SELECT xfid, uid, filename, haslofi FROM ".$xoopsDB->prefix("debaser_files")." WHERE genreid=".$cid."");
			while(list($lid, $uid, $filename, $haslofib) = $xoopsDB->fetchRow($result)){
				$sql = sprintf("DELETE FROM %s WHERE xfid = %u", $xoopsDB->prefix("debaser_files"), $lid);
				$xoopsDB->query($sql);

				// delete the files
				if ($owndir == 'yes') $dir = 'user_'.$uid.'_/';
				else $dir = '';

				@unlink(DEBASER_RUP.'/'.$dir.$filename);
				if ($haslofib == 1) @unlink(DEBASER_RUP.'/'.$dir.'lofi_'.$filename);
				// delete comments
				xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
				$xoopsDB->query(sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix('debaservotedata'), $lid));
			}
			$xoopsDB->query(sprintf("DELETE FROM %s WHERE genreid = %u", $xoopsDB->prefix('debaser_genre'), $cid));
			$xoopsDB->query(sprintf("DELETE FROM %s WHERE textcatid = %u", $xoopsDB->prefix('debaser_text'), $cid));
			redirect_header("category.php?op=debaserCatIndex", 2, _AM_DEBASER_CATDELETED);
			exit();
			} else {
				xoops_cp_header();
				echo "<b>"._AM_DEBASER_DELCAT."</b>";
				xoops_confirm(array('op' => 'delCat', 'genreid' => $cid, 'ok' => 1), 'category.php', _AM_DEBASER_DELCCONF);
				xoops_cp_footer();
			}
	}

	if(!isset($_POST['op'])) $op = isset($_GET['op']) ? $_GET['op'] : 'default';
	else $op = $_POST['op'];

	switch ($op) {

		case 'addCat':
		addCat();
		break;

		case 'editCat':
		xoops_cp_header();
		editCat();
		xoops_cp_footer();
		break;

		case 'delCat':
		delCat();
		break;

		case 'modCat':
		xoops_cp_header();
		modCat();
		xoops_cp_footer();
		break;

		case 'modCatS':
		modCatS();
		break;

		case 'default':
		default:
		xoops_cp_header();
		debaserCatIndex();
		xoops_cp_footer();
		break;
	}
?>