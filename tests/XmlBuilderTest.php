<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Format\XmlBuilder;
use Vosiz\VaTools\Format\Xml\XmlException;

class XmlBuilderTest extends TestCase {

    /** @test */
    public function TextAndAttributeValuesAreEscaped() {

        $el = XmlBuilder::CreateElement('a', '<b>Tom & "Jerry"</b>');
        $el->AddAtts(['t' => 'say "hi" <x>']);

        $this->assertSame('<a t="say &quot;hi&quot; &lt;x&gt;">&lt;b&gt;Tom &amp; "Jerry"&lt;/b&gt;</a>', $el->Render());
        $this->assertSame('<b>Tom & "Jerry"</b>', $el->GetText());
    }

    /** @test */
    public function InvalidNamesAreRefused() {

        $this->expectException(XmlException::class);
        XmlBuilder::CreateElement('a b><x');
    }

    /** @test */
    public function InvalidAttributeNameIsRefused() {

        $this->expectException(XmlException::class);
        XmlBuilder::CreateElement('a')->AddAtts(['on"x' => 1]);
    }

    /** @test */
    public function AttributeSetAgainIsOverwritten() {

        $el = XmlBuilder::CreateElement('a')->AddAtts(['x' => 1])->AddAtts(['x' => 2]);

        $this->assertSame('<a x="2"/>', $el->Render());
    }

    /** @test */
    public function ElementHasOneParentOnly() {

        $first  = XmlBuilder::CreateElement('first');
        $second = XmlBuilder::CreateElement('second');
        $child  = XmlBuilder::CreateElement('child', 'x');

        $child->SetParent($first)->SetParent($first)->SetParent($second);

        $this->assertSame('<first/>', $first->Render());
        $this->assertSame('<second><child>x</child></second>', $second->Render());
        $this->assertSame($second, $child->GetParent());
    }

    /** @test */
    public function ZeroIsValidId() {

        $this->assertSame(0, XmlBuilder::CreateElement('a', '', 0)->GetGuid());
        $this->assertSame('0', XmlBuilder::CreateElement('a', '', '0')->GetGuid());
    }

    /** @test */
    public function DocumentStartsWithDeclarationUnlessSwitchedOff() {

        $builder = new XmlBuilder('root');

        $this->assertSame('<?xml version="1.0" encoding="UTF-8"?>'."\n".'<root/>', $builder->Render());
        $this->assertSame('<root/>', $builder->SetDeclaration(false)->Render());
    }
}
