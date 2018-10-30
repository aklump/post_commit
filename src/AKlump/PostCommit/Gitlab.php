<?php
namespace AKlump\PostCommit;

/**
 * Represents a Gitlab Translator.
 */
class Gitlab extends Translator {
  
  public function isUnderstood() {
    if (!($json = json_decode($this->getContent()))) {
      return FALSE;
    }
    if (isset($json->user_name) && isset($json->repository->name) && isset($json->ref)) {
      $this->setUsername($json->user_name);
      $this->setRepoName($json->repository->name);
      $this->setBranch(str_replace('refs/heads/', '', $json->ref));

      return TRUE;
    }

    return FALSE;
  }
}