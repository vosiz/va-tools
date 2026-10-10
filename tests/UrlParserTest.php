<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Parser\UrlParser;
use Vosiz\VaTools\Parser\UrlStructure;

class UrlParserTest extends TestCase {

    /** @test */
    public function PartsComeFromPathAfterIgnoredPrefix() {

        $struct = UrlStructure::Create('apikor/api', ['version', 'format', 'module']);
        $parser = new UrlParser($struct, '/apikor/api/v1/json/users?limit=5');

        $this->assertSame('v1', $parser->GetPartByKey('version'));
        $this->assertSame('json', $parser->GetPartByKey('format'));
        $this->assertSame('users', $parser->GetPartByKey('module'));
        $this->assertSame('v1/json/users', $parser->GetFullIgnored());
    }

    /** @test */
    public function HostIsNotAPart() {

        $_SERVER['HTTP_HOST'] = 'localhost:8080';
        $parser = new UrlParser(UrlStructure::Create('', ['first']), '/one/two');
        unset($_SERVER['HTTP_HOST']);

        $this->assertSame('one', $parser->GetPartByKey('first'));
        $this->assertSame('localhost:8080/one/two', $parser->GetFull());
    }

    /** @test */
    public function WorksOutsideWebRequest() {

        $parser = new UrlParser(UrlStructure::Create('', ['first']));

        $this->assertSame('', $parser->GetPartByKey('first'));
    }
}
