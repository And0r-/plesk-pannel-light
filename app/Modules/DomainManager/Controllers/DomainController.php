<?php

namespace App\Modules\DomainManager\Controllers;

use App\Modules\DomainManager\Services\DomainService;
use Illuminate\Http\Request;

class DomainController
{
    private $domainService;

    public function __construct(DomainService $domainService)
    {
        $this->domainService = $domainService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'domain' => 'required|string',
            'ftp_user' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        return $this->domainService->createDomain($data);
    }

    public function index()
    {
        return $this->domainService->getDomains();
    }
}
