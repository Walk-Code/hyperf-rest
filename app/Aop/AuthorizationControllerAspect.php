<?php


namespace App\Aop;


use App\Annotation\Auth;
use App\Constants\BusinessCode;
use App\Constants\ResponseCode;
use App\Exception\Core\BusinessException;
use App\Exception\Utils\AssertsHelper;
use App\Exception\Utils\ResponseHelper;
use App\Utils\AuthorizationHelper;
use App\Utils\JWTHepler;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @Aspect
 * Class AuthorizationControllerAspect
 *
 * @package App\Aop
 */
class AuthorizationControllerAspect extends AbstractAspect {

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    /**
     * @Inject
     * @var JWTHepler
     */
    private $jwtHelper;

    public $annotations = [
        Auth::class
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint) {
        $result = null;

        $isLogin    = $this->authorization->isLogin();
        if (!$isLogin) {
            ResponseHelper::fail(ResponseCode::NO_AUTH, ResponseCode::getMessage(ResponseCode::NO_AUTH));
            return $result;
        }

        # 路由在白名单中放行


        # 路由为根路由放行


        # 校验用户角色


        # 校验路由是否在授权列表


        # 校验路由权限

        AssertsHelper::isTrue(false, '您没有权限访问该功能');
        return $proceedingJoinPoint->process();
    }
}