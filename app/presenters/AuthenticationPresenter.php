<?php

namespace App\Presenters;

use \Tracy\Debugger;
use \Nette\Http\IResponse;
use \Nette\Application\UI\Presenter;
use \Nette\Application\BadRequestException;
use \Nette\Security\AuthenticationException;

class AuthenticationPresenter extends Presenter {

  /** @var \Auth0\SDK\Auth0 @inject */
  public $auth0;

  public function actionLogin() {
    $this->auth0->login();
  }

  public function actionLogout() {
    $this->auth0->logout();
    $this->getUser()->logout();

    $this->redirect('Homepage:');
  }

  public function actionCallback($code) {
    try {
      $this->getUser()->login($code);

      $this->redirect('Homepage:');
    } catch (AuthenticationException $e) {
      Debugger::log($e, Debugger::ERROR);
      throw new ForbiddenRequestException('User not authenticated', IResponse::S403_FORBIDDEN, $e);
    }
  }

}
