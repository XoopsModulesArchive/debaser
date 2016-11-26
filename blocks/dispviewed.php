<?php

	function b_debaser_dispviewed_show($options) {

		global $xoopsUser, $xoopsDB, $xoTheme;

		include_once XOOPS_ROOT_PATH.'/modules/debaser/include/constants.php';

		include_once DEBASER_RINC.'/functions.php';

		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('debaser');
		$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$module_id = $module->getVar('mid');
		$gperm_handler = &xoops_gethandler('groupperm');

		if (is_object($xoopsUser)) $current_userid = $xoopsUser->getVar('uid');
		else $current_userid = 'guest';

		$config_handler =& xoops_gethandler('config');
		$moduleConfig =& $config_handler->getConfigsByCat(0, $module_id);

		$getlofi = checkLofi();

		$block = array();

		$result = $xoopsDB->query("SELECT xfid, filename, title, artist, genreid, approved, fileext, views, uid, haslofi FROM ".$xoopsDB->prefix('debaser_files')." WHERE approved = 1 ORDER BY views DESC LIMIT 1");

		$mimeresult = $xoopsDB->query("SELECT xpid, name, html_code, playericon, canplay, equalizer FROM ".$xoopsDB->prefix('debaser_player')." WHERE xsubid = 0 AND isactive = 1 ORDER BY xpid ASC");

		$fileextstack = array();
		$xpidstack = array();
		$namestack = array();
		$iconstack = array();
		$externalstack = array();
		$equalstack = array();

		while ($sqlfetch = $xoopsDB->fetchArray($mimeresult)) {
			if ($sqlfetch['html_code'] == 'external') array_push($externalstack, '1');
			else array_push($externalstack, '0');

			array_push($fileextstack, $sqlfetch['canplay']);
			array_push($xpidstack, $sqlfetch['xpid']);
			array_push($namestack, $sqlfetch['name']);
			array_push($iconstack, $sqlfetch['playericon']);
			array_push($equalstack, $sqlfetch['equalizer']);
		}

		while ($myrow = $xoopsDB->fetchArray($result)) {

			if ($gperm_handler->checkRight('DebaserCatPerm', $myrow['genreid'], $groups, $module_id)) {

				if ($myrow['haslofi'] == 1 && $getlofi == 1) $lofi = 'lofi_';
				else $lofi = '';

				$imagebar = '';

			if ($results = array_search_ext($fileextstack, $myrow['fileext'], false)) {
  				foreach($results as $res) {
					$thekey = implode("', '", $res['keys']);
					if ($externalstack[$thekey] == 0) {

						if ($moduleConfig['innerdisplay'] == 1) {
						$path = getthedir($myrow['uid']);

						if ($equalstack[$thekey] == 0) $equal = '';
						else $equal = 1;

						$imagebar .= '<a class="innerdisp" title="'.$myrow['artist'].' '.$myrow['title'].'" href="'.DEBASER_URL.'/ajaxed.php?action=ajaxinner&amp;id='.$myrow['xfid'].'&amp;player='.$xpidstack[$thekey].'&amp;path='.$path.'&amp;equal='.$equal.'"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
						} else {

						$imagebar .= '<img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" onclick="javascript:openWithSelfMain(\''.DEBASER_URL.'/player.php?id='.$myrow['xfid'].'&amp;player='.$xpidstack[$thekey].'\',\'player\',10,10)" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /> ';
						}
					} else {

					$path = getthedir($myrow['uid']);

						$imagebar .= '<a href="'.DEBASER_UUP.'/'.$path.$myrow['filename'].'" target="_blank"><img src="'.DEBASER_UIMG.'/playericons/'.$iconstack[$thekey].'" alt="'.$namestack[$thekey].'" title="'.$namestack[$thekey].'" /></a> ';
					}
  				}
			}

			$block['imagerow'] = $imagebar;
			$block['artist'] = $myrow['artist'];
			$block['title'] = $myrow['title'];

}

			} // while ende

		if ($moduleConfig['innerdisplay'] == 1) {
			$xoTheme->addScript(null, array('type' => 'text/javascript'), '$(document).ready(function() { $("a.innerdisp").fancybox({ "hideOnContentClick": false, "zoomSpeedIn": 500, "zoomSpeedOut": 500, "overlayShow": true, "padding": 0, "easingIn": "easeOutBack", "easingOut": "easeInBack" }); });');
			$xoTheme->addStylesheet(DEBASER_UCSS.'/jquery.fancybox-1.2.6.css', array('type' => 'text/css', 'media' => 'screen', null));
			$xoTheme->addScript(DEBASER_UJS.'/jquery.fancybox-1.2.6.pack.js', array('type' => 'text/javascript'), null);
			$xoTheme->addScript(DEBASER_UJS.'/jquery.easing.1.3.pack.js', array('type' => 'text/javascript'), null);
		}

		return $block;

	}

?>