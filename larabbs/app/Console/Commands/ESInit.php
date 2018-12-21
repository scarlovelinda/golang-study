<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ESInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init laravel es for post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 创建template
        $client = new Client();

        $url = config('scout.elasticsearch.hosts')[0] . '/_template/tmp';

        try {
            $client->delete($url);
        } catch (\Exception $e) {
            $this->info("===delete模版出现错误==" . $e->getMessage());
        }


        $param = [
            'json' => [
                'template' => config('scout.elasticsearch.index'),
                'mappings' => [
                    'posts' => [
                        'properties' => [
                            'title' => [
                                'type' => 'text',
                                'analyzer' => 'ik_max_word',
                            ],
                            'content' => [
                                'type' => 'text',
                                'analyzer' => 'ik_max_word',
                            ]
                        ]
                    ]
                ],
            ],
        ];
        try {
            $client->put($url, $param);
        } catch (\Exception $e) {
            $this->info("===put模版出现错误==" . $e->getMessage());
        }


        $this->info("========= 创建模版成功 ========");

        // 创建index
        $url = config('scout.elasticsearch.hosts')[0] . '/' . config('scout.elasticsearch.index');
        try {
            $client->delete($url);
        } catch (\Exception $e) {
            $this->info("===delete索引出现错误==" . $e->getMessage());
        }
        $param = [
            'json' => [
                'settings' => [
                    'refresh_interval' => '5s',
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'posts' => [
                        '_all' => [
                            'enabled' => false
                        ]
                    ]
                ]
            ]
        ];
        try {
            $client->put($url, $param);
        } catch (\Exception $e) {
            $this->info("===put索引出现错误==" . $e->getMessage());
        }

        $this->info("========= 创建索引成功 ========");
    }
}
