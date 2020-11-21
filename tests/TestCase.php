<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function getToken()
    {
        $user = App\Models\User::Factory()->create();

        $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        return json_decode($this->response->getContent(), true)['token'];
    }
}
