<?php

namespace App\Implementations;

use App\Contracts\Sns;
use Aws\Sns\SnsClient;
use App\Implementations\Message;

class AwsSns implements Sns
{
    /**
     * Sqs client
     *
     * @var \Aws\Sns\SnsClient
     */
    private $client;
    /**
     * Arn url
     *
     * @var string
     */
    private $arn;

    /**
     * __construct
     *
     * @param string $arn
     * @param  \Aws\Sns\SnsClient $client
     * @return void
     */
    public function __construct($arn, SnsClient $client)
    {
        $this->client = $client;
        $this->arn = $arn;
    }

    /**
     * Send message to queue
     *
     * @param  \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function notify(Message $message)
    {
        try {
            $defaultMessageNotification = 'default message';
            $endPointArn = [
                'EndpointArn' => '$userDeviceToken->endpoint_arn'
            ];
            $endpointAtt = $this->client->getEndpointAttributes($endPointArn);
            if ($endpointAtt == 'failed' || !$endpointAtt['Attributes']['Enabled']) {
                //Log::error('not found endPointArn of device token = ' . $userDeviceTokens->device_token);
                return;
            }
            $payload = [
                'aps' => [
                    'alert' => "Hello, this is message come from viblo.asia",
                    'badge' => 1, # cái này chính là số lượng noti nó hiển thị đỏ đỏ trên cái icon của app ấy
                    'sound' => 'default'
                ],
                'order_id' => 'test',
            ];
            $message = json_encode([
                'default' => $defaultMessageNotification,
                'APNS' => json_encode($payload) # chú ý chỗ này có 1 json_encode nữa nhé
            ]);
            $this->client->publish([
                'TargetArn' => '$userDeviceToken->endpoint_arn',
                'Message' => $message,
                'MessageStructure' => 'json'
            ]);
            return true;
        } catch (\Exception $e) {
            echo 'Error sending message to queue ' . $e->getMessage();
            return false;
        }
    }

    public function snsEndpointArn($deviceToken)
    {
        try {
            $platformApplicationArn = env('SNS_ARN_ANDROID');
            if (!$platformApplicationArn) {
                //Log::error('AWS_SNS_ARN not config, to push notification please config it in .env');
                return;
            }

            $result = $this->client->createPlatformEndpoint([
                'PlatformApplicationArn' => $platformApplicationArn,
                'Token' => $deviceToken,
            ]);

            return $result['EndpointArn'] ?? '';
        } catch (\Exception $e) {
            //Log::error($e->getMessage());
        }

        return;
    }

    public function subscribe()
    {

    }

    public function sms()
    {
        $this->client->publish([
            // 'MessageAttributes' => [
            //     'AWS.SNS.SMS.SenderID' => [
            //         'DataType' => 'String',
            //         'StringValue' => ''
            //     ],
            //     'AWS.SNS.SMS.SMSType' => [
            //         'DataType' => 'String',
            //         'StringValue' => 'Transactional'
            //     ]
            // ],
            'Message' => 'Hello, message from aws sns.',
            'PhoneNumber' => '+84903012375'
        ]);
    }
}
