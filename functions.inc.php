<?php
/**
 * Function to check the mime content type of a file using what is available in the php installation
 * @param $file   file path
 *
 * @return string mime type or false
 */
function rdp_mime_get($file) {
    $s_ret = false;
    $b_existslegacy = function_exists("mime_content_type");
    if (function_exists("finfo_file")) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $file);
        finfo_close($finfo);
        if (!$mime && $b_existslegacy) $mime = mime_content_type($file);
            
        $s_ret = $mime;
    } else if ($b_existslegacy) {
        $s_ret = mime_content_type($file);
    }
    return $s_ret;
}
?>
