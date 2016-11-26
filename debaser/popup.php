<?php
/**************************************************************************/
/* PHP-NUKE: Internet Radio Block/Module                                  */
/* =====================================                                  */
/*                                                                        */
/* Copyright (c) 2003 by René Hart (webmaster@just4me.nl)                 */
/* http://www.just4me.nl                                                  */
/*                                                                        */
/* This program is free software. You can redistribute it and/or modify   */
/* it under the terms of the GNU General Public License as published by   */
/* the Free Software Foundation; either version 2 of the License.         */
/*                                                                        */
/* Internet Radio Block/Module V3.0 by Rene Hart (webmaster@just4me.nl)   */
/* http://www.just4me.nl                                                  */
/* For PHP-Nuke                                                           */
/*                                                                        */
/*                                                                        */
/**************************************************************************/
//******************************************//
// DO NOT CHANGE ANYTHING ON THIS SCRIPT !! //
//       LEAVE COPYRIGHT IN PLACE           //
//******************************************//

/* This version ported to E-Xoops and extensively modifiedby Bob Janes <bob@bobjanes.com>
18 October 2003
This version ported to Xoops and extensively modified
by frankblack <frankblack@myxoops.de
*/

	include '../../mainfile.php';

	require_once XOOPS_ROOT_PATH.'/class/template.php';

	$xoopsTpl = new XoopsTpl();

	$radio_id = $_GET['select'];

	$sql = "
	SELECT radio_name, radio_stream, radio_url, radio_picture
	FROM ".$xoopsDB->prefix('debaserradio')."
	WHERE radio_id = ".intval($radio_id)."";

	$result = $xoopsDB->query($sql);

	list($radio_name, $radio_stream, $radio_url, $radio_picture) = $xoopsDB->fetchRow($result);

	$xoopsTpl->assign('radio_name', $radio_name);
	$xoopsTpl->assign('radio_url', $radio_url);

		if ($radio_url != '') {
		$xoopsTpl->assign('urlavail', true);
		$xoopsTpl->assign('radio_url', $radio_url);
		}

		if ($radio_picture != '') {
		$xoopsTpl->assign('pictureavail', true);
		$xoopsTpl->assign('radio_picture', $radio_picture);
		}

		if (check_real($radio_stream) == true) {
		$xoopsTpl->assign('radioplayer', "<object id=player classid='clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa' height='60' width='300'>
		<param name='controls' value='controlpanel,statusfield' />
		<param name='console' value='clip1' />
		<param name='autostart' value='1' />
		<param name='src' value='$radio_stream' />
		<embed src='$radio_stream' type='audio/x-pn-realaudio-plugin' console='clip1' controls='controlpanel,statusfield' height='60' width='300' autostart='true' pluginspage='http://www.real.com/'>
		</embed>
		<noembed><a href='$radio_stream>play $radio_name</a></noembed>
		</object>");
		}
		else {
		$xoopsTpl->assign('radioplayer', "
		<object id='player' height='50' width='300' classid='clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#version=6,4,7,1112' standby='loading microsoft® windows® media player components...' type='application/x-oleobject'>
		<param name='filename' value='$radio_stream' />
		<param name='showcontrols' value='true' />
		<param name='showstatusbar' value='true' />
		<param name='showpositioncontrols' value='false' />
		<param name='showtracker' value='false' />
		<embed type='application/x-mplayer2' pluginspage = 'http://www.microsoft.com/windows/mediaplayer/' src='$radio_stream' name='player' width='300 height='60' autostart='true' showcontrols='1' showstatusbar='1' showdisplay='1'>
		</embed>
		<noembed><a href='$radio_stream'>play $radio_name</a></noembed>
		</object>");
		}

	/* Function to check for real player files */

	function check_real($t_url) {

	$temp_url = basename($t_url);
	$temp_url = trim($temp_url);
	$temp_url = strtolower($temp_url);
	$check_ram = substr($temp_url, -3);
	$check_rm = substr($temp_url, -2);
	$check_smil = substr($temp_url, -4);

		if ($check_rm == "rm") {
		return true;
		}
		elseif ($check_ram == "ram") {
		return true;
		}
		elseif ($check_rm == "ra") {
		return true;
		}
		elseif ($check_ram == "pls") {
		return true;
		}
		elseif ($check_ram == "rpm") {
		return true;
		}
		elseif ($check_smil == "smil") {
		return true;
		}
		else {
		return false;
		}
	}

	$xoopsTpl->assign("maintheme", xoops_getcss($xoopsConfig['theme_set']));

	$xoopsTpl->display('db:debaser_radiopopup.html');

?>