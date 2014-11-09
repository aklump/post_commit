<?php
/**
 * @file
 * PHPUnit tests for the Jobber class
 */
namespace AKlump\PostCommit;

require_once dirname(__FILE__) . '/../vendor/autoload.php';

class JobberTest extends \PHPUnit_Framework_TestCase {

  public function testFlushJobs() {
    $temp = dirname(__FILE__) . '/testlogs/flush_test.txt';
    file_put_contents($temp, '/do/this/thing.sh');
    $obj = new Jobber($temp);
    $this->assertTrue($obj->hasJobs());
    $obj->flushJobs();
    $this->assertFalse($obj->hasJobs());
    $this->assertTrue(file_exists($temp));
  }

  public function testGetJobs() {
    $obj = new Jobber(dirname(__FILE__) . '/testlogs/pending.txt');
    $control = array(
      '/home/loftuber/intheloftstudios/bin/pull_master_slim_time.sh',
      '/home/loftuber/intheloftstudios/bin/pull_master_verify_age.sh',
    );
    $this->assertSame($control, $obj->getJobs());
  }

  public function testHasJobs() {
    $obj = new Jobber(dirname(__FILE__) . '/testlogs/pending_none.txt');
    $this->assertFalse($obj->hasJobs());

    $obj = new Jobber(dirname(__FILE__) . '/testlogs/pending.txt');
    $this->assertTrue($obj->hasJobs());
  } 
}