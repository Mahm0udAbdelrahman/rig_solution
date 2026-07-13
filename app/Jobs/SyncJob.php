<?php

namespace App\Jobs;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use DB;

class SyncJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, InteractsWithSockets;

    public $headers;
    public $api_link;
    public $models_not_synced_after_chunk;
    public $table;
    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $headers, $api_link, $models_not_synced_after_chunk)
    {
       $this->table = $table;
       $this->headers = $headers;
       $this->api_link = $api_link;
       $this->models_not_synced_after_chunk = $models_not_synced_after_chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $sync_from_local_to_server = Http::connectTimeout(300)->withHeaders($this->headers)->post($this->api_link, [ 'localbody' => $this->models_not_synced_after_chunk ]);
      if($sync_from_local_to_server->successful()){
        $update = DB::table($this->table)->whereIn('id', (array)json_decode($sync_from_local_to_server)->done)->update([
          'sync' => 1,
        ]);
      }
    }
}
