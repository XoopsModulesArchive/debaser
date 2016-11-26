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

	include 'header.php';
	include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	include_once DEBASER_CLASS.'/debasertree.php';

	$xoopsOption['template_main'] = 'debaser_uploadflash.html';

	include XOOPS_ROOT_PATH.'/header.php';

	if (!@array_intersect($xoopsModuleConfig['allowflashupload'], $groups)) {
		redirect_header('index.php', 2, _NOPERM);
		exit();
	}

	function makeflashtoken($formname, $id) {
		$token = md5(uniqid(rand(), true));
		$_SESSION[$formname.$id] = $token;
		return $token;
	}

	$xgroups = implode(' ', $groups);

	if (@array_intersect($xoopsModuleConfig['owndir'], $groups)) {

		if (!is_dir(DEBASER_RUP.'/user_'.$current_userid.'_')) {
			@mkdir(DEBASER_RUP.'/user_'.$current_userid.'_', 0777);
			@copy(DEBASER_RUP.'/index.html', DEBASER_RUP.'/user_'.$current_userid.'_/index.html');
			@chmod(DEBASER_RUP.'/user_'.$current_userid.'_/index.html', 0644);

			if ($xoopsModuleConfig['nohotlink'] == 1) {
				makehtaccess('/user_'.$current_userid.'_');
			} elseif ($xoopsModuleConfig['nohotlink'] == 0 && file_exists(DEBASER_RUP.'/user_'.$current_userid.'_/.htaccess')) {
				@unlink(DEBASER_RUP.'/user_'.$current_userid.'_/.htaccess');
			}
		}
	}

	// check for quota if used in permissions
	if (@array_intersect($xoopsModuleConfig['usequota'], $groups)) {
		$xoopsTpl->assign('usequota', true);
		$currentdirsize = debdirsize(DEBASER_RUP.'/user_'.$current_userid.'_/');

		if ($currentdirsize >= $xoopsModuleConfig['debaserdiskquota']) {
			$xoopsTpl->assign('toobig', true);
		} else {
			$remaining = round(($xoopsModuleConfig['debaserdiskquota'] - $currentdirsize) / 1048576, 2);
			$xoopsTpl->assign('remaining', _MD_DEBASER_REMAIN.$remaining);
			$remainbytes = $xoopsModuleConfig['debaserdiskquota'] - $currentdirsize;
			$xoopsTpl->assign('remainingbytes', $remainbytes);
			$xoopsTpl->assign('toobig', false);
		}
	} else {
		$remainbytes = $xoopsModuleConfig['debasermaxsize'];
		$remaining = $xoopsModuleConfig['debasermaxsize'];
		$remaining2 = $xoopsModuleConfig['debasermaxsize']/1024;
		$xoopsTpl->assign('remaining',$remaining);
		$xoopsTpl->assign('usequota', false);
	}

	$xoopsTpl->assign('uploadmax',round($xoopsModuleConfig['debasermaxsize'] / 1048576, 2));

	$fileform = new XoopsThemeForm(_MD_DEBASER_ADDMPEG, 'extfile', DEBASER_URL.'/upload.php');
	$fileform->setExtra('enctype="multipart/form-data"');
	$fileform->addElement(new XoopsFormHidden('MAX_FILE_SIZE', $remainbytes));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_ARTIST, 'artist', 50, 255));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_TITLE, 'title', 50, 255), true);
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_ALBUM, 'album', 50, 255));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_YEAR, 'year', 4, 4));

	if ($xoopsModuleConfig['multilang'] == 0) {
		$fileform->addElement(get_debaserwysiwyg(_MD_DEBASER_COMMENT, 'description', '', 15, 60));
	} else {
   		$langlist = XoopsLists::getLangList();
		$flaglist = '';

		foreach ($langlist as $flags) {
			$flaglist .= '<img onclick="toggleMe3(\'extlink\', \''.$flags.'\')" src="'.DEBASER_UIMG.'/'.$flags.'.gif" alt="'.$flags.'" title="'.$flags.'" id="'.$flags.'" /> ';
		}

		$fileform->addElement(new XoopsFormLabel(_MD_DEBASER_LANGSELECT, $flaglist));

		foreach ($langlist as $key => $languagedescription) {
			$languagedescription = get_debaserwysiwyg(_MD_DEBASER_DESCLANGUAGE.$languagedescription, $languagedescription .'_description', '', '100%', '400px', 'hiddentext');
			$fileform->addElement($languagedescription);
			unset($languagedescription);
		}
	}

	$mytreechose = new debaserTree($xoopsDB->prefix('debaser_text'), 'textcatid', 'textcatsubid', 'WHERE language = '.$langa, 'AND textfileid = 0', true);
	ob_start();
	$mytreechose->makeDebaserMySelBox('textcattitle', 'textcattitle', 0 , 0, 'genrefrom');
	$formgenre = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
	ob_end_clean();
	$fileform->addElement($formgenre);
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_TRACK, 'track', 3, 3));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_LENGTH, 'length', 5, 5));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_BITRATE, 'bitrate', 3, 3));
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_FREQUENCY, 'frequence', 5, 5));

	if ($is_deb_admin == true) $usertype = 'mime_admin';
	else $usertype = 'mime_user';

	$extstring = '';
	$extresult = $xoopsDB->query("SELECT mime_ext FROM ".$xoopsDB->prefix('debaser_mimetypes')." WHERE ".$usertype." = 1");

	while (list($mime_ext) = $xoopsDB->fetchRow($extresult)) {
		$extstring .= '*.'.$mime_ext.';';
	}

	$extstring = strrev($extstring);
	$extstring = substr($extstring, 1);
	$extstring = strrev($extstring);

	$flashtoken = makeflashtoken('extfile', $current_userid);

	$fileform->addElement(new XoopsFormLabel('','<div id="divSWFUploadUI"><div class="fieldset  flash" id="fsUploadProgress"><span class="legend">'._MD_DEBASER_FILEUPLOAD.'</span></div><p><span id="divStatus">0</span> '._MD_DEBASER_ALRUPLOADED.'</p><p><span id="spanButtonPlaceholder"></span><input id="btnCancel" type="button" value="'._CANCEL.'" disabled="disabled" style="margin-left: 65px; height: 22px; font-size: 8pt;" /><br /></p></div>'._MD_DEBASER_FLASHWARN));

	$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));
	$xoTheme->addStylesheet(DEBASER_UCSS.'/flashupload.css', array('type' => 'text/css', 'media' => 'screen', null));
	$xoTheme->addScript(DEBASER_UJS.'/swfupload.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(DEBASER_UJS.'/swfupload.swfobject.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(DEBASER_UJS.'/swfupload.queue.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(DEBASER_UJS.'/fileprogress.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(DEBASER_UJS.'/handlers.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(DEBASER_UJS.'/swfupload.speed.js', array('type' => 'text/javascript'), null);
	$xoTheme->addScript(null, array('type' => 'text/javascript'), 'var swfu; SWFUpload.onload = function () { var settings = { flash_url : "'.DEBASER_URL.'/swfupload/swfupload.swf", upload_url: "'.DEBASER_URL.'/upload.php", post_params: {"xuserid":"'.$current_userid.'", "xgroups":"'.$xgroups.'", "xmoduleid":"'.$module_id.'", "xusertype":"'.$usertype.'", "flashtoken":"'.$flashtoken.'", "PHPSESSID":"'.session_id().'"}, file_size_limit : "'.$remaining2.'", file_types : "'.$extstring.'",
file_types_description : "'.$extstring.'", file_upload_limit : '.$xoopsModuleConfig['debaserflashbatch'].', file_queue_limit : '.$xoopsModuleConfig['debaserflashbatch'].', custom_settings : { progressTarget : "fsUploadProgress", cancelButtonId : "btnCancel",
debaserreturnurl : "'.DEBASER_URL.'/uploadflash.php", editortype : "'.$xoopsModuleConfig['use_wysiwyg'].'" }, debug: false, button_image_url : "'.DEBASER_UIMG.'/XPButtonUploadText_61x22.png", button_placeholder_id : "spanButtonPlaceholder", button_width: 61, button_height: 22, swfupload_loaded_handler : swfUploadLoaded, file_queued_handler : fileQueued, file_queue_error_handler : fileQueueError, file_dialog_complete_handler : fileDialogComplete, upload_start_handler : uploadStart, upload_progress_handler : uploadProgress, upload_error_handler : uploadError, upload_success_handler : uploadSuccess, upload_complete_handler : uploadComplete, queue_complete_handler : queueComplete, minimum_flash_version : "9.0.28", swfupload_pre_load_handler : swfUploadPreLoad, swfupload_load_failed_handler : swfUploadLoadFailed }; swfu = new SWFUpload(settings); }');

	if ($xoopsModuleConfig['multilang'] == 1) {
		$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);
		$multijs = 'window.onload = function() {';

		foreach ($langlist as $wotever) {
			$trnodedesc = $wotever.'_description';
			$multijs .= '
			var '.$trnodedesc.' = document.getElementById("'.$trnodedesc.'").parentNode.parentNode;
			'.$trnodedesc.'.style.display="none";';
		}
		$multijs .= '}';
		$xoTheme->addScript(null, array('type' => 'text/javascript'), $multijs);
	}

	$xoopsTpl->assign('flashfileform', $fileform->render());

	include_once XOOPS_ROOT_PATH.'/footer.php';
?>