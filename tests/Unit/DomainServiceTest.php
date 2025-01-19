<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Modules\DomainManager\Services\DomainService;
use CustomPlesk\Client;
use SimpleXMLElement;

class DomainServiceTest extends TestCase
{
    public function test_create_domain_success()
    {
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('ip->get')->once()->andReturn([
            (object) ['ipAddress' => '127.0.0.1']
        ]);
        $pleskClientMock->shouldReceive('webspace->create')->once()->andReturn(
            new \PleskX\Api\Struct\Webspace\Info(new SimpleXMLElement('
                <webspace>
                    <id>1</id>
                    <name>example.com</name>
                </webspace>
            '))
        );

        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        $data = [
            'domain' => 'example.com',
            'ftp_user' => 'ftpuser',
            'password' => 'securepassword123',
        ];

        $response = $service->createDomain($data);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Domain successfully created', $response->getData()->message);
    }


    public function test_create_domain_no_ip_address()
    {
        // Mock Plesk Client ohne verfügbare IP-Adressen
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('ip->get')->once()->andReturn([]);

        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        $data = [
            'domain' => 'example.com',
            'ftp_user' => 'ftpuser',
            'password' => 'securepassword123',
        ];

        // Methode ausführen
        $response = $service->createDomain($data);

        // Assertions
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString('No IP address is available.', $response->getData()->plesk_error_message);
        $this->assertEquals('Domain creation failed', $response->getData()->error);
    }


    public function test_create_domain_invalid_password()
    {
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('ip->get')->once()->andReturn([
            (object) ['ipAddress' => '127.0.0.1']
        ]);
        $pleskClientMock->shouldReceive('webspace->create')
            ->andThrow(new \Exception('Invalid password format'));

        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        $data = [
            'domain' => 'example.com',
            'ftp_user' => 'ftpuser',
            'password' => '123',
        ];

        $response = $service->createDomain($data);

        // Zugriff auf stdClass anpassen
        $responseData = $response->getData();

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('Invalid password format', $responseData->errors->password);
        $this->assertEquals('Domain creation failed', $responseData->error);
    }

    public function test_get_domains_success()
    {
        // Mock Plesk Client
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('webspace->getAll')->once()->andReturn([
            (object) [
                'id' => 1,
                'name' => 'example.com',
                'status' => 'active',
            ],
            (object) [
                'id' => 2,
                'name' => 'test.com',
                'status' => 'disabled',
            ],
        ]);

        // DomainService mit Mocked Client
        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        // Methode ausführen
        $response = $service->getDomains();

        // Assertions
        $responseData = $response->getData();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $responseData->data);
        $this->assertEquals('example.com', $responseData->data[0]->name);
        $this->assertEquals('test.com', $responseData->data[1]->name);
    }

    public function test_get_domains_failure()
    {
        // Mock Plesk Client mit Exception
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('webspace->getAll')
            ->once()
            ->andThrow(new \Exception('Connection to Plesk failed', 1001));

        // DomainService mit Mocked Client
        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        // Methode ausführen
        $response = $service->getDomains();

        // Assertions
        $responseData = $response->getData();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Domain list failed', $responseData->error);
        $this->assertEquals(1001, $responseData->plesk_error_id);
        $this->assertStringContainsString('Connection to Plesk failed', $responseData->plesk_error_message);
    }


    public function test_update_status_success_active()
    {
        // Mock Plesk Client
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('webspace->enable')
            ->with('id', 1)
            ->once()
            ->andReturn(true); // Rückgabewert anpassen

        // DomainService mit Mocked Client
        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        // Methode ausführen
        $response = $service->updateStatus(1, 'active');

        // Assertions
        $responseData = $response->getData();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Status successfully updated', $responseData->message);
        $this->assertTrue($responseData->data);
    }


    public function test_update_status_success_disabled()
    {
        // Mock Plesk Client
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('webspace->disable')
            ->with('id', 1)
            ->once()
            ->andReturn(true); // Rückgabewert anpassen

        // DomainService mit Mocked Client
        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        // Methode ausführen
        $response = $service->updateStatus(1, 'disabled');

        // Assertions
        $responseData = $response->getData();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Status successfully updated', $responseData->message);
        $this->assertTrue($responseData->data);
    }

    public function test_update_status_failure()
    {
        // Mock Plesk Client mit Exception
        $pleskClientMock = Mockery::mock(Client::class);
        $pleskClientMock->shouldReceive('webspace->enable')
            ->with('id', 1)
            ->once()
            ->andThrow(new \Exception('Invalid domain ID', 404));

        // DomainService mit Mocked Client
        $service = new DomainService($pleskClientMock, new \App\Helpers\ResponseHelper());

        // Methode ausführen
        $response = $service->updateStatus(1, 'active');

        // Assertions
        $responseData = $response->getData();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Failed to update domain status', $responseData->error);
        $this->assertEquals(404, $responseData->plesk_error_id);
        $this->assertStringContainsString('Invalid domain ID', $responseData->plesk_error_message);
    }
}
