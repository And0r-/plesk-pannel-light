<?php

namespace App\Modules\DomainManager\Controllers;

use App\Modules\DomainManager\Services\DomainService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Domains - v1",
 *     description="Endpoints for managing domains and users."
 * )
 *
 * @OA\Info(
 *     version="1.0.0",
 *     title="Plesk Panel Light API",
 *     description="API documentation for managing Plesk domains and users. For detailed information, refer to the [Plesk API Documentation](https://docs.plesk.com)."
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 *     description="Plesk Panel Light API v1"
 * )
 */
class DomainController
{
    private $domainService;

    public function __construct(DomainService $domainService)
    {
        $this->domainService = $domainService;
    }

    /**
     * @OA\Post(
     *   path="/domains",
     *   summary="Create a new domain with an FTP and System user",
     *   tags={"Domains - v1"},
     *   description="This endpoint creates a new domain and automatically sets up an FTP and System user for file management and server access. The System user is linked to the domain's home directory and has both FTP and optional SSH access (if enabled). For more details, refer to the [Plesk API Documentation](https://docs.plesk.com).",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"domain", "ftp_user", "password"},
     *           @OA\Property(property="domain", type="string", example="example.com"),
     *           @OA\Property(property="ftp_user", type="string", example="ftpuser"),
     *           @OA\Property(property="password", type="string", example="strongpassword123!")
     *       )
     *   ),
     *   @OA\Response(
     *       response=201,
     *       description="Domain successfully created",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Domain successfully created"),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="id", type="integer", example=23),
     *               @OA\Property(property="guid", type="string", example="9259dd1e-3f54-46c7-87cd-1df5e7d1e63c"),
     *               @OA\Property(property="name", type="string", example="example.com")
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *       response=422,
     *       description="Validation error",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="The domain field is required."),
     *           @OA\Property(property="errors", type="object",
     *               @OA\Property(property="domain", type="array",
     *                   @OA\Items(type="string", example="The domain field is required.")
     *               )
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *       response=500,
     *       description="Internal server error",
     *       @OA\JsonContent(
     *           @OA\Property(property="error", type="string", example="Failed to create domain."),
     *           @OA\Property(property="error_id", type="string", example="PLESK_API_ERROR")
     *       )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'domain' => 'required|string',
            'ftp_user' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        return $this->domainService->createDomain($data);
    }

    /**
     * @OA\Get(
     *   path="/domains",
     *   summary="List all domains",
     *   tags={"Domains - v1"},
     *   description="This endpoint retrieves all domains managed by the Plesk server. For more details, refer to the [Plesk API Documentation](https://docs.plesk.com).",
     *   @OA\Response(
     *       response=200,
     *       description="List of domains",
     *       @OA\JsonContent(
     *           @OA\Property(property="data", type="array",
     *               @OA\Items(
     *                   @OA\Property(property="id", type="integer", example=1),
     *                   @OA\Property(property="creationDate", type="string", example="2025-01-09T12:34:56Z"),
     *                   @OA\Property(property="name", type="string", example="example.com"),
     *                   @OA\Property(property="asciiName", type="string", example="example.com"),
     *                   @OA\Property(property="status", type="string", example="0"),
     *                   @OA\Property(property="ipAddresses", type="array", @OA\Items(type="string")),
     *                   @OA\Property(property="enabled", type="boolean", example=true)
     *               )
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *       response=500,
     *       description="Internal server error",
     *       @OA\JsonContent(
     *           @OA\Property(property="error", type="string", example="Failed to retrieve domains."),
     *           @OA\Property(property="error_id", type="string", example="PLESK_API_ERROR")
     *       )
     *   )
     * )
     */
    public function index()
    {
        return $this->domainService->getDomains();
    }
}
