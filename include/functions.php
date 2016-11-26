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

	function debaser_adminMenu ($where=false) {

		include_once XOOPS_ROOT_PATH . '/class/template.php';
		require_once XOOPS_ROOT_PATH . '/class/theme.php';

		global $xoopsModule, $xoTheme, $xoopsModuleConfig;

		$xoTheme->addStylesheet(DEBASER_UCSS.'/menustyle.css', array('type' => 'text/css', 'media' => 'screen', null));
		$xoTheme->addScript(DEBASER_UJS.'/jquery-1.3.2.min.js', array('type' => 'text/javascript', 'charset' => _CHARSET), null);

		$tpl = new XoopsTpl();

		if (file_exists(DEBASER_ROOT.'/jeroen/noncommercial.txt')) $tpl->assign('jeroen', 'block');
		else $tpl->assign('jeroen', 'none');

		if ($xoopsModuleConfig['useffmpeg'] == 1) $tpl->assign('useffmpeg', true);
		else $tpl->assign('useffmpeg', false);

		$tpl->assign('debpref', XOOPS_URL.'/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='.$xoopsModule->getVar('mid'));
		$tpl->display( 'db:debaser_admin_menu.html' );

	}

	function checkLofi() {

		global $xoopsModuleConfig, $groups, $current_userid, $xoopsUser;

		if ($xoopsModuleConfig['uselame'] == 1) {
			if ($xoopsModuleConfig['loficondition'] == 'group') {
				$lofigroup = trim($xoopsModuleConfig['loficondcode']);
				$lofigroup = explode(' ', $lofigroup);
				if (@array_intersect($lofigroup, $groups)) $getlofi = 1;
				else $getlofi = 0;
			}
			if ($xoopsModuleConfig['loficondition'] == 'rank') {
				$lofirank = trim($xoopsModuleConfig['loficondcode']);
				$lofirank = explode(' ', $lofirank);
				if ($current_userid == 'guest') $nowrank = 0;
				else $nowrank = $xoopsUser->rank();

				if (array_intersect($nowrank, $lofirank)) $getlofi = 1;
				else $getlofi = 0;
			}
			if ($xoopsModuleConfig['loficondition'] == 'posts') {
				if ($current_userid == 'guest') $nowposts = 0;
				else $nowposts = $xoopsUser->posts();

				if ($nowposts <= $xoopsModuleConfig['loficondcode']) $getlofi = 1;
				else $getlofi = 0;
			}
		} else {
			$getlofi = 0;
		}
		return $getlofi;
	}

	function &get_debaserwysiwyg($caption, $name, $value = '', $width = '100%', $height = '400px', $supplemental='') {

		global $xoopsModuleConfig;

		$editor_option = strtolower($xoopsModuleConfig['use_wysiwyg']);
		$editor = false;
		$editor_configs = array();
		$editor_configs['name'] =$name;
		$editor_configs['value'] = $value;
		$editor_configs['rows'] = 15;
		$editor_configs['cols'] = 60;
		$editor_configs['width'] = '100%';
		$editor_configs['height'] = '350px';
		$editor_configs['editor'] = $editor_option;
		$editor = new XoopsFormEditor($caption, $name, $editor_configs);
		return $editor;
	}

	// get the directory, if!
	function getthedir($uid) {
		global $xoopsUser, $xoopsModuleConfig;

		$getgroups = (is_object($xoopsUser)) ? $xoopsUser->getGroups($uid) : XOOPS_GROUP_ANONYMOUS;

		// bad boy - just suppressing the warning - tsts!
		if (@array_intersect($xoopsModuleConfig['owndir'], $getgroups)) $owndir = 'user_'.$uid.'_/';
		else $owndir = '';

		return $owndir;
	}

	// get size of the folders content
	function debdirsize($file) {

		$size = 0;

		if (is_dir($file)) {
			if ($dh = opendir($file)) {
				while (($filecnt = readdir($dh)) !== false) {

					if ($filecnt == '.' || $filecnt == '..') continue;

					if (is_dir($file.'/'.$filecnt)) $size += debdirsize($file.'/'.$filecnt);
					else $size += filesize($file.'/'.$filecnt);

				}
			} else {
				return false;
			}
		} else {
			$size = filesize($file);
		}
		return $size;
	}

	// subtract one array from another
	function subtractArrays($array1, $array2) {
		$length_array1 = count($array1);
		$length_array2 = count($array2);
		$compare = 0;
		for ($i = 0; $i < $length_array1; $i++) {
			for ($j = 0; $j < $length_array2; $j++) {
				if ($array1[$i] == $array2[$j])
				$compare = 1;
			}

			if ($compare == 0)
				$new_array[] = $array1[$i];
			else
				$compare = 0;
		}

		return $new_array;
	}

	// function written by lars-magne on php.net
	function array_search_ext($arr, $search, $exact = true, $trav_keys = null) {
  		if(!is_array($arr) || !$search || ($trav_keys && !is_array($trav_keys))) return false;
  		$res_arr = array();
  		foreach($arr as $key => $val) {
    		$used_keys = $trav_keys ? array_merge($trav_keys, array($key)) : array($key);
    			if(($key === $search) || (!$exact && (strpos(strtolower($key), strtolower($search)) !== false))) $res_arr[] = array('type' => "key", 'hit' => $key, 'keys' => $used_keys, 'val' => $val);
    			if(is_array($val) && ($children_res = array_search_ext($val, $search, $exact, $used_keys))) $res_arr = array_merge($res_arr, $children_res);
    			else if(($val === $search) || (!$exact && (strpos(strtolower($val), strtolower($search)) !== false))) $res_arr[] = array('type' => "val", 'hit' => $val, 'keys' => $used_keys, 'val' => $val);
  		}
  		return $res_arr ? $res_arr : false;
	}

	function makehtaccess($path) {

		global $xoopsDB,$xoopsModule,$xoopsConfig,$xoopsModuleConfig;

		$allow_hotlinking = explode('|', $xoopsModuleConfig['allow_hotlinking']);

		$htcontent = "SetEnvIfNoCase Referer \"^".XOOPS_URL."(/|$)\" allowed=1";

		foreach ($allow_hotlinking as $url) {
			$htcontent .= "SetEnvIfNoCase Referer \"^{$url}(/|$)\" allowed=1";
		}

		$htcontent .= "SetEnvIfNoCase Referer \"^$\" allowed=1
		<FilesMatch \".(xml|XML|flv|FLV|mp3|MP3|mp4|MP4|m4v|M4V|swf|SWF)\">
		Order Allow,Deny
		Allow from env=allowed
		</FilesMatch>";

		$filename = DEBASER_ROOT.'/upload'.$path.'/.htaccess';

		if (!$handle = fopen($filename, 'w')) redirect_header($_SERVER['PHP_SELF'], 2, sprintf(_MD_DEBASER_NOOPENHT, $filename));

		if (fwrite($handle, $htcontent) === false) redirect_header($_SERVER['PHP_SELF'], 2, sprintf(_MD_DEBASER_NOWRITEHT, $filename));

		fclose($handle);

	}

?>