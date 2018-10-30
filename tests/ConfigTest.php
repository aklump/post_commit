<?php
/**
 * @file
 * PHPUnit tests for the Config class
 */

namespace AKlump\PostCommit;

class ConfigTest extends \PHPUnit_Framework_TestCase {

  public function testLegacySupport() {
    $conf = array();
    $conf['job_cmd'] = 'alpha';
    $conf['jobs']['*']['refs/heads/master'] = array('do');
    $obj = new Config($conf);
    $obj->setBranch('refs/heads/master');

    $this->assertSame(array('alpha', 'do'), $obj->getJobs());
  }

  public function testJobsNameOnlySingleBranch() {
    $conf = array();
    $conf['jobs']['*']['refs/heads/master'][] = 're';
    $conf['jobs']['*']['refs/heads/master'][] = 'mi';
    $obj = new Config($conf);

    $obj->setName('jquery.slim_time')->setBranch('refs/heads/develop');
    $this->assertCount(0, $obj->getJobs());
    $control = array('re', 'mi');
    $obj->setBranch('refs/heads/master');
    $this->assertSame($control, $obj->getJobs());
  }

  public function testJobsGlobalNameOnlySingleBranch() {
    $conf = array();
    $conf['jobs']['*']['*'] = array('do');
    $conf['jobs']['jquery.slim_time']['refs/heads/master'] = array('re');
    $conf['jobs']['jquery.running_clock']['*'] = array('mi');
    $obj = new Config($conf);

    $obj->setName('jquery.slim_time')->setBranch('refs/heads/develop');
    $control = array('do');
    $this->assertSame($control, $obj->getJobs());
  }

  public function testJobsNameOnlyAllBranches() {
    $conf = array();
    $conf['jobs']['jquery.slim_time']['*'] = array('re');
    $conf['jobs']['jquery.running_clock']['*'] = array('mi');
    $obj = new Config($conf);
    $obj->setName('jquery.slim_time');

    $control = array('re');
    $this->assertSame($control, $obj->getJobs());
  }

  public function testJobsGlobalAndNameAllBranches() {
    $conf = array();
    $conf['jobs']['*']['*'] = array('do');
    $conf['jobs']['jquery.slim_time']['*'] = array('re');
    $conf['jobs']['jquery.running_clock']['*'] = array('mi');
    $obj = new Config($conf);

    $obj->setName('jquery.slim_time');
    $control = array('do', 're');
    $this->assertSame($control, $obj->getJobs());

    $obj->setName('jquery.running_clock');
    $control = array('do', 'mi');
    $this->assertSame($control, $obj->getJobs());
  }

  // Custom methods and assertions
}
