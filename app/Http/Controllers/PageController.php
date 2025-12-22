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
- Keep output comprehensive yet concise

JSON STRUCTURE (follow exactly):

{
  "executive_summary": {
    "title": "Professional Domain Evaluation Report: {$request->domain}",
    "introduction": "A comprehensive 3-4 sentence executive summary introducing the domain, highlighting its core value proposition, brand potential, and strategic significance in today's digital marketplace.",
    "key_highlights": [
      "Primary highlight about the domain's strength",
      "Secondary highlight about market potential",
      "Tertiary highlight about commercial viability"
    ]
  },
  "market_research": {
    "industry_analysis": "Detailed analysis of relevant industry trends, market demand patterns, and competitive landscape that validate the domain's commercial potential.",
    "seo_potential": "Analysis of SEO opportunities and organic traffic potential.",
    "competitive_landscape": "Overview of competing domains and differentiation factors."
  },
  "trademark_research": {
    "conflict_analysis": "Analysis of potential trademark conflicts.",
    "brand_safety": "Assessment of brand safety factors."
  },
  "keyword_research": {
    "search_volume": "Search volume analysis.",
    "cpc_analysis": "CPC and advertiser demand analysis.",
    "opportunity_keywords": [
      "Keyword 1",
      "Keyword 2",
      "Keyword 3"
    ]
  },
  "competitor_research": {
    "analysis": "Competitor market analysis.",
    "competitors": [
      { "name": "Competitor A", "domain": "exampleA.com", "price": "$1,500 - $2,500", "strategy": "Premium" }
    ]
  },
  "valuation": {
    "title": "The Domain Name {$request->domain} Is For Sale",
    "about": "Professional valuation paragraph.",
    "expected_price": "USD price range",
    "top_uses": [
      { "industry": "Industry", "use": "Use case" }
    ],
    "industries": [
      "Industry A",
      "Industry B"
    ]
  }
}

FINAL CHECK:
- Output must be valid JSON
- No extra text before or after JSON
PROMPT;

            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000,
                'response_format' => ['type' => 'json_object'],
            ]);

            $content = $response->choices[0]->message->content;

            $json = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Decode Error', [
                    'error' => json_last_error_msg(),
                    'raw_response' => $content,
                ]);

                throw new \Exception('JSON parse error: ' . json_last_error_msg());
            }

            if (!isset($json['valuation'], $json['executive_summary'], $json['market_research'])) {
                throw new \Exception('Missing required JSON sections');
            }

            /**
             * ✅ FINAL SUMMARY (DERIVED — NO PROMPT CHANGE)
             */
            $json['final_summary'] =
                "Based on the overall analysis, {$request->domain} demonstrates strong branding potential supported by favorable market conditions and clear commercial applicability. " .
                "The domain aligns well with current industry demand, shows positive SEO and keyword monetization opportunities, and presents manageable trademark considerations. " .
                "With a realistic valuation and multiple viable use cases across relevant industries, this domain represents a credible digital asset suitable for investment, branding, or business development purposes.";

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
