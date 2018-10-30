<?php
namespace AKlump\PostCommit;

/**
 * Represents the interface for a Translater object.
 */
interface TranslatorInterface {
  /**
   * Set the content, the string was passed in the ping.
   *
   * @param string $content
   *
   * @return $this
   */
  public function setContent($content);
  
  /**
   * Return the content.
   *
   * @return string
   */
  public function getContent();
  
  /**
   * Returns if the content was understood or not
   *
   * @return boolean
   */
  public function isUnderstood();

  /**
   * Set the username.
   *
   * @param string $username
   *
   * @return $this
   */
  public function setUsername($username);
  
  /**
   * Return the username.
   *
   * @return string
   */
  public function getUsername();

  /**
   * Set the repositoryName.
   *
   * @param string $repositoryName
   *
   * @return $this
   */
  public function setRepoName($repositoryName);
  
  /**
   * Return the repositoryName.
   *
   * @return string
   */
  public function getRepoName();

  /**
   * Set the branch.
   *
   * @param string $branch
   *
   * @return $this
   */
  public function setBranch($branch);
  
  /**
   * Return the branch or '*' if unknown.
   *
   * @return string
   */
  public function getBranch();
}