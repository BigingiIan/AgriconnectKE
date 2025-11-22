<?php
// tests/Feature/MiddlewareExistenceTest.php
namespace Tests\Feature;

use Tests\TestCase;

class MiddlewareExistenceTest extends TestCase
{
    public function test_check_role_middleware_file_exists()
    {
        $middlewarePath = app_path('Http/Middleware/CheckRole.php');
        $this->assertFileExists($middlewarePath, 'CheckRole middleware file does not exist');
    }

    public function test_kernel_has_role_middleware_registered()
    {
        $kernel = app(\App\Http\Kernel::class);
        $middleware = $kernel->getRouteMiddleware();
        
        $this->assertArrayHasKey('role', $middleware, 'Role middleware not registered in Kernel');
        $this->assertEquals(\App\Http\Middleware\CheckRole::class, $middleware['role'], 'Role middleware points to wrong class');
    }

    public function test_middleware_class_can_be_instantiated()
    {
        $middleware = app(\App\Http\Middleware\CheckRole::class);
        $this->assertInstanceOf(\App\Http\Middleware\CheckRole::class, $middleware, 'Cannot instantiate CheckRole middleware');
    }
}