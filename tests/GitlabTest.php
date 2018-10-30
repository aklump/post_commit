<?php
/**
 * @file
 * PHPUnit tests for the Gitlab class
 */

namespace AKlump\PostCommit;

class GitlabTest extends \PHPUnit_Framework_TestCase {

  public function testIsUnderstood() {
    $subject = <<<EOD
{
    "repository": {
        "name": "intheloftstudios"
    },
    "user_name": "aklump",
    "ref": "refs/heads/master"
}    
EOD;
    $obj = new Gitlab($subject);
    $this->assertTrue($obj->isUnderstood());
    $this->assertSame('intheloftstudios', $obj->getRepoName());
    $this->assertSame('aklump', $obj->getUsername());
    $this->assertSame('master', $obj->getBranch());

    $obj->setContent('doremi');
    $this->assertFalse($obj->isUnderstood());
    $this->assertEmpty($obj->getRepoName());
    $this->assertEmpty($obj->getUsername());
    $this->assertSame('*', $obj->getBranch());
  }
}
