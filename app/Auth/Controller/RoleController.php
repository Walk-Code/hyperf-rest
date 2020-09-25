<?php


namespace App\Auth\Controller;


use App\Auth\Service\RoleService;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="角色管理", description="角色的新增/修改/删除/添加菜单/删除菜单/添加用户/删除用户接口")
 * @ApiDefinitions({
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
 */
class RoleController {

    /**
     * @Inject
     * @var RoleService
     */
    private $roleService;

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;


    /**
     * @Author walk-code
     * @GetApi(path="/system/role/getList", description="角色列表")
     * @Query(key="searchText", rule="string|nullable", description="角色名称模糊搜索")
     * @Query(key="page", rule="integer", default="1", description="页码")
     * @Query(key="pageSize", rule="integer", default="10", description="分页数")
     * @ApiResponse(code="200", description="获取列表信息", schema={"status":200,"message":"operation successful.","data":{"current_page":1,"data":{{}},"first_page_url":"\/?page=1","from":null,"last_page":1,"last_page_url":"\/?page=1","next_page_url":null,"path":"\/","per_page":10,"prev_page_url":null,"to":null,"total":0}})
     */
    public function getList() {
        $name = $this->request->input('searchText', '');
        $page = $this->request->input('page', 1);
        $pageSize = $this->request->input('pageSize', 10);
        $pageSize = $pageSize > config('app.max_page_size') ? config('app.max_page_size') : $pageSize;
        $data = $this->roleService->getList($name, $page, $pageSize);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    /**
     * @Author walk-code
     * @PostApi(path="/system/role/add", description="添加角色")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
    "name|角色名称": "string",
     *  "slug|角色块": "string"
     *     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function create() {
        $jsonArr = $this->request->getParsedBody();
        $this->roleService->create($jsonArr);
        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }

    /**
     * @Author walk-code
     * @PostApi(path="/system/role/addMenu", description="角色添加菜单")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
    "menuIds|菜单id,多个菜单id使用逗号隔开": "string",
    "roleId|角色id": "integer"
     *     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function addMenus() {
        $jsonArr = $this->request->getParsedBody();
        $this->roleService->addMenus($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }

    /**
     * @Author walk-code
     * @PostApi(path="/system/role/addAdminUser", description="角色添加菜单")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
    "userIds|系统用户id,多个系统用户id使用逗号隔开": "string",
    "roleId|角色id": "integer"
     *     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function addAdminUsers() {
        $jsonArr = $this->request->getParsedBody();
        $this->roleService->addAdminUsers($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }

    /**
     * @Author walk-code
     * @DeleteApi(path="/system/role/delete", description="删除角色")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
     *     "id|角色id": "string"
     *     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function delete() {
        $jsonArr = $this->request->getParsedBody();
        $this->roleService->delete($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }
}