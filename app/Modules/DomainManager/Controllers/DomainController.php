<?php

namespace App\Modules\DomainManager\Controllers;

use App\Modules\DomainManager\Services\DomainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ZxcvbnPhp\Zxcvbn;
use App\Helpers\ResponseHelper;


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
    private $domainService, $responseHelper;

    public function __construct(DomainService $domainService, ResponseHelper $responseHelper)
    {
        $this->domainService = $domainService;
        $this->responseHelper = $responseHelper;
    }


    /**
     * @OA\Post(
     *   path="/domains",
     *   summary="Create a new domain with an FTP and System user",
     *   tags={"Domains - v1"},
     *   description="This endpoint creates a new domain and automatically sets up an FTP and System user for file management and server access. The System user is linked to the domain's home directory and has both FTP and optional SSH access (if enabled). For more details, refer to the [Plesk API Documentation](https://docs.plesk.com).",
     *   @OA\RequestBody(
     *       required=true,
     *         @OA\JsonContent(
     *             required={"domain", "ftp_user", "password"},
     *             @OA\Property(property="domain", type="string", example="example.com", description="The domain name to be created. Must be a valid domain format."),
     *             @OA\Property(property="ftp_user", type="string", example="ftpuser", description="FTP username for the domain. Only lowercase letters, numbers, '.', '_' and '-' are allowed."),
     *             @OA\Property(property="password", type="string", example="strongpassword123!", description="Password for the FTP and system user. Must be at least 8 characters long and pass complexity requirements (e.g., not commonly used passwords).")
     *         )
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
        $validator = Validator::make($request->all(), [
            'domain' => ['required', 'string', 'regex:/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\\.)+[a-z]{2,}$/i'],
            'ftp_user' => ['required', 'string', 'regex:/^[a-z0-9._-]+$/'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Custom password validation using zxcvbn for complexity checking
        if ($validator->passes()) {
            $zxcvbn = new Zxcvbn();
            $passwordStrength = $zxcvbn->passwordStrength($request->password);

            if ($passwordStrength['score'] < 3) {
                $this->responseHelper->validationError(
                    [
                        'password' => ['Password complexity is insufficient. Please choose a stronger password.']
                    ],
                    'The password is too weak.'
                );
            }
        }

        $validator->validate();

        $data = $validator->validated();
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

    /**
     * @OA\Post(
     *   path="/domains/{domain_id}/status",
     *   summary="Update the status of a domain",
     *   tags={"Domains - v1"},
     *   description="This endpoint updates the status of a domain (active or disabled) in the Plesk system. The status is set using the Plesk API.",
     *   @OA\Parameter(
     *       name="domain_id",
     *       in="path",
     *       required=true,
     *       description="The ID of the domain to be updated.",
     *       @OA\Schema(type="integer", example=33)
     *   ),
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"status"},
     *           @OA\Property(property="status", type="string", enum={"active", "disabled"}, example="disabled")
     *       )
     *   ),
     *   @OA\Response(
     *       response=200,
     *       description="Status successfully updated",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Status successfully updated"),
     *           @OA\Property(property="data", type="boolean", example=true)
     *       )
     *   ),
     *   @OA\Response(
     *       response=403,
     *       description="Forbidden - Protected domain cannot be disabled",
     *       @OA\JsonContent(
     *           @OA\Property(property="error", type="string", example="The selected domain cannot be disabled as it is critical for system operation.")
     *       )
     *   ),
     *   @OA\Response(
     *       response=422,
     *       description="Validation error",
     *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="The status must be either active or disabled."),
     *           @OA\Property(property="errors", type="object",
     *               @OA\Property(property="status", type="array",
     *                   @OA\Items(type="string", example="The status must be either active or disabled.")
     *               )
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *       response=500,
     *       description="Internal server error",
     *       @OA\JsonContent(
     *           @OA\Property(property="error", type="string", example="Failed to update domain status"),
     *           @OA\Property(property="plesk_error_id", type="integer", example=1013),
     *           @OA\Property(property="plesk_error_message", type="string", example="Webspace does not exist")
     *       )
     *   )
     * )
     */
    public function updateStatus(Request $request, $domain_id)
    {
        $data = $request->validate([
            'status' => 'required|string|in:active,disabled',
        ], [
            'status.in' => 'The status must be either active or disabled.',
        ]);

        // Protected domains specified in .env cannot be disabled
        if (in_array($domain_id, config('app.protected_domain_ids'))) {
            return response()->json([
                'error' => 'The selected domain cannot be disabled as it is critical for system operation.',
            ], 403);
        }

        return $this->domainService->updateStatus($domain_id, $data['status']);
    }
}
