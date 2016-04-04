<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Candidate;
use DB;

class CandidateSkills extends Model
{
    protected $table = 'candidate_skills';
    
    protected $fillable = ['candidate_id', 'skill', 'experience','recent'];
    
    public function candidate()
    {
        return $this->belongsTo('candidates','id');
    }
    public function searchBySkills($skills){
        
        $query = "SELECT candidate_id FROM candidate_skills cnd WHERE  ";
        $first = false;
        foreach($skills  as $param){
            $key = key((array)$param);
            $value = $param->$key;
            if($first){
                $query.= " AND ";
            }
            $first = true;
            $query.= " cnd.candidate_id IN (SELECT candidate_id FROM candidate_skills WHERE candidate_id=cnd.candidate_id AND skill LIKE '%".$key."%' AND experience>=".$value.") ";
        }
        
        $query.=' GROUP BY candidate_id';
        
        reset($skills);
        $resume_query = '';
        $first = false;
        foreach($skills  as $param){
            $key = key((array)$param);
            $value = $param->$key;
            if($first){
                $resume_query.= " AND ";
            }
            $first = true;
            $resume_query.= " resume_content like '%".$key."%' ";
        }
        
        $candidates_query = "SELECT * FROM candidates WHERE id IN (".$query.") OR (".$resume_query.") ";
        
        
        
        return DB::select($candidates_query);
    }
}