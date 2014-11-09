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

  public function closeJobsFileHandle() {
    flock($this->fh, LOCK_UN);
    fclose($this->fh);
    unset($this->fh);
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
   * DO NOT USE THIS TO OPEN A FILE, YOU MUST USE getJobsFileHandle() as this
   * insures a lock on the file.
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
   * Returns an array of jobs, you should probabably use takeNextJob().
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
   * Returns the next job from the pending queue and rewrites that file.
   *
   * @return string
   */
  public function takeNextJob() {
    $length = filesize($this->getJobsFile());
    $fh = $this->getJobsFileHandle('r+');
    $jobs = explode(PHP_EOL, fread($fh, $length));
    $jobs = array_filter(array_unique($jobs));
    $next = array_shift($jobs);
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, implode(PHP_EOL, $jobs));
    $this->closeJobsFileHandle();

    return $next;
  }
}