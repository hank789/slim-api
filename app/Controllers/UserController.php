<?php namespace Ylc\AppBackendApi\Controllers;


/**
 * @SWG\Resource(
 *     apiVersion="0.1",
 *     swaggerVersion="1.2",
 *     resourcePath="/user",
 *     basePath="http://localhost/app-backend-api/public"
 *     )
 *
 */
class UserController extends Controller
{
    /**
     * @SWG\Api(
     *   path="/user/{id}",
     *   description="Operations about user",
     *   @SWG\Operation(
     *      method="GET", summary="Find user by ID", notes="Returns a user based on ID",
     *      @SWG\Parameter(
     *       name="id",
     *       description="ID of user that needs to be fetched",
     *       required=true,
     *       type="integer",
     *       format="int64",
     *       paramType="path",
     *       minimum="1.0",
     *       allowMultiple=false
     *     ),
     *      @SWG\ResponseMessage(code=404, message="User not found"),
     *      @SWG\ResponseMessage(code=200, message="User found")
     *   )
     * )
     * @return Slim\Http\Response
     */
    public function findById($id){
        $rules = [
        'id' => 'required'
            ];
        $this->validate(['id'=>$id], $rules);
        
        //以下是验证通过的情况下
        return $this->json(['status' => $id]);
    }
    
    public function doLogin(){
        $rules = [
        'username' => 'required',
        'password' => 'required'
        ];
        
        $this->validate($this->request->post(), $rules);
        
        //以下是验证通过的情况下
        return $this->json(['status' => 'validation passes.']);
    }
    
    public function doRegister(){
        
    }
}