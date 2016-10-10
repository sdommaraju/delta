<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Http\Helpers\ResumeHelper as ResumeHelper;
use File;
use App\Http\Models\Candidate;

class AdminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
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
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$login = Session::get('admin.login');
		if(!$login){
			return redirect('admin/login');
		}
		$userRecord = Session::get('admin');
		return view('admin',$userRecord);
	}
	
	public function login()
	{
		$userRecord = Session::get('admin',[]);
		return view('admin.login',$userRecord);
	}
	public function import()
	{
		$login = Session::get('admin.login');
		if(!$login){
			return redirect('admin/login');
		}
		
		$userRecord = Session::get('admin',[]);
		return view('admin.import',$userRecord);
	}
	public function logout()
	{
		Auth::logout();
		Session::flush();
		return redirect('admin');
	}
	public function loginProcess(AdminLoginRequest $request)
	{
		$formData = $request->input();
		
		$email = trim($formData['email']);
		$password = trim($formData['password']);
		
		
		$credentials = [
				'email'    => $email,
				'password' => $password,
		];
		
		if (Auth::once($credentials)) {
			$userRecord = Auth::user();
			if($userRecord->role_id==2){
				Session::put('admin.user',$userRecord);
				Session::put('admin.login',true);
				return redirect('admin');
			} else {
				Auth::logout();
				return view('admin/login',array('message'=>'Please use Agency Admin credentials'));
			}
			
		} else {
			return view('admin/login',array('message'=>'Invalid Email or Password.'));
		}
	}
	public function importProcess(Request $request){
		
		$file = $request->file('candidates');
		$src_ext = $file->getClientOriginalExtension();
		$reader = Reader::createFromFileObject($file->openFile());
		
		$userRecord = Session::get("admin.user");
		
		$i=0;
		$base_path = base_path();
		foreach ($reader as $index => $row) {
			
			if($i>0){
				$id = $row[0];
				//$list = scandir($base_path."/resumes");
				$list = glob($base_path."/resumes/".$id."_*.*",GLOB_NOSORT);
				foreach($list as $file)
				{
					
					$extension = File::extension($file);
					$file_name = File::name($file);
					
					$resume = new ResumeHelper($file,$extension);
					$resume_content = $resume->parseResume();
					$candidate_data = Candidate::where("candidate_id","=",$row[0])->get();
					$candidate = false;
					if($candidate_data){
						$candidate_data = json_decode(json_encode($candidate_data));
						if(count($candidate_data)>0){
							$id = $candidate_data[0]->id;
							$candidate = Candidate::find($id);
						}
					}
					if(!$candidate)
						$candidate = new Candidate();
					$candidate->candidate_id = $row[0];
					$candidate->first_name = $row[1];
					$candidate->last_name = $row[2];
					$candidate->email = $row[3];
					$candidate->agency_id = $userRecord->agency_id;
					
					$candidate->address1 = $row[5];
					
					$candidate->city = $row[7];
					$candidate->state = $row[8];
					$candidate->zip = $row[9];
					
					$candidate->phone_number = ($row[14]!="")?$row[14]:$row[11];
					
					$candidate->salary = $row[18];
					$candidate->resume_file = $file_name.'.'.$extension;
					$candidate->resume_content = $resume_content;
					$candidate->salary = $row[18];
					
					$candidate->save();
				}
			}
			$i++;
		}
		$userRecord = Session::get('admin');
		$userRecord['message'] = "Successfully Imported.";
		return view('admin/import',$userRecord);
	}
}
