<?php
/**
 * @file
 * PHPUnit tests for the Github class
 */
namespace AKlump\PostCommit;

require_once dirname(__FILE__) . '/../vendor/autoload.php';

class GithubTest extends \PHPUnit_Framework_TestCase {
  public function testIsUnderstood() {
    $subject = <<<EOD
{
    "repository": {
        "full_name": "aklump/jquery.slim_time"
    },
    "sender": {
        "login": "aklump"
    }
}    
EOD;
    $obj = new GitHub($subject);
    $this->assertTrue($obj->isUnderstood());
    $this->assertSame('aklump/jquery.slim_time', $obj->getRepoName());
    $this->assertSame('aklump', $obj->getUsername());
    $this->assertSame('*', $obj->getBranch());
  
    $obj->setContent('doremi');
    $this->assertFalse($obj->isUnderstood());
    $this->assertEmpty($obj->getRepoName());
    $this->assertEmpty($obj->getUsername());
    $this->assertSame('*', $obj->getBranch());
  }
}