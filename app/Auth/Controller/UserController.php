<?php


namespace App\Auth\Controller;

use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="系统用户管理", description="系统用户的新增/修改/删除")
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

    public function getList(){
        $name = $this->request->input('name');

    }

}