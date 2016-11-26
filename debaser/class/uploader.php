<?php 
// $Id: uploader.php,v 1.13 2004/07/11 07:16:07 Liquid Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/*
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org> 
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
	mt_srand((double) microtime() * 1000000);

	class XoopsMediaUploader {
	var $mediaName;
	var $mediaType;
	var $mediaSize;
	var $mediaTmpName;
	var $mediaError;
	var $uploadDir = '';
	var $allowedMimeTypes = array();
	var $maxFileSize = 0;
	var $targetFileName;
    var $prefix;
    var $ext;
    var $errors = array();
    var $savedDestination;
    var $savedFileName;
    /**
     * No admin check for uploads
     */
    //var $noadmin_sizecheck;
    /**
     * Constructor
     * 
     * @param string $uploadDir 
     * @param array $allowedMimeTypes 
     * @param int $maxFileSize 
     * @param int $cmodvalue 
     */
    function XoopsMediaUploader($uploadDir, $allowedMimeTypes = 0, $maxFileSize)
    {
        if (is_array($allowedMimeTypes))
        {
            $this->allowedMimeTypes = &$allowedMimeTypes;
        } 
        $this->uploadDir = $uploadDir;
        $this->maxFileSize = intval($maxFileSize); 
    } 

/*     function noAdminSizeCheck($value)
    {
        $this->noadmin_sizecheck = $value;
    }  */

    /**
     * Fetch the uploaded file
     * 
     * @param string $media_name Name of the file field
     * @param int $index Index of the file (if more than one uploaded under that name)
     * @global $HTTP_POST_FILES
     * @return bool 
     */
    function fetchMedia($media_name, $index = null)
    {
        global $_FILES;
        
		if (!isset($_FILES[$media_name]))
        {
            $this->setErrors(_MD_NOFILECHOOSE);
            return false;
        } elseif (is_array($_FILES[$media_name]['name']) && isset($index))
        {
            $index = intval($index);
            $this->mediaName = (get_magic_quotes_gpc()) ? stripslashes($_FILES[$media_name]['name'][$index]) : $_FILES[$media_name]['name'][$index];
            $this->mediaType = $_FILES[$media_name]['type'][$index];
            $this->mediaSize = $_FILES[$media_name]['size'][$index];
            $this->mediaTmpName = $_FILES[$media_name]['tmp_name'][$index];
            $this->mediaError = !empty($_FILES[$media_name]['error'][$index]) ? $_FILES[$media_name]['error'][$index] : 0;
        } 
        else
        {
            $media_name = @$_FILES[$media_name];
            $this->mediaName = (get_magic_quotes_gpc()) ? stripslashes($media_name['name']) : $media_name['name'];
            $this->mediaName = $media_name['name'];
            $this->mediaType = $media_name['type'];
            $this->mediaSize = $media_name['size'];
            $this->mediaTmpName = $media_name['tmp_name'];
            $this->mediaError = !empty($media_name['error']) ? $media_name['error'] : 0;
        } 
       

		$this->errors = array();

        if (intval($this->mediaSize) < 0)
        {
            $this->setErrors(_MD_INVALIDFILESIZE);
            return false;
        } 
        if ($this->mediaName == '')
        {
            $this->setErrors(_MD_FILENAMEEMPTY);
            return false;
        } 

        if ($this->mediaTmpName == 'none')
        {
            $this->setErrors(_MD_NOFILEUP);
            return false;
        } 

        if (!is_uploaded_file($this->mediaTmpName))
        {
            switch ($this->mediaError)
            {
                case 0: // no error; possible file attack!
                    $this->setErrors(_MD_PROBUP);
                    break;
                case 1: // uploaded file exceeds the upload_max_filesize directive in php.ini 
                    $this->setErrors(_MD_TOOBIG1);
                    break;
                case 2: // uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                    $this->setErrors(_MD_TOOBIG2);
                    break;
                case 3: // uploaded file was only partially uploaded
                    $this->setErrors(_MD_PARTIALLY);
                    break;
                case 4: // no file was uploaded
                    $this->setErrors(_MD_NOFILESEL4);
                    break;
                default: // a default error, just in case!  :)
                    $this->setErrors(_MD_NOFILESEL5);
                    break;
            } 
            return false;
        } 
        return true;
    } 

    /**
     * Set the target filename
     * 
     * @param string $value 
     */
    function setTargetFileName($value)
    {
        $this->targetFileName = strval(trim($value));
    } 

    /**
     * Set the prefix
     * 
     * @param string $value 
     */
    function setPrefix($value)
    {
        $this->prefix = strval(trim($value));
    } 

    /**
     * Get the uploaded filename
     * 
     * @return string 
     */
    function getMediaName()
    {
        return $this->mediaName;
    } 

    /**
     * Get the type of the uploaded file
     * 
     * @return string 
     */
    function getMediaType()
    {
        return $this->mediaType;
    } 

    /**
     * Get the size of the uploaded file
     * 
     * @return int 
     */
    function getMediaSize()
    {
        return $this->mediaSize;
    } 

    /**
     * Get the temporary name that the uploaded file was stored under
     * 
     * @return string 
     */
    function getMediaTmpName()
    {
        return $this->mediaTmpName;
    } 

    /**
     * Get the saved filename
     * 
     * @return string 
     */
    function getSavedFileName()
    {
        return $this->savedFileName;
    } 

    /**
     * Get the destination the file is saved to
     * 
     * @return string 
     */
    function getSavedDestination()
    {
        return $this->savedDestination;
    } 

    /**
     * Check the file and copy it to the destination
     * 
     * @return bool 
     */
    function upload($chmod = 0644)
    {
        if ($this->uploadDir == '')
        {
            $this->setErrors(_MD_UPDIRNOTSET);
            return false;
        } 

        if (!is_dir($this->uploadDir))
        {
            $this->setErrors(_MD_FAILOPENDIR . $this->uploadDir);
        } 

        if (!is_writeable($this->uploadDir))
        {
            $this->setErrors(_MD_FAILOPENDIRWRITE . $this->uploadDir);
        } 

        if (!$this->checkMaxFileSize())
        {
            $this->setErrors(sprintf(_MD_FILESIZE.' %u. '._MD_MAXSIZEALLOW.' %u' , $this->mediaSize, $this->maxFileSize));
        } 
 

        if (!$this->checkMimeType())
        {
            $this->setErrors(_MD_MIMENOTALLOW . $this->mediaType);
        } 

        if (!$this->_copyFile($chmod))
        {
            $this->setErrors(_MD_FAILUPLOADING . $this->mediaName);
        } 

        if (count($this->errors) > 0)
        {
            return false;
        } 
        return true;
    } 

    /**
     * Copy the file to its destination
     * 
     * @return bool 
     */
    function _copyFile($chmod)
    {
        $matched = array();
        if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched))
        {
            return false;
        } 
        if (isset($this->targetFileName))
        {
            $this->savedFileName = $this->targetFileName;
        } elseif (isset($this->prefix))
        {
            $this->savedFileName = uniqid($this->prefix) . '.' . strtolower($matched[1]);
        } 
        else
        {
            $this->savedFileName = strtolower($this->mediaName);
        } 
        $this->savedFileName = preg_replace('!\s+!', '_', $this->savedFileName);
		$this->savedDestination = $this->uploadDir . $this->savedFileName;
        if (!move_uploaded_file($this->mediaTmpName, $this->savedDestination))
        {
            return false;
        } 
        @chmod($this->savedDestination, $chmod);
        return true;
    } 

    /**
     * Is the file the right size?
     * 
     * @return bool 
     */
    function checkMaxFileSize()
    {
        /* if ($this->noadmin_sizecheck)
        {
            return true;
        } */ 
        if ($this->mediaSize > $this->maxFileSize)
        {
            return false;
        } 
        return true;
    }  



    /**
     * Is the file the right Mime type 
     * 
     * (is there a right type of mime? ;-)
     * 
     * @return bool 
     */
    function checkMimeType()
    {
        if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes))
        {
	$notification_handler =& xoops_gethandler('notification');
	$tags = array();
		$tags['MIME_NAME'] = $this->mediaType;
		$notification_handler->triggerEvent('global', 0, 'mimetype_submit', $tags);	
            return false;
			

        } 
        else
        {
            return true;
        } 
    } 

    /**
     * Add an error
     * 
     * @param string $error 
     */
    function setErrors($error)
    {
        $this->errors[] = trim($error);
    } 

    /**
     * Get generated errors
     * 
     * @param bool $ashtml Format using HTML?
     * @return array |string    Array of array messages OR HTML string
     */
    function &getErrors($ashtml = true)
    {
        if (!$ashtml)
        {
            return $this->errors;
        } 
        else
        {
            $ret = '';
            if (count($this->errors) > 0)
            {
                $ret = _MD_ERROR_RETURN;
                foreach ($this->errors as $error)
                {
                    $ret .= $error . '<br />';
                } 
            } 
            return $ret;
        } 
    } 
} 

?>