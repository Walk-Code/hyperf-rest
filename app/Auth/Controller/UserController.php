<?php


namespace App\Auth\Controller;

use App\Annotation\Auth;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use App\Utils\AuthorizationHelper;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Middleware(ValidateTokenMiddleware  ::class)
 * @ApiVersion(version="v1")
 * @ApiController(tag="系统用户管理", description="系统用户的新增/修改/删除/获取用户信息")
 * * @ApiDefinitions({
 *  @ApiDefinition(name="RoleOkResponse", properties={
"status|状态码": 200,
 *     "message|响应信息": "operation successful.",
 *     "data": {{}}
 *     }),
 *  @ApiDefinition(name="RoleFailResponse", properties={
"status|状态码": 500,
 *      "message|响应信息": "operation fail."
 *     }),
 *  @ApiDefinition(name="RoleValidateFailResponse", properties={
"status|状态码": 500,
 *      "message|响应信息": "Params is invalid."
 *     }),
 * })
 * Class UserController
 * @package App\Auth\Controller
 */
class UserController{

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;


    /**
     * @Inject
     * @var AuthorizationHelper
     */
    private $authHelper;

    /**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;

    public function getList(){
        $name = $this->request->input('name');
    }


    /**
     * @author walk-code
     * @PostApi(path="/system/user/info", description="获取用户信息")
     * @Query(key="page", rule="integer", default="1", description="页码")
     * @Query(key="pageSize", rule="integer", default="10", description="分页数")
     * @ApiResponse(code="200", description="获取列表信息", schema={"status":200,"message":"operation successful.","data":{"current_page":1,"data":{{}},"first_page_url":"\/?page=1","from":null,"last_page":1,"last_page_url":"\/?page=1","next_page_url":null,"path":"\/","per_page":10,"prev_page_url":null,"to":null,"total":0}})
     */
    public function getUserInfo() {
        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), [123]);
    }
}