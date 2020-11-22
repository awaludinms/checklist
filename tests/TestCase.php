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

    protected function userAdd()
    {
        return App\Models\User::Factory()->create();
    }

    protected function getToken()
    {
        $user = $this->userAdd();

        $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        return json_decode($this->response->getContent(), true)['token'];
    }

    protected function authorization()
    {
        return ['Authorize' => 'Bearer ' . $this->getToken() ];
    }

    protected function createChecklist()
    {
        return App\Models\Checklist::Factory()->create();
    }

}
