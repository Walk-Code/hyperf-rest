<?php


namespace App\Auth\Controller;

use App\Auth\Service\UserService;
use App\Constants\ResponseCode;
use App\Exception\Utils\ResponseHelper;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;

class UserController{

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    public function getList(){
        $name = $this->request->input('name');
    }

    public function getUserInfo(RequestInterface $request) {
        $userId = Context::get('userId', 0);
        $data = $this->userService->find($userId);
        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $data);
    }
}