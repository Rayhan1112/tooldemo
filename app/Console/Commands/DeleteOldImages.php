<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DeleteOldImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete uploaded images based on database settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $setting = DB::table('image_deletion_settings')->first();

        if (!$setting) {
            $this->error('No image deletion settings found in database.');
            return Command::FAILURE;
        }

        $value = $setting->value;
        $type = $setting->type;

        switch ($type) {
            case 'seconds':
                $threshold = $value;
                break;
            case 'minutes':
                $threshold = $value * 60;
                break;
            case 'days':
                $threshold = $value * 86400;
                break;
            default:
                $this->error('Invalid type in image deletion settings.');
                return Command::FAILURE;
        }

        $uploadsPath = public_path('uploads');
        $files = File::files($uploadsPath);

        foreach ($files as $file) {
            $filename = basename($file);
            $timestamp = (int) explode('.', $filename)[0];

            if (time() - $timestamp > $threshold) {
                File::delete($file);
                $this->info("Deleted old image: {$filename}");
            }
        }

        return Command::SUCCESS;
    }
}
