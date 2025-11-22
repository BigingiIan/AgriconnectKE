<?php

namespace Tests\Feature;

use Tests\TestCase;

class PresentationSafeTests extends TestCase
{
    /**
     * Test home page is accessible (no DB)
     */
    public function test_home_page_loads_without_db()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test products page is accessible (no DB)
     */
    public function test_products_page_loads_without_db()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    /**
     * Test about page is accessible (no DB)
     */
    public function test_about_page_loads_without_db()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /**
     * Test contact page is accessible (no DB)
     */
    public function test_contact_page_loads_without_db()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * Test login and register pages are accessible (no DB required to view forms)
     */
    public function test_auth_pages_load_without_db()
    {
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    /**
     * Test search and category pages load without DB
     */
    public function test_search_and_category_pages_load_without_db()
    {
        $this->get('/search?q=test')->assertStatus(200);
        $this->get('/products/category/vegetables')->assertStatus(200);
    }

    /**
     * Test homepage contains app name (presentation content check)
     */
    public function test_homepage_contains_app_name()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AgriConnect', false);
    }
}
