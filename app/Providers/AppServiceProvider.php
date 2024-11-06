<?php

use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Dotenv\Dotenv;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (env('GAE_APPLICATION_ENV')) {
            $secretName = env('GOOGLE_ENV_SECRET_NAME');
            $dotenv = $this->loadEnvFromSecret($secretName);
            Dotenv::createImmutable(base_path(), '.env')->load();
        }
    }

    private function loadEnvFromSecret($secretName)
    {
        $client = new SecretManagerServiceClient();
        $name = $client->secretVersionName('your-project-id', $secretName, 'latest');
        $response = $client->accessSecretVersion($name);
        $dotenvData = $response->getPayload()->getData();
        file_put_contents(base_path('.env'), $dotenvData);
    }
}

