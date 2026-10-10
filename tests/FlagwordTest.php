<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Structure\Flagword;
use Vosiz\VaTools\Structure\FlagException;

class FlagwordTest extends TestCase {

    /** @test */
    public function SizeInBytesGivesThatManyFlags() {

        $fword = new Flagword(2 * PHP_INT_SIZE);

        $this->assertFalse($fword->ByIndex(2 * PHP_INT_SIZE * 8 - 1)->IsSet());
        $this->assertSame(PHP_INT_SIZE, $fword->GetFlagwordUnitSize());

        $this->expectException(FlagException::class);
        $fword->ByIndex(2 * PHP_INT_SIZE * 8);
    }

    /** @test */
    public function FlagsGoToIntegerAndBack() {

        $fword = new Flagword();
        $fword->SetFlag(0);
        $fword->SetFlag(3);

        $this->assertSame(9, $fword->ToInt());

        $other = (new Flagword())->FromInt(6);

        $this->assertFalse($other->ByIndex(0)->IsSet());
        $this->assertTrue($other->ByIndex(1)->IsSet());
        $this->assertTrue($other->ByIndex(2)->IsSet());
        $this->assertSame(6, $other->ToInt());
    }

    /** @test */
    public function MoreLayersCannotBeAnInteger() {

        $this->expectException(FlagException::class);
        (new Flagword(2 * PHP_INT_SIZE))->ToInt();
    }
}
