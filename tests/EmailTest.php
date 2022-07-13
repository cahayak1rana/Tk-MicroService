<?php

namespace Tests;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use App\CustomMailer\CustomTransport;
use App\CustomMailer\CustomMailer;
use Swift_Message;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class EmailTest extends TestCase
{
    protected function createEmail()
    {
        //$message = new Swift_Message('Test subject', '<body>Message body</body>');
        $message = new CustomMailer();
        $message->setTo('to@example.com', 'test');
        $message->setCc(array('cc@example.com'));
        $message->setBcc(array('bcc@example.com'));
        $message->setFrom('from@example.com', 'from');
        return $message;
    }

    protected function mockGuzzleClient()
    {
        $this->mock_handler = new MockHandler([
            new Response(200, []),
        ]);

        $this->client = new Client(['handler' => $this->mock_handler]);
    }

    public function testSendMail()
    {
        //$message = $this->createEmail();

        $test_url = 'https://localhost:8000/email';

        $config = $this->app['config']->get('services.custom_mail', []);

        $transport = new CustomTransport($config['key'], $config['url']);
        $transport->setTo('to@example.com', 'test');
        $transport->setCc(array('cc@example.com'));
        $transport->setBcc(array('bcc@example.com'));
        $transport->setFrom('from@example.com', 'from');


        $response = $transport->send('postman');

        $body = $response->body();
        $data = json_decode($body, true);

        $expected = [
            'to' => [
                [
                    'name' => null,
                    'email' => 'to@example.com',
                ]
            ],
            'cc' => [
                [
                    'name' => NULL,
                    'email' => 'cc@example.com',
                ]
            ],
            'bcc' => [
                [
                    'name' => NULL,
                    'email' => 'bcc@example.com',
                ]
            ],
            'message' => '<body>Message body</body>',
            'subject' => 'Test subject',
        ];

        // // Test that the data was sent to the correct URL
        // $this->assertEquals($test_url, (string)$response->getUri());

        // Test the correct data was sent to the API
        $this->assertEquals($expected, $data);

        // Test that the authorization key was sent in the headers
        $this->assertEquals('Bearer secret-key', $response->getHeaderLine('Authorization'));
    }
}
