<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Models\Companies;
use App\Http\Models\User;
use App\Http\Requests\CompanyRequest;
use App\Http\Transformers\CompanyTransformer;

class CompanyController extends BaseController
{
    /**
     * @api {get} /company Fetch All Companies.
     * @apiVersion 1.0.0
     * @apiName GetCompanies
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *     ]
     *   }
     *
     * @apiError CompaniesNotFound No Companies found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompaniesNotFound"
     *     }
     */
    public function index()
    {
        $companies = Companies::all();
        return $this->response->collection($companies, new CompanyTransformer);
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
     * @api {post} /companies Create New Company.
     * @apiVersion 1.0.0
     * @apiName CreateCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {String} name Company Name
     * @apiParam {String} city City
     * @apiParam {String} state State
     * @apiParam {String} contact_name Name of Contact person
     * @apiParam {String} contact_email Email of Contact person
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Create Company"
     *     }
     */
    public function store(CompanyRequest $request)
    {
        $companyData = $request->input();
       
        $company = Companies::create($companyData);
        
        return $this->response->item($company,new CompanyTransformer);
    }

    /**
     * @api {get} /companies/:id Fetch Company Details by Id.
     * @apiVersion 1.0.0
     * @apiName GetCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function show($id)
    {
       $company = Companies::findorfail($id);
       return $this->response->item($company,new CompanyTransformer);
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
     * @api {put} /companies/:id Update Company Details by Id.
     * @apiVersion 1.0.0
     * @apiName PutCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     * @apiParam {String} name Company Name
     * @apiParam {String} city City
     * @apiParam {String} state State
     * @apiParam {String} contact_name Name of Contact person
     * @apiParam {String} contact_email Email of Contact person
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function update(CompanyRequest $request, $id)
    {
        $company = Companies::findorfail($id);
        
        $companyData = $request->input();
        
        $company->name = $companyData['name'];
        $company->city = $companyData['city'];
        $company->state = $companyData['state'];
        $company->contact_name = $companyData['contact_name'];
        $company->contact_email = $companyData['contact_email'];
       
        $company->save();
        
        return $this->response->item($company,new CompanyTransformer);
    }

    /**
     * @api {delete} /companies/:id Delete Company by Id
     * @apiVersion 1.0.0
     * @apiName DeleteCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function destroy($id)
    {
        $company = Companies::findorfail($id);
        $company->delete();
        return $this->response->item($company,new CompanyTransformer);
    }
}
