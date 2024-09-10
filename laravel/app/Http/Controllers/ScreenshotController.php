<?php

namespace App\Http\Controllers;

use App\Jobs\ScreenshotJob;
use App\Models\Screenshot;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Facades\Log;



class ScreenshotController extends Controller
{
    public function index()
    {
        return view('screenshot.index');
    }

    public function create(Request $request)
    {
        error_log("request = " . $request->getMethod());
        return "hello world";
    }

    public function s3Url($path)
    {
        Log::debug("STATIC s3 url path = " . $path);
        $disk = Storage::disk('s3');

        if ($disk->exists($path)) {
            $s3_client = $disk->getDriver()->publicUrl($path);
            LOG::notice("s3 liiiiiii " . $s3_client);

            return $s3_client;
        } else {
            throw new FileNotFoundException($path);
        }
    }


    public function jobs(Request $request)
    {
        LOG::debug("job controller");
        try {
            $validated = $request->validate([
                'url' => 'required|max:500',
                'name' => 'required|max:30',
            ]);

                $scren = Screenshot::where('name', $request->name)
                ->where(function ($query) {
                    $query->where('is_processing', true);
                })
                ->first();

            if ($scren) {
                return response()->json(['error' => 'name already exists or is being processed'], 409);
            }

            $scren = Screenshot::create([
                'name' => $request->name,
                'url' => $request->url,
                'is_processing' => true,
                'file_path' => 'processing', 
            ]);

            LOG::debug("VALIDATED job controller");
            ScreenshotJob::dispatch($request->url, $request->name);

            return response()->json(['message' => 'Job dispatched']);
        } catch (\Throwable $th) {
            LOG::info("Error: " . $th->getMessage());
            return response()->json(['error' => 'name already exists or is being processed'], 409);
        }
    }


    public function getSites(Request $request)
    {
        try {
            $sites = Screenshot::all();
            $addPath = $sites->map(function ($site) {
                return [
                    'id' => $site->id,
                    'url' => $site->url,
                    'file_path' => Storage::disk('minio')->temporaryUrl($site->file_path, now()->addMinutes(5)),
                    'name' => $site->name,
                    'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $site->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            return response()->json($addPath);
        } catch (\Throwable $th) {
            error_log("Error: " . $th->getMessage());
            return response()->json(['error' => 'getSites controller error' . $th->getMessage()], 500);
        }
    }

}
