<?php
/**
 * JWTHepler.php
 * description
 * created on 2020/11/3 17:02
 * created by walk-code
 */

namespace App\Utils;


use App\Auth\Service\UserService;
use App\Constants\BusinessCode;
use App\Exception\Core\BusinessException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use PHPUnit\Util\RegularExpressionTest;

class JWTHepler {

    /**
     * 私钥
     *
     * @var
     */
    private $privateKey;

    /**
     * 公钥
     *
     * @var
     */
    private $publicKey;

    /**
     * @Inject
     * @var FileHelper
     */
    public $fileHelper;

    /**
     * @Inject
     * @var Redis
     */
    public $redis;

    /**
     * 用户id
     * @var
     */
    private $userId;

    public static $instance = null;

    public function __construct() {
        $this->privateKey = $this->fileHelper->getContent(BASE_PATH . '/script/ca/rsa_private_key.pem');
        $this->publicKey  = $this->fileHelper->getContent(BASE_PATH . '/script/ca/rsa_public_key.pem');
    }

    /**
     * 保证再一次请求中使用jwt的地方都是一个用户
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/6
     * Time: 15:26
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 创建令牌
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/4
     * Time: 14:43
     *
     * @param array $user
     */
    public function createToken() {
        $payload = [
            'iss' => 'walk_code',
            'aud' => 'walk_code.com',// walk_code.com
            'lat' => time(),
            'exp' => time() + 7600,
            'nbf ' => 0,
            'userId' => $this->getUserId()
        ];
        $jwt = JWT::encode($payload, $this->privateKey, 'RS256');
        $encodeUser = md5(1);
        $this->redis->set('access_token_'.$encodeUser, $jwt);

        return $jwt;
    }

    /**
     * 解析token
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/4
     * Time: 15:19
     *
     * @param $jwt
     */
    public function parseToken($jwt) {
        // TODO 结合rdids对token进行白名单处理
        try{
            $decode = JWT::decode($jwt, $this->publicKey, ['RS256']);

            return (array)$decode;
        } catch (SignatureInvalidException $e) {
            throw new BusinessException(BusinessCode::TOKEN_SIGNATURE_INVALID, BusinessCode::getMessage(BusinessCode::TOKEN_SIGNATURE_INVALID));
        }catch (BeforeValidException $e) {
            throw new BusinessException(BusinessCode::TOKEN_IS_INVALID, BusinessCode::getMessage(BusinessCode::TOKEN_IS_INVALID));
        }catch (ExpiredException $e) {
            throw new BusinessException(BusinessCode::TOKEN_EXPIRED, BusinessCode::getMessage(BusinessCode::TOKEN_EXPIRED));
        }catch (\Exception $e){
            throw new BusinessException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 设置userId
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/9
     * Time: 16:52
     *
     * @param $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }
}