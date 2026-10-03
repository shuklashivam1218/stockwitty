<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_page_renders_with_address_email_and_office_schema(): void
    {
        $response = $this->get('/contact/');

        $response->assertOk();
        $response->assertSee('Send us a message');
        $response->assertSee('B-197, Jaitpur Extension Part 1');
        $response->assertSee('mailto:hello@stockswitty.com', false);
        $response->assertSee('"@type":"FinancialService"', false);
    }

    public function test_phone_and_whatsapp_stay_hidden_until_configured(): void
    {
        config(['sw.contact.phone' => null, 'sw.contact.whatsapp' => null]);
        $this->get('/contact/')->assertDontSee('wa.me/', false)->assertDontSee('tel:', false);

        config(['sw.contact.phone' => '+91 98765 43210', 'sw.contact.whatsapp' => '919876543210']);
        $this->get('/contact/')->assertSee('wa.me/919876543210', false)->assertSee('tel:+919876543210', false);
    }

    public function test_nav_and_footer_link_to_the_contact_page(): void
    {
        $html = $this->get('/wittyscore/')->getContent();

        // Desktop nav, mobile nav and the footer's Company column.
        $this->assertGreaterThanOrEqual(3, substr_count($html, 'href="/contact/"'));
        $this->assertStringNotContainsString('href="/#footer"', $html);
    }
}
