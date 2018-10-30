<?php
namespace AKlump\PostCommit;

/**
 * Represents a Translator base class.
 */
abstract class Translator implements TranslatorInterface {
  protected $data = array(
    'content' => '',
    'username' => '',
    'repositoryName' => '',
    'branch' => '*',
  );

  public function __construct($content) {
    $this->setContent($content);
  }

  public function isUnderstood() {
    return FALSE;
  }
  
  public function setRepoName($repositoryName) {
    $this->data['repositoryName'] = (string) $repositoryName;
  
    return $this;
  }
  
  public function getRepoName() {
    return $this->data['repositoryName'];
  }
  
  public function setUsername($username) {
    $this->data['username'] = (string) $username;
  
    return $this;
  }
  
  public function getUsername() {
    return $this->data['username'];
  }
  
  public function setContent($content) {
    $this->data['content'] = (string) $content;
    $this->setUsername('');
    $this->setRepoName('');
    $this->setBranch('');
  
    return $this;
  }
  
  public function getContent() {
    return $this->data['content'];
  }
  
  /**
   * Set the branch.
   *
   * @param string $branch
   *
   * @return $this
   */
  public function setBranch($branch) {
    if (empty($branch)) {
      $branch = '*';
    }
    $this->data['branch'] = (string) $branch;
  
    return $this;
  }
  
  /**
   * Return the branch.
   *
   * @return string
   */
  public function getBranch() {
    return $this->data['branch'];
  }  
}