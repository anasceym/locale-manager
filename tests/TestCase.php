<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function getPaginatedItemsExpectedStructure($expectedData = []) {

        $defaultPaginatedData = [
            'total',
            'per_page',
            'current_page',
            'last_page',
            'next_page_url',
            'prev_page_url',
            'from',
            'to'
        ];

        if (count($expectedData)) {
            $defaultPaginatedData['data'] = [
                '*' => $expectedData
            ];
        }

        return $defaultPaginatedData;
    }

    public function getErrorExpectedStructure() {

        return [
            'message',
            'data'
        ];
    }
}
