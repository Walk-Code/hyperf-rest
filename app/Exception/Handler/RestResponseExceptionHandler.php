<?php


namespace App\Exception\Handler;


use App\Annotation\HttpRequestLog;
use App\Exception\Core\BusinessException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 业务异常处理类
 * Class RestResponseExceptionHandler
 * @package App\Exception\Handler
 */
class RestResponseExceptionHandler extends ExceptionHandler {
    /**
     * @HttpRequestLog()
     * Handle the exception, and return the specified result.
     */
    public function handle(Throwable $throwable, ResponseInterface $response) {
        # TODO 拓展对应异常处理类
        if ($throwable instanceof BusinessException) {
            $data['status'] = $throwable->getCode();
            $data['message'] = $throwable->getMessage();
            $data['data'] = $throwable->getData();
            $responseBody = json_encode($data, JSON_UNESCAPED_UNICODE);
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withHeader('Content-type', 'application/json; charset=utf-8')->withStatus(200)->withBody(new SwooleStream($responseBody));
        }

        return $response;
    }

    /**
     * Determine if the current exception handler should handle the exception,.
     *
     * @return bool
     *              If return true, then this exception handler will handle the exception,
     *              If return false, then delegate to next handler
     */
    public function isValid(Throwable $throwable): bool {
        return true;
    }
}