<?php
/**
 * Function to check the mime content type of a file using what is available in the php installation
 * @param $file   file path
 *
 * @return string mime type or false
 */
function mime_get($file) {
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

/**
 * Function to read a Docx Microsoft Word file
 * @param $filename  docx file path
 */
function docx_read($filename){

    $striped_content = '';
    $content = '';

    if(!$filename || !file_exists($filename)) return false;

    $zip = zip_open($filename);

    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
    }// end while

    zip_close($zip);

    //echo $content;
    //echo "<hr>";
    //file_put_contents('1.xml', $content);

    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);

    return $striped_content;
}
?>
