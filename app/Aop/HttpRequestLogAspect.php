<?php

namespace App\Aop;

use App\Annotation\HttpRequestLog;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Logger\LoggerFactory;

/**
 * @Aspect
 * Class HttpRequestLogAspect
 */
class HttpRequestLogAspect extends AbstractAspect {

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var \Hyperf\Contract\SessionInterface
     */
    protected $session;

    public $annotations = [
        HttpRequestLog::class,
    ];

    public function __construct(LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @return mixed return the value from process method of ProceedingJoinPoint, or the value that you handled
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint) {
        $startTime = microtime(true);
        $this->before();
        $result = $proceedingJoinPoint->process();
        $endTime = microtime(true);
        $space = str_repeat(' ', 21);
        $this->logger->info('Time-Consuming' . $space . ' : ', [($endTime - $startTime) . 'ms']);
        $this->after();

        return $result;
    }

    /**
     * 切入点前执行
     */
    private function before() {
        $url = $this->request->url();
        $method = $this->request->getMethod();
        $ip = $this->getIP();
        $delimiter = str_repeat('=', 30);
        $space = str_repeat(' ', 20);
        $this->logger->info($delimiter . 'start' . $delimiter);
        $this->logger->info('URL            ' . $space . ' : ', [$url]);
        $this->logger->info('IP             ' . $space . ' : ', [$ip]);
        $this->logger->info('HTTP Method    ' . $space . ' : ', [$method]);
        $this->logger->info('Content-Type   ' . $space . ' : ', $this->request->getHeader('Content-Type'));
        $this->logger->info('Headers        ' . $space . ' : ', $this->request->getHeaders());
        $this->logger->info('Server Params  ' . $space . ' : ', $this->request->getServerParams());
        $this->logger->info('Cookies        ' . $space . ' : ', $this->request->getCookieParams());
        $this->logger->info('Request Params ' . $space . ' : ', $this->request->getParsedBody());
    }

    /**
     * 切入点后执行
     */
    private function after() {
        $delimiter = str_repeat('=', 31);
        $this->logger->info($delimiter . 'end' . $delimiter);
    }

    /**
     * 获取用户ip
     * @return mixed
     */
    public function getIP() {
        if (!empty($this->request->getHeader('CLIENT_IP'))) {
            $ip = $this->request->getServerParams()['client_ip'];
        } else if (!empty($this->request->getHeader('X_FORWARDED_FOR'))) {
            # 存在多级代理
            $ip = $this->request->getHeader('X_FORWARDED_FOR');
        } else if (!empty($this->request->getHeader('X_REAL_IP'))) {
            $ip = $this->request->getHeader('X_REAL_IP');
        } else {
            $ip = $this->request->getServerParams()['remote_addr'];
        }

        return $ip;
    }
}