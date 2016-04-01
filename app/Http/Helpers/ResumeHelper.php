<?php
namespace App\Http\Helpers;

class ResumeHelper{
    private $file;
    private $extension;
    private $content='';
    
    function __construct($file,$ext){
        if(!$file || !file_exists($file)) return false;
        
        $this->file = $file;
        $this->extension = $ext;
    }
    
    function parseResume(){
        switch(strtolower($this->extension)){
            case 'doc' :
                $this->content = $this->readWord($this->file);
                break;
            case 'docx' :
                $this->content = $this->read_docx($this->file);
                break;
        }
        return $this->content;
    }
    
    function read_docx($filename){
    
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
        }
        zip_close($zip);
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\n", $content);
        $content = str_replace('</w:rPr></w:pPr>', "\n", $content);
        $striped_content = strip_tags($content);
    
        return $striped_content;
         
    }
    function read_doc($filename) {
        $fileHandle = fopen($filename, "r");
        $line = @fread($fileHandle, filesize($filename));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
        {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
            {
            } else {
                $outtext .= $thisline." ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }
    function readWord($filename) {
        if(file_exists($filename))
        {
            if(($fh = fopen($filename, 'r')) !== false )
            {
                $headers = fread($fh, 0xA00);
    
                // 1 = (ord(n)*1) ; Document has from 0 to 255 characters
                $n1 = ( ord($headers[0x21C]) - 1 );
    
                // 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
                $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );
    
                // 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
                $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
    
                // 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
                $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
    
                // Total length of text in the document
                $textLength = ($n1 + $n2 + $n3 + $n4);
    
                $extracted_plaintext = fread($fh, $textLength);
    
                // if you want to see your paragraphs in a new line, do this
                //return nl2br($extracted_plaintext);
                return $extracted_plaintext;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function br2nl( $string )
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $string);
    }
}