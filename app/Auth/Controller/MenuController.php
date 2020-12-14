<?php


namespace App\Auth\Controller;


use App\Auth\Model\Menu;
use App\Auth\Service\MenuService;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\PutApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;

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

    public function getList() {
        $page = $this->request->input('page', 1);
        $pageSize = $this->request->input('pageSize', 10);
        $pageSize = $pageSize > config('app.max_page_size') ? config('app.max_page_size') : $pageSize;
        $seachText = $this->request->input('searchText', '');
        $data = $this->menuService->getList($seachText, $page, $pageSize);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function toTree() {
        $data = $this->menuService->toTree();

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

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


    public function getLeftMenu() {
        $menus = [
            [
                "name" => "system",
                "path" => "/system",
                "component" => "Main",
                "parent_code" => '0',
                "children" => [
                    [
                        "name" => "system_user",
                        "path" => "/system_user",
                        "component" => "/system/user/system_user",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md-person",
                            "notCache" => false,
                            "title" => "用户管理",
                            "permission" => [
                                "system_user_add",
                                "system_user_edit"
                            ]
                        ]
                    ],
                    [
                        "name" => "system_user_group",
                        "path" => "/system_user_group",
                        "component" => "/system/user-group/system_user_group",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md-person",
                            "notCache" => false,
                            "title" => "用户组管理",
                            "permission" => [
                                "system_user_add",
                                "system_user_edit"
                            ]
                        ]
                    ],
                    [
                        "name" => "system_role",
                        "path" => "/system_role",
                        "component" => "/system/role/system_role",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md-contacts",
                            "notCache" => false,
                            "title" => "角色管理",
                            "permission" => [
                                "system_role_add",
                                "system_role_edit"
                            ]
                        ]
                    ],
                    [
                        "name" => "system_menu",
                        "path" => "/system_menu",
                        "component" => "/system/menu/system_menu",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md-list",
                            "notCache" => false,
                            "title" => "菜单管理",
                            "permission" => [
                                "system_add_route"
                            ]
                        ]
                    ],
                    [
                        "name" => "system_dict",
                        "path" => "/system_dict",
                        "component" => "/system/dict",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md-archive",
                            "notCache" => false,
                            "title" => "字典管理",
                            "permission" => []
                        ]
                    ],
                    [
                        "name" => "system_log",
                        "path" => "/system-log",
                        "component" => "/system/log/system_log",
                        "meta" => [
                            "hideInMenu" => false,
                            "icon" => "md_bug",
                            "notCache" => false,
                            "title" => "日志管理",
                            "permission" => []
                        ]
                    ]
                ],
                "meta" => [
                    "hideInMenu" => false,
                    "icon" => "logo-buffer",
                    "notCache" => true,
                    "title" => "系统管理"
                ]
            ]
        ];

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $menus);
    }

    public function getLeftMenus() {
        $systemUserId = Context::get('userId', 0);
        $data = $this->menuService->getIviewTreeData($systemUserId);
        // $data = Menu::getMenusBySystemUserId(1);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }


    public function getTreeSelectData() {
        $systemUserId = Context::get('userId', 0);
        $data = $this->menuService->getTreeSelectData($systemUserId);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function addOrUpdate() {
        $jsonArr = $this->request->getParsedBody();
        $jsonArr = json_decode($jsonArr['routeJson'], true);
        $data = $this->menuService->addOrUpdate($jsonArr);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function del() {
        $id = $this->request->input('id');
        $this->menuService->del($id);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }
}