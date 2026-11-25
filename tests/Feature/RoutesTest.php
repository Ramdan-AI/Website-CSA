<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    public function test_every_route_points_to_an_existing_controller_method(): void
    {
        $missing = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($route) => $route->getActionName())
            ->filter(fn (string $action) => str_contains($action, '@'))
            ->reject(function (string $action) {
                [$class, $method] = explode('@', $action);

                return method_exists($class, $method);
            })
            ->unique()
            ->values()
            ->all();

        $this->assertSame([], $missing);
    }
}
