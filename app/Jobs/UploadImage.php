<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $disk;

    protected string $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $disk, string $filename)
    {
        $this->disk = $disk;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //upload to disk
        $content = Storage::disk('public')->get($this->filename);
        Storage::disk($this->disk)->put($this->filename, $content);

        //delete from public storage
        $path = Storage::disk('public')->path($this->filename);
        unlink($path);
    }
}
