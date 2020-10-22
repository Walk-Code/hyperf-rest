<?php
/**
 * LoginController.php
 * description
 * created on 2020/10/20 11:12
 * created by walk-code
 */

namespace App\Auth\Controller;


use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

class LoginController {

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    public function postLogin() {

    }



}