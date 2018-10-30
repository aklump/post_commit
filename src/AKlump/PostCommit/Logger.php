<?php
namespace AKlump\PostCommit;

/**
 * Represents a Logger class.
 */
class Logger {

  protected $fh;

  protected $data = array(
    'file_path' => NULL,
    'lines' => array(),
  );

  public function __construct($file_path = '') {
    $this->setFilePath($file_path);
  }

  public function truncate() {
    if ($fh = $this->getFH()) {
      ftruncate($fh, 0);
      fclose($fh);  

      return TRUE;
    }

    return FALSE;
  }

  /**
   * View all lines added during this instantiation.
   *
   * @param  string $eol Optional end of line char
   *
   * @return $this
   */
  public function view($eol = PHP_EOL) {
    return implode($eol, $this->data['lines']) . $eol;
  }

  public function header() {
    $this->append('PHP user: ' . trim(shell_exec('whoami')));
    if (($ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')) {
      $this->append($ip);
    }
    $now = new \DateTime('now', new \DateTimeZone('America/Los_Angeles'));
    $this->append($now->format('r'));

    return $this;    
  }

  public function append($content) {
    $this->data['lines'][] = $content;
    if (($content = trim($content)) && ($fh = $this->getFH())) {
      fwrite($fh, $content . PHP_EOL);
      return TRUE;
    }

    return FALSE;
  }

  protected function getFH() {
    if (!empty($this->fh)) {
      return $this->fh;
    }

    if (($this->fh = fopen($this->getFilePath(), 'a'))) {
      if (flock($this->fh, LOCK_EX)) {
        return $this->fh;
      };
    }
  
    return FALSE;
  }

  /**
   * Print a footer and close the file handle.    
   *
   * @return $this
   */
  public function close() {
    $this->append(str_repeat('-', 80));
    $this->append('');
    fclose($this->fh);

    return $this;
  }  

  /**
  * Set the file_path.
  *
  * @param string $file_path
  *
  * @return $this
  */
  public function setFilePath($file_path) {
   $this->data['file_path'] = (string) $file_path;

   return $this;
  }

  /**
  * Return the file_path.
  *
  * @return string
  */
  public function getFilePath() {
   return $this->data['file_path'];
  }
}