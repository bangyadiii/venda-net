<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRouterRequest;
use App\Models\Router;
use App\Services\MikroTikService;
use Illuminate\Http\Request;
use RouterOS\Query;

class RouterController extends Controller
{
    protected ?MikroTikService $mikroTikService;

    public function __construct(MikroTikService $mikroTikService)
    {
        $this->mikroTikService = $mikroTikService;
    }

    public function index()
    {
        return view('content.router.index');
    }

    public function create()
    {
        return view('content.router.create');
    }

    public function store(AddRouterRequest $request)
    {
        $this->mikroTikService
            ->connectWithCredentials($request->host, $request->username, $request->password);
        $query = new Query('/system/resource/print');
        $response = $this->mikroTikService->getClient()
            ->query($query)->read();

        return \redirect()->route('/', ['info' => $response]);
    }

    public function testConnection(Request $request)
    {
        $this->mikroTikService
            ->connectWithCredentials($request->host, $request->username, $request->password);
        $query = new Query('/system/resource/print');
        $response = $this->mikroTikService->getClient()
            ->query($query)->read();

        return \response()->json($response);
    }

    public function edit(Router $router)
    {
        return view('content.router.edit', \compact('router'));
    }

    public function update(AddRouterRequest $request, Router $router)
    {
        $this->mikroTikService
            ->connectWithCredentials($request->host, $request->username, $request->password);
    }
}
