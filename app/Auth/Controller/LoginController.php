<?php
/**
 * LoginController.php
 * description
 * created on 2020/10/20 11:12
 * created by walk-code
 */

namespace App\Auth\Controller;


use App\Auth\Service\LoginService;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use App\Jobs\Job;
use App\Utils\AuthorizationHelper;
use App\Utils\JWTHepler;
use Firebase\JWT\JWT;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="系统后台登录", description="系统登录")
 * @ApiDefinitions({
 * @ApiDefinition(name="LoginOkResponse", properties={
"status|状态码": 200,
 *     "message|响应信息": "operation successful.",
 *     "data": {{}}
 *     }),
 *  @ApiDefinition(name="LoginFailResponse", properties={
"status|状态码": 500,
 *      "message|响应信息": "operation fail."
 *     }),
 *  @ApiDefinition(name="LoginValidateFailResponse", properties={
"status|状态码": 500,
 *      "message|响应信息": "Params is invalid."
 *     }),
 * })
 */
class LoginController {

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    /**
     * @Inject
     * @var LoginService
     */
    private $loginService;

    /**
     * @Inject
     * @var JWTHepler
     */
    private $jwtHelper;

    /**
     * @author walk-code
     * @PostApi(path="/system/user/login", description="系统用户登录")
     * @Query(key="page", rule="integer", default="1", description="页码")
     * @Query(key="pageSize", rule="integer", default="10", description="分页数")
     * @ApiResponse(code="200", description="获取列表信息", schema={"status":200,"message":"operation successful.","data":{"current_page":1,"data":{{}},"first_page_url":"\/?page=1","from":null,"last_page":1,"last_page_url":"\/?page=1","next_page_url":null,"path":"\/","per_page":10,"prev_page_url":null,"to":null,"total":0}})
     */
    public function postLogin() {
        $username = $this->request->input('username');
        $passwrod = $this->request->input('password');

        $systemUser = $this->loginService->loginValidate($username, $passwrod);
        $jwt = $this->jwtHelper::getInstance()->setUserId($systemUser->id)->createToken($systemUser);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $jwt);
    }
}