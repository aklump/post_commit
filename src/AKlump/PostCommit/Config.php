<?php
namespace AKlump\PostCommit;

/**
 * Represents a Config handler class.
 */
class Config {
  
  protected $data = array(
    'vars' => array(),
    'name' => '*',
    'branch' => '*',
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
    if ($this->getBranch() !== '*' && isset($jobs[$this->getBranch()])) {
      $these_jobs = array_merge($these_jobs, $jobs[$this->getBranch()]);
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
   * Set the branch.
   *
   * @param string $branch
   *
   * @return $this
   */
  public function setBranch($branch) {
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