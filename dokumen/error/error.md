terdapat error ketika mengakses pengumpulans
# Error - Internal Server Error

Class "Filament\Tables\Actions\ViewAction" not found

PHP 8.3.30
Laravel 12.53.0
127.0.0.1:8000

## Stack Trace

0 - app\Filament\Resources\Pengumpulans\Tables\PengumpulansTable.php:42
1 - app\Filament\Resources\Pengumpulans\PengumpulanResource.php:32
2 - vendor\filament\filament\src\Resources\Resource.php:70
3 - vendor\filament\filament\src\Resources\Pages\ListRecords.php:213
4 - vendor\filament\tables\src\Concerns\InteractsWithTable.php:47
5 - vendor\laravel\framework\src\Illuminate\Container\BoundMethod.php:36
6 - vendor\laravel\framework\src\Illuminate\Container\Util.php:43
7 - vendor\laravel\framework\src\Illuminate\Container\BoundMethod.php:96
8 - vendor\laravel\framework\src\Illuminate\Container\BoundMethod.php:35
9 - vendor\livewire\livewire\src\Wrapped.php:23
10 - vendor\livewire\livewire\src\Features\SupportLifecycleHooks\SupportLifecycleHooks.php:144
11 - vendor\livewire\livewire\src\Features\SupportLifecycleHooks\SupportLifecycleHooks.php:24
12 - vendor\livewire\livewire\src\ComponentHook.php:19
13 - vendor\livewire\livewire\src\ComponentHookRegistry.php:45
14 - vendor\livewire\livewire\src\EventBus.php:60
15 - vendor\livewire\livewire\src\helpers.php:98
16 - vendor\livewire\livewire\src\Mechanisms\HandleComponents\HandleComponents.php:50
17 - vendor\livewire\livewire\src\LivewireManager.php:73
18 - vendor\livewire\livewire\src\Features\SupportPageComponents\HandlesPageComponents.php:17
19 - vendor\livewire\livewire\src\Features\SupportPageComponents\SupportPageComponents.php:117
20 - vendor\livewire\livewire\src\Features\SupportPageComponents\HandlesPageComponents.php:14
21 - vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
22 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
23 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
24 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
25 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
26 - vendor\filament\filament\src\Http\Middleware\DispatchServingFilamentEvent.php:15
27 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
28 - vendor\filament\filament\src\Http\Middleware\DisableBladeIconComponents.php:14
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
30 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
31 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
32 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
33 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
34 - vendor\laravel\framework\src\Illuminate\Session\Middleware\AuthenticateSession.php:70
35 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
36 - vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
37 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
38 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
39 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
41 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
42 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
44 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
45 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
46 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
47 - vendor\filament\filament\src\Http\Middleware\SetUpPanel.php:19
48 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
49 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
50 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
51 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
52 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
53 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
54 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
55 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
56 - vendor\livewire\livewire\src\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware.php:19
57 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
58 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
59 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
60 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
61 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
62 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
63 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
64 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
65 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
66 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
67 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
68 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:61
69 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
70 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
71 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
72 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
73 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
74 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
75 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
76 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
77 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
78 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
79 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
80 - public\index.php:20
81 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

GET /admin/pengumpulans

## Headers

* **host**: 127.0.0.1:8000
* **connection**: keep-alive
* **sec-ch-ua**: "Not:A-Brand";v="99", "Microsoft Edge";v="145", "Chromium";v="145"
* **sec-ch-ua-mobile**: ?0
* **sec-ch-ua-platform**: "Windows"
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: navigate
* **sec-fetch-user**: ?1
* **sec-fetch-dest**: document
* **referer**: http://127.0.0.1:8000/admin
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: en-US,en;q=0.9
* **cookie**: XSRF-TOKEN=eyJpdiI6ImIzS1Vscysvc2VaV0RKay9xdDMxb1E9PSIsInZhbHVlIjoicHZLSFhqd1ovMnN5VEtSamNTaDJnTmtVMGM3MEhQNXJXYnRQZTJXbFVIZE9XUzhIN0F3azFIL1JnZjBzRjFZcnVoSVY3b3RtK1dab3ZzUFp5V3F1N29IM1l4N2gzNUFEWEVPR0t5MUZpckF3WWhzYUcxNmE4L3RObENXdDYzS3oiLCJtYWMiOiIyNjkwMjM1MWJmZWY0MGUyNzc3YjhhNTlkZGI0MjQzMzAyYzBmOWY0NzFmMThlZmExOGZkYWNkYmVhMDk5MGIwIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6Im9vRHhvUEpXdDNaczRTQldhcVliQ3c9PSIsInZhbHVlIjoia1U0QnY5aHlXMjhEQm1yQmkremVRbHhvSlBvTUxWb0dhUVRsL2diRThaamtQTElVSzVkQTJZMmVGQXJyZ0NHcDg4V01Yei9zZ0pqTHVsVzYyZTFYMmxkTkhMUmRkdDZmM1ltQ2t1eUh1a1lmK2xpdWtXMXlUeVJ6NGlXL0dmTE8iLCJtYWMiOiI1MjM1NGU0ODAyYWM2OTJiNTI0OTdiYjFkMTM3MDEzMmJjOGNlODQ2MzA5OTkyNzBhZGM2NTU0NDM2OTFmYjkwIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Filament\Resources\Pengumpulans\Pages\ListPengumpulans
route name: filament.admin.resources.pengumpulans.index
middleware: panel:admin, Illuminate\Cookie\Middleware\EncryptCookies, Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse, Illuminate\Session\Middleware\StartSession, Filament\Http\Middleware\AuthenticateSession, Illuminate\View\Middleware\ShareErrorsFromSession, Illuminate\Foundation\Http\Middleware\VerifyCsrfToken, Illuminate\Routing\Middleware\SubstituteBindings, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Http\Middleware\Authenticate

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'fLMifneXdxwdXxYN7C7MvZmO8dP9FNEK17fZU4ZV' limit 1 (22.27 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.28 ms)
