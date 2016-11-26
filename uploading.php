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

		$xoopsOption['template_main'] = 'debaser_uploading.html';

		include XOOPS_ROOT_PATH.'/header.php';

		if (!@array_intersect($xoopsModuleConfig['allowupload'], $groups)) {
			redirect_header('index.php', 2, _NOPERM);
			exit();
		}

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

		// check for quota
		if (@array_intersect($xoopsModuleConfig['usequota'], $groups)) {
			$xoopsTpl->assign('use_quota', true);
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
			$xoopsTpl->assign('toobig', false);
		}

		$xoopsTpl->assign('uploadmax',round($xoopsModuleConfig['debasermaxsize'] / 1048576, 2));

		$fileform = new XoopsThemeForm(_MD_DEBASER_ADDMPEG, 'extfile', DEBASER_URL.'/upload.php', 'post', true);
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

	// javascript for multilang
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
		$fileform->addElement(new XoopsFormHidden('userid', $current_userid));

			$fileform->addElement(new XoopsFormFile(_MD_DEBASER_FILEUPLOAD, 'mpupload', $xoopsModuleConfig['debasermaxsize']));

			$fileform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

		$xoopsTpl->assign('extfileform', $fileform->render());
		echo '<div id="loading">Uploading ...</div>';

		$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function () { $(\'#loading\').hide(); $("input#submit").click(function () { $(\'#loading\').show(); }); });');

		$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));

		include_once XOOPS_ROOT_PATH.'/footer.php';

?>