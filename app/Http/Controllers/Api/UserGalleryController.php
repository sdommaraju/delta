<?php

namespace App\Http\Controllers\Api;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Models\User;
use App\Http\Models\UserGallery;
use App\Http\Requests\UserGalleryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Transformers\UserGalleryTransformer;
use LucaDegasperi\OAuth2Server\Authorizer;
class UserGalleryController extends BaseController
{
    public function __construct(){
    
        //$this->middleware('oauth-client',["only"=>["index"]]);
        $this->middleware('oauth-user',["except"=>["index"]]);
    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Authorizer $authorizer)
    {
        $user_id=$authorizer->getResourceOwnerId();
        $gallery = UserGallery::where('user_id','=',$user_id)->get();
        return $this->response->collection($gallery,new UserGalleryTransformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @api {post} /gallery Create New Gallery Item for User.
     * @apiVersion 1.0.0
     * @apiName CreateUserGallery
     * @apiGroup Users Gallery
     * 
     * @apiParam {Integer} user_id User Id.
     * @apiParam {Raw} file gallery raw content
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "user_gallery": {
     *          "user_id": "2",
     *          "mime": "image/jpeg",
     *          "original_filename": "New Doc_1.jpg",
     *          "filename": "php8A3A.tmp.jpg",
     *          "updated_at": "2016-03-17 07:12:57",
     *          "created_at": "2016-03-17 07:12:57",
     *          "id": 2
     *      }
     *     }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "Can not Create User Gallery"
     *     }
     */
    public function store(UserGalleryRequest $request)
    {
        $file = Request::file('file');
		$extension = $file->getClientOriginalExtension();
		Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
		
		$gallery = new UserGallery();
		$gallery->user_id = $request->user_id;
		$gallery->mime = $file->getClientMimeType();
		$gallery->original_filename = $file->getClientOriginalName();
		$gallery->filename = $file->getFilename().'.'.$extension;

		$gallery->save();

		return $gallery;
    }

    /**
     * @api {get} /gallery/{id} Get User Gallery by Id.
     * @apiVersion 1.0.0
     * @apiName GetUserGallery
     * @apiGroup Users Gallery
     * 
     * @apiParam {Integer} id Gallery Id.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *          "user_id": "2",
     *          "mime": "image/jpeg",
     *          "original_filename": "New Doc_1.jpg",
     *          "filename": "php8A3A.tmp.jpg",
     *          "updated_at": "2016-03-17 07:12:57",
     *          "created_at": "2016-03-17 07:12:57",
     *          "id": 2
     *      }
     *     }
     *
     * @apiError Error
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "No User Gallery found"
     *     }
     */
    public function show($id)
    {
        $gallery = UserGallery::findorfail($id);
		return $this->response->item($gallery,new UserGalleryTransformer);
    }
    
    /**
     * @api {get} /gallery/{id}/download Download User Gallery by Id.
     * @apiVersion 1.0.0
     * @apiName DownloadUserGallery
     * @apiGroup Users Gallery
     * 
     * @apiParam {Integer} id Gallery Id
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       Image File
     *     }
     *
     * @apiError Error
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "No User Gallery found"
     *     }
     */
    public function download($id)
    {
        $gallery = UserGallery::findorfail($id);
        $file = Storage::disk('local')->get($gallery->filename);
    
        return response($file)->header('Content-Type', $gallery->mime);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
