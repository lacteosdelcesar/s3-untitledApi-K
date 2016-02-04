<?php namespace App\Auth;

use App\Entities\User;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Lib\Responders\JsonResponder;

class AuthorizeAction extends Action
{

    public function __construct()
    {
        $this->responder = JsonResponder::class;
    }

    public function execute(array $args)
    {
        $data = json_decode($this->request->getBody());
        $mapper = MySQLEntityManager::createMaper(User::class);
        $user = $mapper->checkUser($data);
        $tokenId = base64_encode(mcrypt_create_iv(32));
        if($user){
            $signer = new Sha256();
            $token = (new Builder())->setId($tokenId, true) // Configures the id (jti claim), replicating as a header item
                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                //->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                //->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
                ->set('user', $user) // Configures a new claim, called "uid"
                //->set('scope', array('read','write','delete')) // Configures a new claim, called "uid"
                ->sign($signer, getenv("SECRET_KEY")) // creates a signature using "testing" as key
                ->getToken();
            return $this->responseInfo = ['body' => ['token' => "$token"], 'status' => self::STATUS_OK];
        } else {
            return $this->responseInfo = [
                'body' => ['msj' => 'credenciales incorrestas'],
                'status' => self::STATUS_UNAUTHORIZED
            ];
        }
    }
}