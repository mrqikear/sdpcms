<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/19
 * Time: 10:40
 */

namespace common\components;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Claim\Factory as ClaimFactory;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Parsing\Decoder;
use Lcobucci\JWT\Parsing\Encoder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

/**
 * Class JWT
 * @package common\components
 */

class JWT extends Component
{
    /**
     * @var array 支持的加密算法
     */
    public $supportedAlgs = [
        'HS256' => 'Lcobucci\JWT\Signer\Hmac\Sha256',
        'HS384' => 'Lcobucci\JWT\Signer\Hmac\Sha384',
        'HS512' => 'Lcobucci\JWT\Signer\Hmac\Sha512',
    ];

    public $key; //加密键
    /**
     * 实例化JWT生成器
     * @see [[Lcobucci\JWT\Builder::__construct()]]
     * @return Builder
     */
    public function getBuilder(Encoder $encoder = null, ClaimFactory $claimFactory = null)
    {
        return new Builder($encoder, $claimFactory);
    }

    /**
     * 实例化JWT分析器
     * @see [[Lcobucci\JWT\Parser::__construct()]]
     * @return Parser
     */
    public function getParser(Decoder $decoder = null, ClaimFactory $claimFactory = null)
    {
        return new Parser($decoder, $claimFactory);
    }

    /**
     * 验证JWT并返回一个令牌类
     * function: ValiJwt
     * @return Token|null
     */
    public function ValiJwt($token, $validate = true, $verify = true)
    {
        try {
            $token = $this->getParser()->parse((string)$token);
        } catch (\RuntimeException $e) {
            // Yii::warning("Invalid JWT provided: " . $e->getMessage(), 'jwt');
            return null;
        } catch (\InvalidArgumentException $e) {
            // Yii::warning("Invalid JWT provided: " . $e->getMessage(), 'jwt');
            return null;
        }
        if ($validate && !$this->validateToken($token)) {
            return null;
        }
        if ($verify && !$this->verifyToken($token)) {
            return null;
        }
        return $token;
    }

    /**
     * 数据验证
     * Validate token
     * @param Token $token token object
     * @return bool
     */
    public function validateToken(Token $token, $currentTime = null)
    {
        $data = new ValidationData($currentTime);
        // @todo Add claims for validation
        return $token->validate($data);
    }

    /**
     * Validate token
     * @param Token $token token object
     * @return bool
     */
    public function verifyToken(Token $token)
    {
        $alg = $token->getHeader('alg');
        if (empty($this->supportedAlgs[$alg])) {
            throw new InvalidParamException('Algorithm not supported');
        }
        $signer = Yii::createObject($this->supportedAlgs[$alg]);
        return $token->verify($signer, new Key($this->key));
    }


}