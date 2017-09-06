<?php

namespace App\Model;

use \Tracy\Debugger;
use \Auth0\SDK\Auth0;
use \Nette\Security\Identity;
use \Nette\Security\IIdentity;
use \Nette\Security\IAuthenticator;
use \Nette\Security\AuthenticationException;

class Auth0Authenticator implements IAuthenticator {

  /** @var \Auth0\SDK\Auth0 */
  private $auth0;

  public function __construct(Auth0 $auth0) {
    $this->auth0 = $auth0;
  }

  /**
   *  @param $args[0] Authorization Code
   *  @throws AuthenticationException
   */
  public function authenticate(array $args) : IIdentity {
    if (sizeof($args) > 0 && !empty($args[0])) {
      $code = $args[0];

      if ($this->auth0->exchange()) {
        return new Identity($this->auth0->getUser()['email'], NULL, $this->auth0->getUser());
      } else {
        throw new AuthenticationException('Auth0 code not exchanged successfully; user not authenticated.');
      }
    } else {
      throw new AuthenticationException('Auth0 code not provided; user not authenticated.');
    }
  }

}
