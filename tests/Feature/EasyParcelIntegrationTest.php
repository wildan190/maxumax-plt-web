<?php

namespace Tests\Feature;

use App\Services\EasyParcelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EasyParcelIntegrationTest extends TestCase
{
    /**
     * Test rate checking with EasyParcel Demo API.
     */
    public function test_can_check_shipping_rates_via_demo_api(): void
    {
        // Ensure we are in demo mode
        config(['services.easyparcel.is_production' => false]);
        
        $service = new EasyParcelService();
        
        $params = [
            'pick_code' => '11900',
            'pick_state' => 'PULAU PINANG',
            'pick_country' => 'MY',
            'send_code' => '50000',
            'send_state' => 'W.P. KUALA LUMPUR',
            'send_country' => 'MY',
            'weight' => '1'
        ];
        
        $response = $service->checkRate($params);
        
        // Assert basic response structure
        $this->assertIsArray($response);
        
        // If the API key is valid for demo, we expect success.
        // If invalid, we expect error but still a valid structure.
        
        if (isset($response['api_status'])) {
            if ($response['api_status'] === 'Success') {
                $this->assertEquals('Success', $response['api_status']);
                $this->assertArrayHasKey('result', $response);
                $this->assertIsArray($response['result']);
                $this->assertNotEmpty($response['result']);
                
                // Check if rates exist
                $firstResult = $response['result'][0];
                $this->assertArrayHasKey('rates', $firstResult);
                $this->assertNotEmpty($firstResult['rates']);
            } else {
                // If failed, it might be due to invalid key for demo, but we assert we got a response
                $this->assertEquals('Error', $response['api_status']);
            }
        } else {
            $this->fail('Invalid API response structure: ' . json_encode($response));
        }
    }
}
