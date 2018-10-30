<?php
namespace AKlump\PostCommit;

/**
 * Represents a GitHub Translator.
 */
class GitHub extends Translator {
  
  public function isUnderstood() {
    if (!($json = json_decode($this->getContent()))) {
      return FALSE;
    }
    if (isset($json->sender->login) && isset($json->repository->full_name)) {
      $this->setUsername($json->sender->login);
      $this->setRepoName($json->repository->full_name);

      return TRUE;
    }

    return FALSE;
  }
}