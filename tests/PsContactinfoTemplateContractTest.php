<?php

class PsContactinfoTemplateContractTest extends PsContactinfoTestCase
{
    public function testEmailIsGatedByDisplayFlag()
    {
        $templates = array('ps_contactinfo.tpl', 'ps_contactinfo-rich.tpl');
        foreach ($templates as $template) {
            $contents = file_get_contents(dirname(__DIR__) . '/' . $template);
            $this->assertRegExp('/\$contact_infos\.email\s*&&\s*\$display_email/', $contents, $template);
        }
    }

    public function testNavFallsBackToContactLink()
    {
        $contents = file_get_contents(dirname(__DIR__) . '/nav.tpl');
        $this->assertContains('$contact_infos.phone', $contents);
        $this->assertContains('$urls.pages.contact', $contents);
    }

    public function testRichTemplateRendersDetails()
    {
        $contents = file_get_contents(dirname(__DIR__) . '/ps_contactinfo-rich.tpl');
        $this->assertContains('$contact_infos.details', $contents);
        $this->assertContains('$contact_infos.fax', $contents);
    }
}
