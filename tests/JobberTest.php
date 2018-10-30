<?php
/**
 * @file
 * PHPUnit tests for the Jobber class
 */

namespace AKlump\PostCommit;

class JobberTest extends \PHPUnit_Framework_TestCase {

  public function testGetJob() {
    $temp = dirname(__FILE__) . '/testlogs/get_test.txt';
    $obj = new Jobber($temp);
    file_put_contents($temp, "do\nre\nmi");
    $this->assertSame(array('do', 're', 'mi'), $obj->getJobs());
    $this->assertSame('do', $obj->takeNextJob());
    $this->assertSame(array('re', 'mi'), $obj->getJobs());
    $this->assertSame('re', $obj->takeNextJob());
    $this->assertSame(array('mi'), $obj->getJobs());
    $this->assertSame('mi', $obj->takeNextJob());
    $this->assertSame(array(), $obj->getJobs());
    $this->assertNull($obj->takeNextJob());
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
