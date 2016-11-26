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

/**
*
*  This version ported to E-Xoops and extensively modified
*  by Bob Janes <bob@bobjanes.com>
*  18 October 2003
*
*/

    include_once __DIR__ . '/admin_header.php';
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    include_once XOOPS_ROOT_PATH.'/modules/debaser/include/functions.php';
    xoops_cp_header();

    if (isset($_GET['op']) && $_GET['op'] == 'IRdelete') {
        $op = 'IRdelete';
    }

    if (isset($_GET['op']) && $_GET['op'] == 'IRedit') {
        $op = 'IRedit';
    }

        if (isset($_GET['op']) && $_GET['op'] == 'IRnew') {
            $op = 'IRnew';
        }

        if (isset($_POST['op']) && $_POST['op'] == 'IRadd') {
            $op = 'IRadd';
        }

        if (isset($_POST['op']) && $_POST['op'] == 'IRchange') {
            $op = 'IRchange';
        }

function IRdisplay()
{
    global $xoopsDB;
    global $admin, $radioid;

    debaseradminMenu(1, _AM_DEBASERRAD_ADMIN.' : '._AM_DEBASERRAD_PROG);

    echo "
		<div style='float:left; width:100%;'><table class='outer' width='100%'>
		<tr>
		<td colspan='4' class='odd'>
		<strong>"._AM_DEBASERRAD_PROG . '</strong>
		</td>
		</tr>';
    $count = 0;
    $idnum = 1;

    $sql = 'SELECT radio_id, radio_name 
		FROM ' . $xoopsDB->prefix('debaserradio') . '
		ORDER BY radio_name ASC';

    $result = $xoopsDB->query($sql);
    while (list($radio_id, $radio_name) = $xoopsDB->fetchRow($result)) {
        if ($radio_id != '') {
            if ($count == 0) {
                $count = 1;
            }
            echo "
		<tr><td class='even' align='center' width='40'><strong>$idnum</strong></td>
		<td class='odd'>$radio_name</td>
		<td class='even' align='center' width='40'>
		<a href='radioindex.php?op=IRedit&amp;radioid=$radio_id'>
		<img src='../images/edit.gif' width='20' height='20' alt='"._EDIT."' title='"._EDIT."' /></a></td>
		<td class='odd' align='center' width='40'>
		<a href='radioindex.php?op=IRdelete&amp;radioid=$radio_id'>
		<img src='../images/delete.gif' width='20' height='20' alt='"._DELETE."' title='"._DELETE."' /></a></td></tr>";
            $idnum = $idnum + 1;
        }
    }

    if (($radio_id == '') and ($count == 0)) {
        echo '<em>' . _AM_DEBASERRAD_NOST . '</em>';
    }
    if ($count == 1) {
        echo '</table><br />';
    }
    echo "<a href='radioindex.php?op=IRnew'><strong>"._AM_DEBASERRAD_NEW . '</strong></a><hr /></div>';
}


function IRdelete()
{
    global $xoopsDB;

    $sql = 'DELETE FROM ' . $xoopsDB->prefix('debaserradio') . ' WHERE radio_id = ' . (int)$_GET['radioid'] . ' ';
    $xoopsDB->queryF($sql);
    redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);
}

    function IRedit()
    {
        global $radioid, $xoopsDB;

        debaseradminMenu(1, _AM_DEBASERRAD_ADMIN.' : '._AM_DEBASERRAD_EDIT);

        $sql = '
	SELECT radio_name, radio_stream, radio_url, radio_picture 
	FROM ' . $xoopsDB->prefix('debaserradio') . ' 
	WHERE radio_id = ' . (int)$_GET['radioid'] . '';

        $result = $xoopsDB->query($sql);

        list($radio_name, $radio_stream, $radio_url, $radio_picture) = $xoopsDB->fetchRow($result);

        echo "
		<div style='float:left; width:100%;'><table width='100%'>
		<tr>
		<td>";

        $edform = new XoopsThemeForm(_AM_DEBASERRAD_EDIT, 'IRedit', 'radioindex.php');
        $formradioname = new XoopsFormText(_AM_DEBASERRAD_NAME, 'radioname', 50, 50, $radio_name);
        $formradiourl = new XoopsFormText(_AM_DEBASERRAD_URL, 'radiourl', 50, 50, $radio_url);
        $formradiostream = new XoopsFormText(_AM_DEBASERRAD_STREAM, 'radiostream', 50, 255, $radio_stream);
        $formradiopict = new XoopsFormText(_AM_DEBASERRAD_PICT, 'radiopicture', 50, 50, $radio_picture);
        $op_hidden = new XoopsFormHidden('op', 'IRchange');
        $radioid_hidden = new XoopsFormHidden('radioid', $_GET['radioid']);
        $submit_button = new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit');
        $edform->addElement($formradioname);
        $edform->addElement($formradiourl);
        $edform->addElement($formradiostream);
        $edform->addElement($formradiopict);
        $edform->addElement($op_hidden);
        $edform->addElement($radioid_hidden);
        $edform->addElement($submit_button);
        $edform->setRequired($formradioname);
        $edform->setRequired($formradiostream);
        $edform->display();

        echo '
		</td>
		</tr>
		</table></div>';
    }

function IRchange()
{
    global $xoopsDB;

    $sql = '
		UPDATE ' . $xoopsDB->prefix('debaserradio') . '
		SET 
		radio_name=' . $xoopsDB->quoteString($_POST['radioname']) . ', 
		radio_stream=' . $xoopsDB->quoteString($_POST['radiostream']) . ', 
		radio_url=' . $xoopsDB->quoteString($_POST['radiourl']) . ', 
		radio_picture=' . $xoopsDB->quoteString($_POST['radiopicture']) . ' 
		WHERE radio_id = ' . (int)$_POST['radioid'] . '';

    $xoopsDB->query($sql);

    redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);
}

    function IRnew()
    {
        global $radioid, $xoopsDB;

        debaseradminMenu(1, _AM_DEBASERRAD_ADMIN.' : '._AM_DEBASERRAD_NEW);

        echo "
		<div style='float:left; width:100%;'><table width='100%'>
		<tr>
		<td>";

        $nuform = new XoopsThemeForm(_AM_DEBASERRAD_NEW, 'IRnew', 'radioindex.php');
        $formradioname = new XoopsFormText(_AM_DEBASERRAD_NAME, 'radioname', 50, 50);
        $formradiourl = new XoopsFormText(_AM_DEBASERRAD_URL, 'radiourl', 50, 50, 'http://');
        $formradiostream = new XoopsFormText(_AM_DEBASERRAD_STREAM, 'radiostream', 50, 255, 'http://');
        $formradiopict = new XoopsFormText(_AM_DEBASERRAD_PICT, 'radiopicture', 50, 50);
        $op_hidden = new XoopsFormHidden('op', 'IRadd');
        $submit_button = new XoopsFormButton('', 'dbsubmit', _SUBMIT, 'submit');
        $nuform->addElement($formradioname);
        $nuform->addElement($formradiourl);
        $nuform->addElement($formradiostream);
        $nuform->addElement($formradiopict);
        $nuform->addElement($op_hidden);
        $nuform->addElement($submit_button);
        $nuform->setRequired($formradioname);
        $nuform->setRequired($formradiostream);
        $nuform->display();

        echo "
		</td>
		</tr>
		</table>
		<br /><a href='http://www.radio-locator.com' target='_blank'>Open MIT Radio Locator</a><hr /></div>";
    }



function IRadd()
{
    global $xoopsDB;

    $sql = '
		INSERT 
		INTO ' . $xoopsDB->prefix('debaserradio') . "
		VALUES (''," . $xoopsDB->quoteString($_POST['radioname']) . ', ' . $xoopsDB->quoteString($_POST['radiostream']) . ', ' . $xoopsDB->quoteString($_POST['radiourl']) . ', '
               . $xoopsDB->quoteString($_POST['radiopicture']) . ')';

    $xoopsDB->query($sql);

    redirect_header('radioindex.php', 2, _AM_DEBASER_DBUPDATE);
}

    if (!isset($op)) {
        $op = '';
    }


switch ($op) {

    case 'IRdisplay':
    IRdisplay();
    break;

    case 'IRdelete':
    IRdelete();
    break;

    case 'IRedit':
    IRedit();
    break;

    case 'IRchange':
    IRchange();
    break;

    case 'IRnew':
    IRnew();
    break;

    case 'IRadd':
    IRadd();
    break;

    default:
    IRdisplay();
    break;

}

xoops_cp_footer();
