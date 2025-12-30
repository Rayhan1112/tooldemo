<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function index()
    {
        return view('single-page');
    }

    public function generateDescription(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:255',
            'about'  => 'required|string|max:1000',
        ]);

        try {
            $client = \OpenAI::client(config('services.openai.key'));

            // ⚠️ PROMPT IS UNTOUCHED (AS REQUESTED)
            $prompt = <<<PROMPT
You are a professional domain valuation analyst, brand strategist, and market researcher.

TASK:
Generate a comprehensive professional domain evaluation report with the following sections:
1. Executive Summary (Introduction Report)
2. Market Research & Analysis (Google Research-style)
3. Trademark Research
4. Keyword Search Volume Research
5. Competitor Research
6. Valuation & Commercial Assessment

INPUT:
Domain: {$request->domain}
About: {$request->about}

OUTPUT REQUIREMENTS:
- Return VALID JSON only
- Do NOT include markdown, comments, or explanations
- Use natural, professional business language
- Provide detailed content for each section (8-10 lines or more where appropriate)
- All data should be generated as free-form text within the structure, based on real research and market knowledge, not dummy or placeholder data
- Ensure the entire response fits within the token limit

JSON STRUCTURE (follow exactly):

{
  "executive_summary": {
    "title": "Professional Domain Evaluation Report: {$request->domain}",
    "introduction": "A detailed executive summary of 8-10 lines introducing the domain, highlighting its core value proposition, brand potential, and strategic significance in today's digital marketplace.",
    "key_highlights": [
      "Primary highlight about the domain's strength",
      "Secondary highlight about market potential",
      "Tertiary highlight about commercial viability"
    ]
  },
  "market_research": {
    "industry_analysis": "Provide a detailed analysis in 8-10 lines of relevant industry trends, market demand patterns, and competitive landscape that validate the domain's commercial potential.",
    "seo_potential": "Provide a detailed analysis in 8-10 lines of SEO opportunities and organic traffic potential.",
    "competitive_landscape": "Provide a detailed overview in 8-10 lines of competing domains and differentiation factors."
  },
  "trademark_research": {
    "conflict_analysis": "Provide a detailed analysis in 8-10 lines of potential trademark conflicts.",
    "brand_safety": "Provide a detailed assessment in 8-10 lines of brand safety factors."
  },
  "keyword_research": {
    "search_volume": "Provide a detailed analysis in 8-10 lines of search volume.",
    "cpc_analysis": "Provide a detailed analysis in 8-10 lines of CPC and advertiser demand.",
    "opportunity_keywords": [
      "Keyword 1",
      "Keyword 2",
      "Keyword 3"
    ]
  },
  "competitor_research": {
   "analysis": "Provide a detailed competitor market analysis in 8-10 lines, including compulsory research data on market share, pricing strategies, and competitive advantages. Research and provide real competitor data based on the domain's industry.",
   "competitors": [
     { "name": "Real Competitor Name 1", "domain": "realcompetitor1.com", "price": "Actual price range based on research", "strategy": "Detailed real strategy description" },
     { "name": "Real Competitor Name 2", "domain": "realcompetitor2.com", "price": "Actual price range based on research", "strategy": "Detailed real strategy description" },
     { "name": "Real Competitor Name 3", "domain": "realcompetitor3.com", "price": "Actual price range based on research", "strategy": "Detailed real strategy description" },
     { "name": "Real Competitor Name 4", "domain": "realcompetitor4.com", "price": "Actual price range based on research", "strategy": "Detailed real strategy description" }
   ]
 },
  "valuation": {
   "title": "Generate an appropriate title for the domain valuation, only include 'For Sale' if the domain is genuinely available for purchase based on research.",
   "about": "Provide a detailed professional valuation paragraph in 8-10 lines.",
   "expected_price": "Provide a realistic USD price range based on market research and competitor analysis.",
    "top_uses": [
      { "industry": "Industry", "use": "Use case" }
    ],
    "industries": [
      "Industry A",
      "Industry B"
    ]
  },
  "final_summary": "Provide a detailed final summary in 8-10 lines synthesizing the key findings from all sections, tailored to the domain."
}

FINAL CHECK:
- Output must be valid JSON
- No extra text before or after JSON
PROMPT;

            $maxRetries = 3;
            $retryDelay = 1; // seconds
            $response = null;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $response = $client->chat()->create([
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.3,
                        'max_tokens' => 8000,
                        'response_format' => ['type' => 'json_object'],
                    ]);
                    break; // Success, exit retry loop
                } catch (\OpenAI\Exceptions\RateLimitException $e) {
                    if ($attempt === $maxRetries) {
                        throw $e; // Re-throw if max retries reached
                    }
                    Log::warning("OpenAI Rate Limit hit, attempt {$attempt}/{$maxRetries}, retrying in {$retryDelay}s");
                    sleep($retryDelay);
                    $retryDelay *= 2; // Exponential backoff
                } catch (\Throwable $e) {
                    // For other exceptions, don't retry
                    throw $e;
                }
            }

            $content = $response->choices[0]->message->content;

            $json = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Decode Error', [
                    'error' => json_last_error_msg(),
                    'raw_response' => $content,
                ]);

                throw new \Exception('JSON parse error: ' . json_last_error_msg());
            }

            if (!isset($json['valuation'], $json['executive_summary'], $json['market_research'], $json['final_summary'])) {
                throw new \Exception('Missing required JSON sections');
            }

            return response()->json([
                'success' => true,
                'data' => $json
            ]);

        } catch (\Throwable $e) {
            Log::error('Domain Report Generation Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }


}
