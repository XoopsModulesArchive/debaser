<?php

    include __DIR__ . '/admin_header.php';
    include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    include_once __DIR__ . '/../include/functions.php';
    include_once XOOPS_ROOT_PATH.'/modules/debaser/class/debasertree.php';
    include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
    include __DIR__ . '/../class/uploader.php';

    /* assigning get-variables to work with register_globals off */
    if (isset($_GET['op']) && $_GET['op'] == 'batchadd') {
        $op = 'batchadd';
    }

    if (isset($_GET['op']) && $_GET['op'] == 'singleupload') {
        $op = 'singleupload';
    }
    /* -- */

    /* function for single upload */
    function singleupload()
    {
        require_once XOOPS_ROOT_PATH.'/class/template.php';

        if (!isset($xoopsTpl)) {
            $xoopsTpl = new XoopsTpl();
        }

        global $xoopsDB, $xoopsModuleConfig, $xoopsUser, $xoopsModule;
    
        $xoopsTpl->assign('uploadmax', $xoopsModuleConfig['debasermaxsize']);

        debaseradminMenu(4, _AM_DEBASER_SINGLEUP);
        echo '<br />';

        $fileform = new XoopsThemeForm(_MD_DEBASER_ADDMPEG, 'extfile', xoops_getenv('PHP_SELF'));
    
        if (($xoopsModuleConfig['debaserupsel'] == 1) || ($xoopsModuleConfig['debaserupsel'] == 3)) {
            $fileform->setExtra('enctype="multipart/form-data"');
        }

        $xfid = isset($_POST['xfid']) ? $_POST['xfid'] : 0;

        $memberHandler =  xoops_getHandler('member');
        $group_list = & $memberHandler -> getGroupList();
        $gpermHandler =  xoops_getHandler('groupperm');
        $groups = $gpermHandler -> getGroupIds('DebaserFilePerm', $xfid, $xoopsModule -> getVar('mid'));
        $groups = $groups;

        if ($xoopsModuleConfig['usefileperm'] == 1) {
            $fileform -> addElement(new XoopsFormSelectGroup(_AM_DEBASER_FCATEGORY_GROUPPROMPT, 'groups', true, $groups, 5, true));
        }
    
        if (($xoopsModuleConfig['debaserupsel'] == 1) || ($xoopsModuleConfig['debaserupsel'] == 3)) {
            $fileform->addElement(new XoopsFormFile(_AM_DEBASER_FILE, 'mp3file', 0));
        }

        if (($xoopsModuleConfig['debaserupsel'] == 2) || ($xoopsModuleConfig['debaserupsel'] == 3)) {
            $formlink = new XoopsFormText(_MD_DEBASER_EXTLINK, 'link', 50, 150);
            $fileform->addElement($formlink);
        }

        $formartist = new XoopsFormText(_MD_DEBASER_ARTIST, 'artist', 50, 50);
        $fileform->addElement($formartist);

        $formtitle = new XoopsFormText(_MD_DEBASER_TITLE, 'title', 50, 50);
        $fileform->addElement($formtitle);

        $formalbum = new XoopsFormText(_MD_DEBASER_ALBUM, 'album', 50, 50);
        $fileform->addElement($formalbum);

        $formyear = new XoopsFormText(_MD_DEBASER_YEAR, 'year', 4, 4);
        $fileform->addElement($formyear);

        $formcomment = new XoopsFormDhtmlTextArea(_MD_DEBASER_COMMENT, 'comment', '', 10, 50);
        $fileform->addElement($formcomment);

        if ($xoopsModuleConfig['autogenresingle'] != 1) {
            $mytreechose = new debaserTree($xoopsDB->prefix('debaser_genre'), 'genreid', 'subgenreid');
            ob_start();
            $mytreechose->debaserSelBox('genretitle', 'genretitle', 0, 1, 'genrefrom');
            $formgenre = new XoopsFormLabel(_MD_DEBASER_GENRE, ob_get_contents());
            ob_end_clean();
            $fileform->addElement($formgenre);
        }

        $formtrack = new XoopsFormText(_MD_DEBASER_TRACK, 'track', 3, 3);
        $fileform->addElement($formtrack);

        $formlength = new XoopsFormText(_MD_DEBASER_LENGTH, 'length', 5, 5);
        $fileform->addElement($formlength);

        $formbitrate = new XoopsFormText(_MD_DEBASER_BITRATE, 'bitrate', 3, 3);
        $fileform->addElement($formbitrate);

        $formfrequence = new XoopsFormText(_MD_DEBASER_FREQUENCY, 'frequence', 5, 5);
        $fileform->addElement($formfrequence);

        $formweight = new XoopsFormText(_AM_DEBASER_WEIGHT, 'weight', 4, 4, 0);
        $fileform->addElement($formweight);

        $op_hidden = new XoopsFormHidden('op', 'extfile');
        $fileform->addElement($op_hidden);

        $submit_button = new XoopsFormButton('', 'filesubmit', _SUBMIT, 'submit');
        $fileform->addElement($submit_button);
        $xoopsTpl->assign('extfileform', $fileform->render());
        $xoopsTpl->display('db:debaser_amupload.html');
    }
    /* ------------------------------------------ */

    /* function for adding songs via batch */
    function batchadd()
    {
        global $xoopsModuleConfig, $xoopsDB, $genre, $genretitle, $myts, $xoopsModule;

        $dir = opendir(XOOPS_ROOT_PATH . '/modules/debaser/batchload/');

        while ($filename = readdir($dir)) {
            if ($filename != '.' && $filename != '..') {
                $added = time();
              
                $sorce = XOOPS_ROOT_PATH."/modules/debaser/batchload/$filename";
                $taget = XOOPS_ROOT_PATH."/modules/debaser/upload/$filename";
                rename($sorce, $taget);
                $filepath = XOOPS_ROOT_PATH."/modules/debaser/upload/$filename";
                require_once XOOPS_ROOT_PATH.'/modules/debaser/class/getid3/getid3.php';
                $getID3 = new getID3;
                $ThisFileInfo = $getID3->analyze($filepath);
                getid3_lib::CopyTagsToComments($ThisFileInfo);

                include XOOPS_ROOT_PATH.'/modules/debaser/include/readinfo.php';
            
                $link = '';
                $weight = '0';

                if ($xoopsModuleConfig['autogenrebatch'] == 1) {
                    $genre = $myts->htmlSpecialChars((!empty($ThisFileInfo['comments_html']['genre']) ? implode($ThisFileInfo['comments_html']['genre']) : ''));

                    if ($genre == '') {
                        $genre = noGenreTitle($genretitle);
                    }

                    genreExist($genre);
                } else {
                    $genre = noGenreTitle($genretitle);
                }

                $mimesql = 'SELECT mime_ext FROM ' . $xoopsDB->prefix('debaser_mimetypes') . " WHERE mime_types LIKE '%" . $mimetyplink . "%'";
                $mimeresult = $xoopsDB->query($mimesql);
                list($mimetyp) = $xoopsDB->fetchRow($mimeresult);
                
    /* insert file into database and show sucess or not */

                if ($xoopsModuleConfig['autobatchapprove'] == 1) {
                    $approved = 1;
                    if ($mimetyp == '') {
                        $approved = 0;
                    }
                } else {
                    $approved = 0;
                }

                if ($title == '') {
                    $ext = strrchr($filename, '.');
                    $title = substr($filename, 0, -strlen($ext));
                }

                $sql = '
			INSERT INTO ' . $xoopsDB->prefix('debaser_files') . ' (
				filename, added, title, artist, album, year, addinfo, track, genre, length, bitrate, link, frequence, approved, fileext, weight) 
			VALUES (
				' . $xoopsDB->quoteString($filename) . ', 
				' . (int)$added . ', 
				' . $xoopsDB->quoteString($title) . ', 
				' . $xoopsDB->quoteString($artist) . ',
				' . $xoopsDB->quoteString($album) . ', 
				' . (int)$year . ', 
				' . $xoopsDB->quoteString($addinfo) . ', 
				' . (int)$track . ', 
				' . $xoopsDB->quoteString($genre) . ', 
				' . $xoopsDB->quoteString($length) . ', 
				' . (int)$bitrate . ', 
				' . $xoopsDB->quoteString($link) . ', 
				' . (int)$frequence . ', 
				' . (int)$approved . ',
				' . $xoopsDB->quoteString($mimetyp) . ',
				' . (int)$weight . ')';

                $result = $xoopsDB->queryF($sql);
            }
        }

        closedir($dir);

        redirect_header('index.php', 1, _AM_DEBASER_ALLUP);
    }
    /* ------------------------------------------ */

    /* inserting links or files */
    if (isset($_POST['op']) && $_POST['op'] == 'extfile') {
        if (($_FILES['mp3file']['name'] != '') && ($_POST['link'] != '')) {
            redirect_header('index.php', 1, _AM_DEBASER_UPLOADFILELINKNO);
            die();
        }

        if (isset($_FILES['mp3file'])) {

        /* sets the value for admin uploads */
        $usertype = 1;
        /* -- */

        /* checks the mimetype of the file and if the user is allowed to upload this one */
        $allowed_mimetypes = retdebasermime($_FILES['mp3file']['name'], $usertype);
        /* -- */

        /* reads the maximum size for uploads defined in preferences */
        $maxfilesize = $xoopsModuleConfig['debasermaxsize'];
        /* -- */

        $uploaddir = XOOPS_ROOT_PATH.'/modules/debaser/upload/';

            $uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, $maxfilesize);

            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if (!$uploader->upload()) {
                    @unlink($uploaddir.$uploader->getSavedFileName());
                    $errors = $uploader->getErrors();
                    redirect_header('upload.php', 2, $errors);
                } else {
                }

                $filename = $uploader->getSavedFileName();
                $filepath = $uploaddir.$uploader->getSavedFileName();

                $mimetyp = debasermimecompare();

                require_once __DIR__ . '/../class/getid3/getid3.php';
                $getID3 = new getID3;
                $ThisFileInfo = $getID3->analyze($filepath);
                getid3_lib::CopyTagsToComments($ThisFileInfo);
                include XOOPS_ROOT_PATH.'/modules/debaser/include/readinfo.php';
                if ($title == '') {
                    $title = basename($filepath, '.' . $mimetyp);
                }
            }
        }

        if (isset($_POST['link']) && ($_POST['link'] != '')) {

        /* this code tries to read information from a linked file, works only if fopen is allowed on remote server */
        $remotefilename = $_POST['link'];

            if ($fp_remote = fopen($remotefilename, 'rb')) {
                $localtempfilename = tempnam('/upload', 'getID3');

                if ($fp_local = fopen($localtempfilename, 'wb')) {

            /* Do this to only work on the first 10kB of the file (good enough for most formats) */
            $buffer = fread($fp_remote, 10240);
                    fwrite($fp_local, $buffer);
                    fclose($fp_local);

            /* this part tries to read information from the file */
            require_once __DIR__ . '/../class/getid3/getid3.php';
                    $getID3 = new getID3;
                    $ThisFileInfo = $getID3->analyze($localtempfilename);
                    getid3_lib::CopyTagsToComments($ThisFileInfo);
                    include XOOPS_ROOT_PATH.'/modules/debaser/include/readinfo.php';
            /* -- */
    
            /* Delete temporary file */
            unlink($localtempfilename);
                }
                fclose($fp_remote);
                $mimesql = 'SELECT mime_ext FROM ' . $xoopsDB->prefix('debaser_mimetypes') . " WHERE mime_types LIKE '%" . $mimetyplink . "%'";
                $mimeresult = $xoopsDB->query($mimesql);
                list($mimetyp) = $xoopsDB->fetchRow($mimeresult);
            }
        }
    /* -- */


    /* 	if ($title == '') {
        $title = basename($_POST['link'], ".".$mimetyp);
        } */
        
        /* checking if file alread exists, if yes page will be redirected */
        mpegExist($artist, $title);
        /* -- */

            if ($xoopsModuleConfig['autogenresingle'] == 1) {
                $genre = $myts->htmlSpecialChars((!empty($ThisFileInfo['comments_html']['genre']) ? implode($ThisFileInfo['comments_html']['genre']) : ''));

                if ($genre == '') {
                    $genre = noGenreTitle();
                }

                genreExist($genre);
            } else {
                if ($_POST['genrefrom'] == '0') {
                    $genre = noGenreTitle();
                } else {
                    $sqlb = '
				SELECT genretitle 
				FROM ' . $xoopsDB->prefix('debaser_genre') . ' 
				WHERE genreid = ' . (int)$_POST['genrefrom'] . '';

                    $resultb = $xoopsDB->query($sqlb);
                    list($genrelist) = $xoopsDB->fetchRow($resultb);
                    $genre = $genrelist;
                }
            }

                /* setting values for files wether they have to be approved or not */
                if ($xoopsModuleConfig['autofileapprove'] == 1) {
                    $approved = 1;
                } else {
                    $approved = 0;
                }
                /* -- */

        if (isset($_POST['link']) && ($_POST['link'] != '')) {
            /* checks if links are approved automatically */
        if ($xoopsModuleConfig['autolinkapprove'] == 1) {
            $approved = 1;

            if ($mimetyp == '') {
                $approved = 0;
                $mimetyp = '';
            }
        } else {
            $approved = 0;
        }
        /* -- */
        }

            /* if the title could not be read the filename will be the title */

            
                        if ($_FILES['mp3file'] != '') {
                            $link = '';
                        }
        if ($_POST['link'] != '') {
            $filename = '';
            $link = $_POST['link'];
            if ($title == '') {
                $title = basename($_POST['link'], '.' . $mimetyp);
            }
        }
        $added = time();

            /* this part overrides the automatic retrieval of information (if any) */
            if ($_POST['artist'] != '') {
                $artist = $myts->htmlSpecialChars($_POST['artist']);
            }
        if ($_POST['title'] != '') {
            $title = $myts->htmlSpecialChars($_POST['title']);
        }
        if ($_POST['album'] != '') {
            $album = $myts->htmlSpecialChars($_POST['album']);
        }
        if ($_POST['year'] != '') {
            $year = $_POST['year'];
        }
        if ($_POST['comment'] != '') {
            $addinfo = $myts->htmlSpecialChars($_POST['comment']);
        }
        if ($_POST['track'] != '') {
            $track = $_POST['track'];
        }
        if ($_POST['bitrate'] != '') {
            $bitrate = $_POST['bitrate'];
        }
        if ($_POST['frequence'] != '') {
            $frequence = $_POST['frequence'];
        }
        if ($_POST['length'] != '') {
            $length = $_POST['length'];
        }
            /* -- */

            /* insert file into database and show sucess or not */
            $sql = '
			INSERT INTO ' . $xoopsDB->prefix('debaser_files') . " (filename, added, title, artist, album, year, addinfo, track, genre, length, bitrate, link, frequence, approved, fileext, weight) 
			VALUES ('" . $filename . "', " . (int)$added . ', '
                   . $xoopsDB->quoteString($title) . ', ' . $xoopsDB->quoteString($artist) . ', ' . $xoopsDB->quoteString($album) . ', ' . (int)$year . ', '
                   . $xoopsDB->quoteString($addinfo) . ', '
                   . (int)$track . ', '
                   . $xoopsDB->quoteString($genre) . ', '
                   . $xoopsDB->quoteString($length) . ', '
                   . (int)$bitrate . ", '" . $link . "', " . (int)$frequence . ', '
                   . (int)$approved . ', '
                   . $xoopsDB->quoteString($mimetyp) . ', '
                   . (int)$_POST['weight'] . ')';

        $result = $xoopsDB->query($sql);

        $xfid = $xoopsDB->getInsertId();

        $groups = isset($_POST['groups']) ? $_POST['groups'] : array();
        debaser_save_Perm($groups, $xfid, 'DebaserFilePerm');

            // Notification
            $notificationHandler = xoops_getHandler('notification');
        $tags = array();
        $tags['SONG_NAME'] = $artist . ' - ' . $title;
        $tags['SONG_URL'] = XOOPS_URL. '/modules/'.$xoopsModule->getVar('dirname').'/singlefile.php?id='.$xfid;

        if ($xoopsModuleConfig['autofileapprove'] == 1) {
            $notificationHandler->triggerEvent('global', 0, 'new_song', $tags);
        } else {
            $tags['WAITINGSONG_URL'] = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php?op=approve';
            $notificationHandler->triggerEvent('global', 0, 'song_submit', $tags);
        }

        if ($result) {
            redirect_header('upload.php', 1, _AM_DEBASER_SINGLEUPSUCC);
        }
    }


    xoops_cp_header();

    if (!isset($op)) {
        $op = '';
    }

    switch ($op) {

        case 'batchadd':
        batchadd();
        break;

        case 'default':
        default:
        singleupload();
        break;
    }

    xoops_cp_footer();
