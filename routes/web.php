<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DomainReportController;
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
