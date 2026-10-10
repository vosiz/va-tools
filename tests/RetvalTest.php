<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Retval;
use Vosiz\VaTools\RetvalType;

class RetvalTest extends TestCase {

    /** @test */
    public function PartsAreReadable() {

        $retval = Retval::Warning('not a %s problem', 'severe');

        $this->assertSame('not a severe problem', $retval->GetMessage());
        $this->assertSame('Warning', $retval->GetTypeName());
        $this->assertInstanceOf(RetvalType::class, $retval->GetTypeEnum());
        $this->assertSame('[Retval.Warning]: not a severe problem', (string)$retval);
    }

    /** @test */
    public function MessageWithoutArgumentsIsNotFormatted() {

        $this->assertSame('done at 100% of 5', Retval::Error('done at 100% of 5')->GetMessage());
    }

    /** @test */
    public function FatalExists() {

        $this->assertTrue(Retval::Fatal('x')->Is('fatal'));
        $this->assertTrue(retval_fatal('x')->Is('fatal'));
        $this->assertFalse(Retval::Fatal('x')->Is('error'));
    }

    /** @test */
    public function SuccessIsPositiveSignal() {

        $this->assertSame(0x14, RetvalType::GetEnum('success')->GetValue());
    }
}
