<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
	    $filename='D:/Projects/CodeBase/delta/storage/app/1630654_745_1_JillHeaden.docx';
	    $file = $this->read_docx($filename);
	    
	    //$filename='D:/Projects/CodeBase/delta/storage/app/IM_462.doc';
	    //$file = $this->read_doc($filename);
	   
	    $delimiter = '#';
	    $startTag = 'SKILLS:';
	    $endTag = 'EDUCATION';
	    $regex = $delimiter . preg_quote($startTag, $delimiter). '(.*?)'.preg_quote($endTag, $delimiter). $delimiter.'s';
	    
	    $exp = $regex;
	    preg_match($exp,$file,$match);
	    if(count($match)>0){
	        $skills = explode("\n",$match[1]);
	        foreach($skills as $skill){
	            if(trim($skill)!=""){
	                
	                $text = 'ignore everything except this (text)';
	                
	                preg_match('/\(([^)]*)\)/', $skill, $skill_match);
	                preg_match('/(?:\(|(?!^)\G)[^()]*?([+-]?\d+(?:,\d+)?)(?=[^()]*\))/', $skill, $skill_match_years);
	                print_r($skill_match_years);exit;
	                
	            }
	        }
	    }
	    //return view('welcome');
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
	    $content = str_replace('</w:r></w:p>', "\r\n", $content);
	    $striped_content = strip_tags($content);
	
	    return $striped_content;
	}
	function read_doc($filename){
	
	    $striped_content = '';
	    $content = '';
	
	    if(!$filename || !file_exists($filename)) return false;
	
	    $content = file_get_contents($filename);
	    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
	    $content = str_replace('</w:r></w:p>', "\r\n", $content);
	    //$striped_content = strip_tags($content);
	
	    return $this->br2nl(nl2br($content));
	}
	function br2nl( $string )
	{
	    return preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $string);
	}

}
