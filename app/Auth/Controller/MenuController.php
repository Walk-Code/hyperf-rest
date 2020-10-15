<?php


namespace App\Auth\Controller;


use App\Auth\Service\MenuService;
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
use Hyperf\Apidog\Annotation\PutApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="角色管理", description="系统菜单增加/删除/修改/编辑")
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
class MenuController {

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    /**
     * @Inject
     * @var MenuService
     */
    private $menuService;

    /**
     * @author walk-code
     * @GetApi(path="/system/menu/getList", description="菜单列表")
     * @Query(key="page", rule="integer", default="1", description="页码")
     * @Query(key="pageSize", rule="integer", default="10", description="分页数")
     * @ApiResponse(code="200", description="获取列表信息", schema={"status":200,"message":"operation successful.","data":{"current_page":1,"data":{{}},"first_page_url":"\/?page=1","from":null,"last_page":1,"last_page_url":"\/?page=1","next_page_url":null,"path":"\/","per_page":10,"prev_page_url":null,"to":null,"total":0}})
     */
    public function getList() {
        $page = $this->request->input('page', 1);
        $pageSize = $this->request->input('pageSize', 10);
        $pageSize = $pageSize > config('app.max_page_size') ? config('app.max_page_size') : $pageSize;
        $title = $this->request->input('searchText', '');
        $data = $this->menuService->getList($title, $page, $pageSize);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    /**
     * @author walk-code
     * @GetApi(path="/system/menu/toTree", description="菜单树")
     */
    public function toTree() {
        $data = $this->menuService->toTree();

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    /**
     * @author walk-code
     * @PostApi(path="/system/menu/add", description="创建菜单")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={"type|类型": "string",
     *     "title|标题": "string",
     *     "parentId|父id": "string",
     *     "id|子id": "string",
     *     "order|排序编号": "string",
     *     "icon|图标": "string",
     *     "url|菜单路由": "string"
     *})
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function create() {
        $jsonArr = $this->request->getParsedBody();
        $this->menuService->createOrEdit($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }

    /**
     * @author walk-code
     * @PutApi(path="/system/menu/edit", description="编辑菜单")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
    "type|类型": "string",
     *     "title|标题": "string",
     *     "parentId|父id": "string",
     *     "id|子id": "string",
     *     "order|排序编号": "string",
     *     "icon|图标": "string",
     *     "url|菜单路由": "string"
     *     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function edit() {
        $jsonArr = $this->request->getParsedBody();
        $this->menuService->createOrEdit($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }

    /**
     * @author walk-code
     * @DeleteApi(path="/system/menu/delete", description="删除菜单")
     * @Header(key="Content-Type|报文类型",rule="string" , description="设置为application/json")
     * @Body(rules={
     * "id|业务id": "string"
     })
     * @ApiResponse(code="200", description="操作成功", schema={"$ref": "RoleOkResponse"})
     * @ApiResponse(code="422", description="参数错误", schema={"$ref": "RoleValidateFailResponse"})
     * @ApiResponse(code="500", description="操作失败", schema={"$ref": "RoleFailResponse"})
     */
    public function delete() {
        $jsonArr = $this->request->getParsedBody();
        $this->menuService->delete($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }
}