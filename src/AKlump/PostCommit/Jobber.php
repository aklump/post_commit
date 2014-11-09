<?php
namespace AKlump\PostCommit;

/**
 * Represents a Jobber class.
 */
class Jobber {

  protected $fh;

  protected $data = array(
    'jobs_file' => NULL,
  );

  public function __construct($jobs_file = '') {
    $this->setJobsFile($jobs_file);
  }
  
  /**
   * Return a locked file handle to jobs_file.
   *
   * @param  string $mode The file mode for fopen.
   *
   * @return resource||FALSE
   */
  public function getJobsFileHandle($mode = 'r+') {
    if (!empty($this->fh)) {
      fclose($this->fh);
    }    
    if (($this->fh = fopen($this->getJobsFile(), $mode))) {
      if (flock($this->fh, LOCK_EX)) {
        return $this->fh;
      }
    }
  
    return FALSE;
  }

  /**
   * Set the jobs_file.
   *
   * @param string $jobs_file
   *
   * @return $this
   */
  public function setJobsFile($jobs_file) {
    $this->data['jobs_file'] = (string) $jobs_file;
  
    return $this;
  }
  
  /**
   * Return the jobs_file.
   *
   * @return string
   */
  public function getJobsFile() {
    return $this->data['jobs_file'];
  }

  /**
   * Determines if there are any pending jobs.
   *
   * @return boolean
   */
  public function hasJobs() {
    return count($this->getJobs()) > 0;
  }

  /**
   * Returns an array of jobs
   *
   * @return array
   */
  public function getJobs() {
    $jobs = array();
    $jobs = explode(PHP_EOL, file_get_contents($this->getJobsFile()));
    $jobs = array_filter(array_unique($jobs));

    return $jobs;
  }

  /**
   * Truncates the pending jobs file.
   *
   * @return $this
   */
  public function flushJobs() {
    $fh = fopen($this->getJobsFile(), 'w');
    ftruncate($fh, 0);
    fclose($fh);

    return $this;
  }
}