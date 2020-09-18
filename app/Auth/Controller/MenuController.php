<?php


namespace App\Auth\Controller;


use App\Auth\Service\MenuService;
use App\Constants\ResponseCode;
use App\Exception\Utils\AssertsHelper;
use App\Exception\Utils\ResponseHelper;
use Hyperf\Config\Config;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

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
        $title = $this->request->input('searchText', '');
        $data = $this->menuService->getList($title, $page, $pageSize);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function toTree() {
        $data = $this->menuService->toTree();

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }

    public function create() {
        $jsonObj = $this->request->getParsedBody();
        $this->menuService->createOrEdit($jsonObj);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS));
    }
}