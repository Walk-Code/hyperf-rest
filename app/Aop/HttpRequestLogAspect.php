<?php

namespace App\Aop;

use App\Annotation\HttpRequestLog;
use App\Utils\IpHelper;
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
        $space = str_repeat(' ', 20);
        $this->logger->info('Response Args' . $space . ' : ' . json_encode($proceedingJoinPoint->result));
        $this->logger->info('Time-Consuming' . $space . ' : ' . ($endTime - $startTime) . 'ms');
        $this->after();

        return $result;
    }

    private function before() {
        $uri = $this->request->path();
        $url = $this->request->url();
        $method = $this->request->getMethod();
        $ip = IpHelper::getIP();
        $delimiter = str_repeat('=', 30);
        $space = str_repeat(' ', 20);
        $this->logger->info($delimiter . 'start' . $delimiter);
        $this->logger->info('URL' . $space . ' : ' . $url);
        $this->logger->info('HTTP Method' . $space . ' : ' . $method);
        $this->logger->info('IP' . $space . ' : ' . $ip);
        $this->logger->info('Request Args' . $space . ' : ' . json_encode($this->request->all()));
    }

    private function after() {
        $delimiter = str_repeat('=', 30);
        $this->logger->info($delimiter . 'end' . $delimiter);
    }
}