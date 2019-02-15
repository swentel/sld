<?php

namespace Drupal\sld\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends ControllerBase {

  public function page(UserInterface $user) {

    $turtle = '@prefix foaf: <http://xmlns.com/foaf/0.1/>.
<' . Url::fromRoute('solid.user.profile', ['user' => $user->id()], ['absolute' => TRUE])->toString() . '>
    a foaf:PersonalProfileDocument;
    foaf:primaryTopic <#me> .
<#me>
    a foaf:Person;
    foaf:name "' . Html::escape($user->getAccountName()) . '".';

    $headers = [
      'Access-Control-Allow-Origin' => '*',
      'content-disposition' => 'attachment; filename="profile.ttl"',
      'Content-Type' => 'text/turtle',
    ];

    return new Response($turtle, 200, $headers);
  }

  /**
   * Custom access check to the pod.
   *
   * @param \Drupal\user\UserInterface $user
   *
   * @return \Drupal\Core\Access\AccessResult
   */
  public function access(UserInterface $user) {

    if (sld_enabled($user)) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }

}