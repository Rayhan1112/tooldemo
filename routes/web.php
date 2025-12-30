<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DomainReportController;
use App\Http\Controllers\EmailSchedulerController;
use App\Http\Controllers\ScheduledEmailController;
use App\Http\Controllers\ImageUploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great.
|
*/

// Single page route
Route::get('/', [PageController::class, 'index']);
Route::post('/generate-description', [App\Http\Controllers\PageController::class, 'generateDescription']);

Route::get('/email-scheduler', [EmailSchedulerController::class, 'index']);
Route::post('/schedule-email', [EmailSchedulerController::class, 'store']);
Route::get('/scheduled-emails-status', [ScheduledEmailController::class, 'status']);
Route::get('/api/emails', [ScheduledEmailController::class, 'getEmails']);
Route::delete('/api/emails/{id}', [ScheduledEmailController::class, 'delete']);

// Redirect /schedule-email (GET) to the email scheduler form
Route::get('/schedule-email', function() {
    return redirect('/email-scheduler');
});

// Test route to verify database connection
Route::get('/test-db', function() {
    try {
        $tables = DB::select('SHOW TABLES');
        return response()->json([
            'success' => true,
            'message' => 'Database connection successful!',
            'tables' => $tables,
            'connection' => config('database.default')
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test route to verify scheduled_emails table
Route::get('/test-scheduled-emails', function() {
    try {
        $columns = DB::getSchemaBuilder()->getColumnListing('scheduled_emails');
        $count = DB::table('scheduled_emails')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'scheduled_emails table is accessible!',
            'columns' => $columns,
            'record_count' => $count
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/', [PageController::class, 'index']);
Route::post('/generate-report', [PageController::class, 'generateReport']);

// Test route (no CSRF protection)
Route::post('/test-openai', function() {
    try {
        $apiKey = config('services.openai.key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'API key not configured']);
        }

        $client = \OpenAI::client($apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => 'Say "OpenAI is working!" in exactly 3 words.'],
            ],
            'max_tokens' => 50,
            'temperature' => 0.1,
        ]);

        return response()->json([
            'success' => true,
            'response' => $response->choices[0]->message->content,
            'api_key_exists' => !empty($apiKey)
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/image-uploader', function () { return view('image-uploader'); });
Route::post('/upload-image', [ImageUploadController::class, 'upload']);
