<?php

namespace App\Jobs;

use App\Events\NewScan;
use App\Events\Rayan;
use App\Http\Controllers\ScreenshotController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use App\Models\Screenshot;
use Storage;
use Illuminate\Support\Facades\Log;

class ScreenshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected string $url;
    protected string $name;
    public function __construct($url, $name)
    {
        $this->url = $url;
        $this->name = $name;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            LOG::info("Handle Calling Node.js script with URL: " . $this->name);

            $nodeScript = '/var/www/html/scripts/screen.js';
            $command = "node $nodeScript " . escapeshellarg($this->url);

            $result = Process::run($command);

            $path = 'screens/' . uniqid() . '.png';
            $disk = Storage::disk("s3");
            $contents = $disk->put($path, $result->output());
            
            LOG::info("contents = " . $contents);

            $screenshot = Screenshot::where('name', $this->name)->first();
            $screenshot->url = $this->url;
            $screenshot->name = $this->name;
            $screenshot->file_path = $path;
            $screenshot->save();


            $temporaryUrl = Storage::disk('minio')->temporaryUrl($screenshot->file_path, now()->addMinutes(5));
            Log::debug($screenshot->name . " before emit event");
            event(new NewScan( $screenshot, $temporaryUrl));
            Log::debug($screenshot->name . " after emit event\n\n");


        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage());
        }
        //
    }
}
