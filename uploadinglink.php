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

	$xoopsOption['template_main'] = 'debaser_uploadlink.html';

	include XOOPS_ROOT_PATH.'/header.php';

	if (!@array_intersect($xoopsModuleConfig['submitlink'], $groups)) {
		redirect_header('index.php', 2, _NOPERM);
		exit();
	}

	$fileform = new XoopsThemeForm(_MD_DEBASER_ADDLINK, 'extlink', DEBASER_URL.'/uploadlink.php', 'post', true);
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

	$platform = $xoopsDB->query("SELECT xpid, name FROM ".$xoopsDB->prefix('debaser_player')." WHERE platform = 1");
	$anyplat = $xoopsDB->getRowsNum($platform);
	if ($anyplat > 0) {
		$formfileplat = new XoopsFormSelect(_MD_DEBASER_PLATFORM, 'platform', '0', 1, false);
		$formfileplat->setExtra(' onchange="platformremove(\'link\');" ');
		$formfileplat->addOption(0, '-----');
		while(list($platid, $platname) = $xoopsDB->fetchRow($platform)) {
			$formfileplat->addOption($platid, $platname);
		}
		$fileform->addElement($formfileplat);
	}
	$fileform->addElement(new XoopsFormText(_MD_DEBASER_TYPEOFLINK, 'link', 50, 255), true);

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
	$fileform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
	$xoopsTpl->assign('extfileform', $fileform->render());

	$xoTheme->addStylesheet(DEBASER_UCSS.'/module.css', array('type' => 'text/css', 'media' => 'screen', null));
	$xoTheme->addScript(DEBASER_UJS.'/functions.js', array('type' => 'text/javascript'), null);

	include_once XOOPS_ROOT_PATH.'/footer.php';

?>