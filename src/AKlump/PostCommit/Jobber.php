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

  public function hasJobs() {
    return @filesize($this->getJobsFile()) > 0;
  }
}