<?php

namespace App\Console\Commands;

use App\Services\EasyParcelService;
use Illuminate\Console\Command;

class TestEasyParcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easyparcel:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test EasyParcel Integration';

    /**
     * Execute the console command.
     */
    public function handle(EasyParcelService $easyParcel)
    {
        $this->info('Testing EasyParcel Integration...');
        $this->info('API Key: ' . config('services.easyparcel.api_key'));

        $params = [
            'pick_code' => '11900',
            'pick_state' => 'PULAU PINANG',
            'pick_country' => 'MY',
            'send_code' => '50000',
            'send_state' => 'W.P. KUALA LUMPUR',
            'send_country' => 'MY',
            'weight' => '1'
        ];

        $this->info('Checking Rate for: ' . json_encode($params));

        $result = $easyParcel->checkRate($params);

        $this->info('Result:');
        // print_r($result);
        $this->line(json_encode($result, JSON_PRETTY_PRINT));
        
        if (isset($result['api_status']) && $result['api_status'] === 'Success') {
            $this->info('EasyParcel Integration Successful!');
        } else {
            $this->error('EasyParcel Integration Failed.');
        }
    }
}
