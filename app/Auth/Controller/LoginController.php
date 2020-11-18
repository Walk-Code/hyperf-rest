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
use App\Utils\JWTHepler;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;


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

    public function postLogin() {
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET,OPTIONS');//允许访问的方式
        $username = $this->request->input('username');
        $passwrod = $this->request->input('password');

        $systemUser = $this->loginService->loginValidate($username, $passwrod);
        $jwt = $this->jwtHelper::getInstance()->setUserId($systemUser->id)->createToken($systemUser);

        ResponseHelper::success(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $jwt);
    }
}