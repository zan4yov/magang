<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Analyze empathy map and generate customer profile
     */
    public function generateCustomerProfile(array $empathyData): array
    {
        $prompt = $this->buildPrompt($empathyData);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 2048,
                    ]
                ]);

            if (!$response->successful()) {
                $errorBody = $response->json();
                $statusCode = $response->status();
                
                // Handle specific error codes
                if ($statusCode === 429) {
                    $retryDelay = $errorBody['error']['details'][0]['metadata']['retryDelay'] ?? '30s';
                    throw new Exception("API rate limit exceeded. Please wait {$retryDelay} and try again, or use a different API key.");
                } elseif ($statusCode === 403 || $statusCode === 401) {
                    throw new Exception('Invalid API key. Please check your GEMINI_API_KEY in .env file.');
                } elseif ($statusCode === 404) {
                    throw new Exception('Model not found. The API model may have been updated. Please contact support.');
                } else {
                    throw new Exception('Gemini API request failed: ' . ($errorBody['error']['message'] ?? $response->body()));
                }
            }

            $result = $response->json();
            
            return $this->parseGeminiResponse($result);

        } catch (Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Build the prompt for Gemini AI
     */
    protected function buildPrompt(array $empathyData): string
    {
        $says = implode("\n- ", $empathyData['says'] ?? []);
        $thinks = implode("\n- ", $empathyData['thinks'] ?? []);
        $does = implode("\n- ", $empathyData['does'] ?? []);
        $feels = implode("\n- ", $empathyData['feels'] ?? []);

        return <<<PROMPT
You are an expert business strategist specializing in Value Proposition Canvas (VPC) analysis. Your task is to analyze empathy map data and generate a comprehensive Customer Profile.

# Empathy Map Input:

**Says** (what customers express verbally):
- {$says}

**Thinks** (internal thoughts and concerns):
- {$thinks}

**Does** (observable behaviors and actions):
- {$does}

**Feels** (emotions and feelings):
- {$feels}

# Your Task:

Analyze the empathy map data and generate a Customer Profile with three components:

1. **Customer Jobs**: What customers are trying to accomplish (functional, social, emotional jobs)
2. **Pains**: Obstacles, frustrations, risks, and negative outcomes customers experience
3. **Gains**: Desired outcomes, benefits, and measures of success customers seek

# Important Guidelines:

- Be specific and actionable
- Focus on insights, not just rephrasing empathy data
- Each category should have 3-7 distinct points
- Use first-person perspective from customer's viewpoint
- Prioritize the most impactful insights

# Output Format:

Return ONLY a valid JSON object in this exact structure (no markdown, no extra text):

{
  "customer_jobs": [
    "Job description 1",
    "Job description 2"
  ],
  "customer_pains": [
    "Pain description 1",
    "Pain description 2"
  ],
  "customer_gains": [
    "Gain description 1",
    "Gain description 2"
  ],
  "reasoning": "Brief explanation of your analysis approach and key insights (2-3 sentences)"
}
PROMPT;
    }

    /**
     * Parse Gemini API response and extract customer profile
     */
    protected function parseGeminiResponse(array $response): array
    {
        try {
            // Extract text from Gemini response structure
            $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Remove markdown code blocks if present
            $text = preg_replace('/```json\s*|\s*```/', '', $text);
            $text = trim($text);
            
            // Parse JSON
            $data = json_decode($text, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }
            
            // Validate structure
            if (!isset($data['customer_jobs']) || !isset($data['customer_pains']) || !isset($data['customer_gains'])) {
                throw new Exception('Missing required fields in AI response');
            }
            
            return [
                'customer_jobs' => array_values($data['customer_jobs']),
                'customer_pains' => array_values($data['customer_pains']),
                'customer_gains' => array_values($data['customer_gains']),
                'reasoning' => $data['reasoning'] ?? 'No reasoning provided',
            ];
            
        } catch (Exception $e) {
            Log::error('Failed to parse Gemini response: ' . $e->getMessage());
            throw new Exception('Failed to parse AI response. Please try again.');
        }
    }
}
