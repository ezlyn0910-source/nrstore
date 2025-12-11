protected $routeMiddleware = [
    // ... other middleware
    'verified.custom' => \App\Http\Middleware\EnsureEmailIsVerified::class,
];