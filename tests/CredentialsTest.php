<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Structure\Credentials;

class CredentialsTest extends TestCase {

    /** @test */
    public function ProtectedPasswordIsHiddenButCanBeRevealed() {

        $creds = new Credentials('user', '1234', true);

        $this->assertSame(Credentials::PROT_PASS_PLACEH, $creds->GetPassword());
        $this->assertSame('1234', $creds->RevealPassword());
    }

    /** @test */
    public function AuthComparesUserAndPassword() {

        $creds = new Credentials('user', '1234', true);

        $this->assertTrue($creds->Auth('user', '1234'));
        $this->assertFalse($creds->Auth('user', '******'));
        $this->assertFalse($creds->Auth('other', '1234'));
    }
}
