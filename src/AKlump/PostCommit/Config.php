<?php
namespace AKlump\PostCommit;

/**
 * Represents a Config handler class.
 */
class Config {
  
  protected $data = array(
    'vars' => array(),
    'name' => '*',
    'ref' => '*',
  );

  public function __construct($vars) {
    $this->setVars($vars);
  }

  /**
   * Return a list of jobs based on configuration and environment.
   *
   * @return array Each element is the name of a job to schedule.
   */
  public function getJobs() {
    $conf = $this->getVars();

    // Legacy support from version 0.2
    if (isset($conf['job_cmd'])) {
      $conf['jobs']['*']['*'][] = $conf['job_cmd'];
    }
    
    $jobs = isset($conf['jobs']['*']) ? $conf['jobs']['*'] : array();
    if ($this->getName() !== '*' && isset($conf['jobs'][$this->getName()])) {
      $jobs = array_merge_recursive($jobs, $conf['jobs'][$this->getName()]);
    }

    $these_jobs = isset($jobs['*']) ? $jobs['*'] : array();
    if ($this->getRef() !== '*' && isset($jobs[$this->getRef()])) {
      $these_jobs = array_merge($these_jobs, $jobs[$this->getRef()]);
    }

    return $these_jobs;
  }
  
  /**
   * Set the name.
   *
   * @param string $name
   *
   * @return $this
   */
  public function setName($name) {
    $this->data['name'] = (string) $name;
  
    return $this;
  }
  
  /**
   * Return the name.
   *
   * @return string
   */
  public function getName() {
    return $this->data['name'];
  }
  
  /**
   * Set the ref.
   *
   * @param string $ref
   *
   * @return $this
   */
  public function setRef($ref) {
    $this->data['ref'] = (string) $ref;
  
    return $this;
  }
  
  /**
   * Return the ref.
   *
   * @return string
   */
  public function getRef() {
    return $this->data['ref'];
  }

  /**
   * Set the vars.
   *
   * @param array $vars
   *
   * @return $this
   */
  public function setVars($vars) {
    $this->data['vars'] = (array) $vars;
  
    return $this;
  }
  
  /**
   * Return the vars.
   *
   * @return array
   */
  public function getVars() {
    return $this->data['vars'];
  }
}