<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ReAct Reasoning Service
 * 
 * Implements the ReAct (Reasoning and Acting) framework for AI-powered VPC generation.
 * 
 * ReAct Pattern:
 * 1. OBSERVE - Analyze input data
 * 2. THINK - Identify patterns and insights
 * 3. ACT - Generate specific outputs
 * 
 * Two Reasoning Layers:
 * - Layer 1: Empathy Map → Customer Profile
 * - Layer 2: Customer Profile → Value Map
 */
class ReasoningService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;
    protected int $maxRetries = 3;

    public function __construct()
    {
        $this->provider = config('services.ai_provider', 'groq');
        
        if ($this->provider === 'groq') {
            $this->apiKey = config('services.groq.api_key');
            $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
        } else {
            $this->apiKey = config('services.gemini.api_key');
            $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        }
    }

    /**
     * Layer 1: Generate Customer Profile from Empathy Map using ReAct
     */
    public function generateCustomerProfileWithReasoning(array $empathyData): array
    {
        $prompt = $this->buildReActPromptLayer1($empathyData);
        
        $result = $this->callAI($prompt);
        
        return [
            'customer_jobs' => $result['customer_jobs'] ?? [],
            'customer_pains' => $result['customer_pains'] ?? [],
            'customer_gains' => $result['customer_gains'] ?? [],
            'reasoning_trace' => $result['reasoning_steps'] ?? [],
            'summary' => $result['summary'] ?? 'AI-generated customer profile',
        ];
    }

    /**
     * Layer 2: Generate Value Map from Customer Profile using ReAct
     */
    public function generateValueMapWithReasoning(array $customerProfile): array
    {
        $prompt = $this->buildReActPromptLayer2($customerProfile);
        
        $result = $this->callAI($prompt);
        
        return [
            'products_services' => $result['products_services'] ?? [],
            'pain_relievers' => $result['pain_relievers'] ?? [],
            'gain_creators' => $result['gain_creators'] ?? [],
            'reasoning_trace' => $result['reasoning_steps'] ?? [],
            'summary' => $result['summary'] ?? 'AI-generated value map',
        ];
    }

    /**
     * Build ReAct prompt for Layer 1 (Empathy Map → Customer Profile)
     */
    protected function buildReActPromptLayer1(array $empathyData): string
    {
        $says = implode("\n- ", $empathyData['says'] ?? []);
        $thinks = implode("\n- ", $empathyData['thinks'] ?? []);
        $does = implode("\n- ", $empathyData['does'] ?? []);
        $feels = implode("\n- ", $empathyData['feels'] ?? []);

        return <<<PROMPT
ROLE
You are an expert Business Analyst using the ReAct (Reasoning and Acting) framework for Value Proposition Canvas analysis.

FRAMEWORK: REACT
For each analysis step, you MUST follow this pattern:
1. OBSERVE: What patterns do you see in the data?
2. THINK: What insights and connections emerge?
3. ACT: What specific output do you generate?

TASK
Transform this Empathy Map data into a Customer Profile (Jobs, Pains, Gains).

INPUT DATA
==========

SAYS (Direct quotes from customers):
- {$says}

THINKS (Internal thoughts and concerns):
- {$thinks}

DOES (Observable behaviors):
- {$does}

FEELS (Emotional states):
- {$feels}

REASONING PROCESS
=================

Execute these steps systematically:

STEP 1 - Customer Jobs Analysis
- OBSERVE the SAYS and DOES sections
- THINK about what tasks, goals, or problems customers are trying to accomplish
- ACT by listing 3-5 specific Customer Jobs

STEP 2 - Pains Analysis  
- OBSERVE the THINKS and FEELS sections (negative emotions, frustrations)
- THINK about obstacles, risks, and undesired outcomes
- ACT by listing 3-5 specific Pains

STEP 3 - Gains Analysis
- OBSERVE the FEELS section (positive desires) and overall goals
- THINK about desired outcomes, benefits, and expectations
- ACT by listing 3-5 specific Gains

OUTPUT FORMAT
=============
Return ONLY valid JSON (no markdown, no extra text):
{
  "reasoning_steps": [
    {
      "step": 1,
      "phase": "Customer Jobs Analysis",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Customer Jobs"
    },
    {
      "step": 2,
      "phase": "Pains Analysis",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Pains"
    },
    {
      "step": 3,
      "phase": "Gains Analysis",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Gains"
    }
  ],
  "customer_jobs": [
    "Specific job in Indonesian",
    "Another job in Indonesian"
  ],
  "customer_pains": [
    "Specific pain in Indonesian",
    "Another pain in Indonesian"
  ],
  "customer_gains": [
    "Specific gain in Indonesian",
    "Another gain in Indonesian"
  ],
  "summary": "Brief summary of the customer profile in Indonesian"
}

IMPORTANT:
- Generate 3-5 items for each category
- Be specific and actionable
- Write descriptions in Indonesian
- Each item should be a complete, clear statement
PROMPT;
    }

    /**
     * Build ReAct prompt for Layer 2 (Customer Profile → Value Map)
     */
    protected function buildReActPromptLayer2(array $customerProfile): string
    {
        $jobs = implode("\n- ", $customerProfile['customer_jobs'] ?? []);
        $pains = implode("\n- ", $customerProfile['customer_pains'] ?? []);
        $gains = implode("\n- ", $customerProfile['customer_gains'] ?? []);

        return <<<PROMPT
ROLE
You are an expert Business Analyst using the ReAct (Reasoning and Acting) framework for Value Proposition Canvas design.

FRAMEWORK: REACT
For each analysis step, you MUST follow this pattern:
1. OBSERVE: What patterns do you see in the Customer Profile?
2. THINK: What solutions or offerings would address these needs?
3. ACT: What specific value propositions do you generate?

TASK
Transform this Customer Profile into a Value Map (Products & Services, Pain Relievers, Gain Creators).

INPUT DATA - CUSTOMER PROFILE
=============================

CUSTOMER JOBS (What they're trying to accomplish):
- {$jobs}

PAINS (Obstacles and frustrations):
- {$pains}

GAINS (Desired outcomes):
- {$gains}

REASONING PROCESS
=================

Execute these steps systematically:

STEP 1 - Products & Services Design
- OBSERVE the Customer Jobs
- THINK about what products, services, or features would help accomplish these jobs
- ACT by listing 3-5 specific Products & Services

STEP 2 - Pain Relievers Design
- OBSERVE the Pains
- THINK about how to eliminate, reduce, or minimize each pain
- ACT by listing 3-5 specific Pain Relievers

STEP 3 - Gain Creators Design
- OBSERVE the Gains
- THINK about how to create, maximize, or exceed these expected outcomes
- ACT by listing 3-5 specific Gain Creators

OUTPUT FORMAT
=============
Return ONLY valid JSON (no markdown, no extra text):
{
  "reasoning_steps": [
    {
      "step": 1,
      "phase": "Products & Services Design",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Products & Services"
    },
    {
      "step": 2,
      "phase": "Pain Relievers Design",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Pain Relievers"
    },
    {
      "step": 3,
      "phase": "Gain Creators Design",
      "observe": "Your observation here",
      "think": "Your reasoning here",
      "act": "Generated Gain Creators"
    }
  ],
  "products_services": [
    "Specific product/service in Indonesian",
    "Another product/service in Indonesian"
  ],
  "pain_relievers": [
    "Specific pain reliever in Indonesian",
    "Another pain reliever in Indonesian"
  ],
  "gain_creators": [
    "Specific gain creator in Indonesian",
    "Another gain creator in Indonesian"
  ],
  "summary": "Brief summary of the value proposition in Indonesian"
}

IMPORTANT:
- Generate 3-5 items for each category
- Be specific and actionable
- Write descriptions in Indonesian
- Each Pain Reliever should directly address a Pain
- Each Gain Creator should directly enable a Gain
- Products & Services should support the Jobs
PROMPT;
    }

    /**
     * Call AI API (Groq or Gemini)
     */
    protected function callAI(string $prompt): array
    {
        if ($this->provider === 'groq') {
            return $this->callGroqApi($prompt);
        }
        
        return $this->callGeminiApi($prompt);
    }

    /**
     * Call Groq API with retry logic
     */
    protected function callGroqApi(string $prompt): array
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                $response = Http::timeout(90)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $this->model,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are an expert Business Analyst. Always respond with valid JSON only, no markdown formatting.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'temperature' => 0.4,
                        'max_tokens' => 4096,
                    ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $text = $result['choices'][0]['message']['content'] ?? '';
                    return $this->parseJsonResponse($text);
                }
                
                $errorBody = $response->json();
                $statusCode = $response->status();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';
                
                Log::error("ReAct Groq API Error (Attempt {$attempt})", [
                    'status' => $statusCode,
                    'error' => $errorMessage,
                ]);
                
                if ($statusCode === 429) {
                    if ($attempt < $this->maxRetries) {
                        sleep(pow(2, $attempt));
                        continue;
                    }
                    throw new Exception('Rate limit exceeded. Please wait and try again.');
                }
                
                throw new Exception($errorMessage);
                
            } catch (Exception $e) {
                $lastException = $e;
                if ($attempt >= $this->maxRetries) {
                    throw $e;
                }
                sleep(pow(2, $attempt));
            }
        }
        
        throw $lastException ?? new Exception('AI reasoning generation failed.');
    }

    /**
     * Call Gemini API
     */
    protected function callGeminiApi(string $prompt): array
    {
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
        
        $response = Http::timeout(90)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($apiUrl . '?key=' . $this->apiKey, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 4096,
                ]
            ]);

        if (!$response->successful()) {
            $error = $response->json()['error']['message'] ?? 'API error';
            throw new Exception($error);
        }

        $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return $this->parseJsonResponse($text);
    }

    /**
     * Parse JSON response from AI
     */
    protected function parseJsonResponse(string $text): array
    {
        // Remove markdown code blocks if present
        $text = preg_replace('/```json\s*|\s*```/', '', $text);
        $text = trim($text);
        
        $data = json_decode($text, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('ReAct: Invalid JSON from AI', ['response' => substr($text, 0, 500)]);
            throw new Exception('Invalid JSON response from AI reasoning engine.');
        }
        
        return $data;
    }
}
