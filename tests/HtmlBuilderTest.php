<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Format\HtmlBuilder;

class HtmlBuilderTest extends TestCase {

    /** @test */
    public function DocumentHasDoctypeAndNoXmlDeclaration() {

        $html = new HtmlBuilder();
        $html->AddToBody(HtmlBuilder::P('Hello'));

        $this->assertSame('<!DOCTYPE html>'."\n".'<html><head></head><body><p>Hello</p></body></html>', $html->Render());
    }

    /** @test */
    public function TextIsEscaped() {

        $this->assertSame('<p>1 &lt; 2 &amp; 3</p>', HtmlBuilder::P('1 < 2 & 3')->Render());
    }

    /** @test */
    public function SettersOverwrite() {

        $link = HtmlBuilder::A('x', '/a')->SetLink('/b')->SetClass('one')->SetClass('two');

        $this->assertSame('<a href="/b" class="two">x</a>', $link->Render());
    }

    /** @test */
    public function VoidTagsStayVoidAndEmptyOnesClose() {

        $this->assertSame('<br>', HtmlBuilder::CreateElement('br')->Render());
        $this->assertSame('<div></div>', HtmlBuilder::Div()->Render());
    }

    /** @test */
    public function TableIsBuiltWithHeaders() {

        $table = HtmlBuilder::Table([['Alice', 30]], ['Name', 'Age']);

        $this->assertSame(
            '<table><thead><tr><th>Name</th><th>Age</th></tr></thead><tbody><tr><td>Alice</td><td>30</td></tr></tbody></table>',
            $table->Render()
        );
    }
}
