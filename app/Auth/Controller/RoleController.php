<?php


namespace App\Auth\Controller;


use App\Auth\Service\RoleService;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

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

    public function getList(){
        $name = $this->request->input('searchText', '');
        $page = $this->request->input('page', 1);
        $pageSize = $this->request->input('pageSize', 10);
        $pageSize = $pageSize > config('app.max_page_size') ? config('app.max_page_size') : $pageSize;
        $data = $this->roleService->getList($name, $page, $pageSize);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function create() {
        $jsonArr = $this->request->getParsedBody();
        $this->roleService->create($jsonArr);
    }

}