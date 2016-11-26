<?php
/**
* $Id: admin/about.php v 1.5 23 August 2004 hsalazar Exp $
* Module: Wordbook
* Version: v 1.5
* Release Date:
* Author: hsalazar
* License: GNU
*/

include 'admin_header.php';
include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';
$myts =& MyTextSanitizer::getInstance();

xoops_cp_header();

$module_handler =& xoops_gethandler('module');
$versioninfo =& $module_handler->get($xoopsModule->getVar('mid'));

debaseradminMenu(-1, _AM_DEBASER_ABOUT." ".$versioninfo->getInfo('name'));

// Left headings...
echo "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/".$versioninfo->getInfo('image')."' alt='' hspace='0' vspace='0' align='left' style='margin-right: 10px; '></a>";
echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>".$versioninfo->getInfo('name')." version ".$versioninfo->getInfo('version')."</div>";
if ($versioninfo->getInfo('author_realname') != '') {
	$author_name = $versioninfo->getInfo('author')." (".$versioninfo->getInfo('author_realname').")";
} else {
	$author_name = $versioninfo->getInfo('author');
}

echo "<div style='line-height:16px;font-weight:bold;display:block;'>"._AM_DEBASER_BY." ".$author_name."</div>";
echo "<div style='line-height:16px;display:block;'>".$versioninfo->getInfo('license')."</div><br></>\n";

// Author information
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><b>"._MI_DEBASER_AUTHOR_INFO."</b></td>";
echo "</tr>";

if ($versioninfo->getInfo('$author_realname') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200' align='left'>"._MI_DEBASER_AUTHOR_NAME."</td>";
	echo "<td class='even' align='left'>".$author_name."</td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('author_website_url') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200' align='left'>"._MI_DEBASER_AUTHOR_WEBSITE."</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('author_website_url') . "' target='blank'>".$versioninfo->getInfo('author_website_name')."</a></td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('author_email') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200' align='left'>"._MI_DEBASER_AUTHOR_EMAIL."</td>";
	echo "<td class='even' align='left'><a href='mailto:".$versioninfo->getInfo('author_email')."'>" . $versioninfo->getInfo('author_email')."</a></td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('credits') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200' align='left'>"._MI_DEBASER_AUTHOR_CREDITS."</td>";
	echo "<td class='even' align='left'>".$versioninfo->getInfo('credits')."</td>";
	echo "</tr></table><br />\n";
}

// Module development information
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><b>"._MI_DEBASER_MODULE_INFO."</b></td>";
echo "</tr>";

if ($versioninfo->getInfo('status') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200' align='left'>"._MI_DEBASER_MODULE_STATUS."</td>";
	echo "<td class='even' align='left'>".$versioninfo->getInfo('status')."</td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('support_site_url') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200px' align='left'>"._MI_DEBASER_MODULE_SUPPORT."</td>";
	echo "<td class='even' align='left'><a href='".$versioninfo->getInfo('support_site_url')."' target='blank'>".$versioninfo->getInfo('support_site_name')."</a></td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('submit_bug') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200px' align='left'>"._MI_DEBASER_MODULE_BUG."</td>";
	echo "<td class='even' align='left'><a href='".$versioninfo->getInfo('submit_bug')."' target='blank'>"."Submit a Bug in Soapbox Bug Tracker"."</a></td>";
	echo "</tr>";
}

if ($versioninfo->getInfo('submit_feature') != '') {
	echo "<tr>";
	echo "<td class='head' width = '200px' align='left'>"._MI_DEBASER_MODULE_FEATURE."</td>";
	echo "<td class='even' align='left'><a href='".$versioninfo->getInfo('submit_feature')."' target='_blank'>"."Request a feature in the Soapbox Feature Tracker"."</a></td>";
	echo "</tr></table><br />\n";
}

// Warning
if ($versioninfo->getInfo('warning') != '') {
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
	echo "<tr>";
	echo "<td class='bg3' align='left'><strong>"._MI_DEBASER_MODULE_DISCLAIMER."</strong></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='even' align='left'>".$versioninfo->getInfo('warning')."</td>";
	echo "</tr>";
	echo "</table><br />\n";
}

// Author's note
if ($versioninfo->getInfo('author_word') != '') {
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
	echo "<tr>";
	echo "<td class='bg3' align='left'><strong>"._MI_DEBASER_AUTHOR_WORD."</strong></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='even' align='left'>".$versioninfo->getInfo('author_word')."</td>";
	echo "</tr></table><br /><br />";
}

xoops_cp_footer();
?>