<?php

namespace App\Http\Controllers;

use App\Models\AiChat;
use App\Models\AiModel;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AiChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $chats = AiChat::with(['user', 'aiModel'])->paginate(15);
        return view('ai.chats.index', compact('chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('ai.chats.create', [
            'aiModels' => AiModel::all(),
        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ai_model_id' => 'required|exists:ai_models,id',
            'is_active' => 'boolean',
        ]);

        // Ajouter l'utilisateur actuellement connecté
        $validated['user_id'] = $request->user()->id;

        $chat = AiChat::create($validated);

        // Rediriger vers la page de détails du chat
        return redirect()->route('ai.chats.show', ['chat' => $chat->id])->with('success', 'Chat AI créé avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // Récupérer le chat avec l'ID spécifié
        $aiChat = AiChat::findOrFail($id);

        // Charger toutes les relations nécessaires pour éviter les erreurs de propriété null
        $aiChat->load(['user', 'aiModel', 'messages', 'resources']);

        return view('ai.chats.show', compact('aiChat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $aiChat = AiChat::findOrFail($id);

        return view('ai.chats.edit', [
            'aiChat' => $aiChat,
            'aiModels' => AiModel::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $aiChat = AiChat::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ai_model_id' => 'required|exists:ai_models,id',
            'is_active' => 'boolean',
        ]);

        // On ne modifie pas l'utilisateur associé au chat
        $aiChat->update($validated);

        return redirect()->route('ai.chats.index')->with('success', 'AI Chat updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $aiChat = AiChat::findOrFail($id);
        $aiChat->delete();

        return redirect()->route('ai.chats.index')->with('success', 'AI Chat deleted successfully');
    }

    /**
     * Start a new chat conversation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startChat($id)
    {
        $aiChat = AiChat::with('aiModel')->findOrFail($id);

        // Vérifier si le chat est actif
        if (!$aiChat->is_active) {
            return redirect()->back()->with('error', 'Ce chat est inactif et ne peut pas être démarré.');
        }

        // Vérifier si le modèle AI existe
        if (!$aiChat->aiModel) {
            return redirect()->back()->with('error', 'Aucun modèle AI associé à ce chat.');
        }

        try {
            // Vérifier la disponibilité du modèle via le service Ollama
            $ollamaService = app(\App\Services\OllamaService::class);
            $modelDetails = null;

            try {
                // Tenter de récupérer les détails du modèle
                $modelDetails = $ollamaService->getModelDetails($aiChat->aiModel->name);
            } catch (\Exception $e) {
                // Si le modèle n'est pas disponible, on continue mais on log l'erreur
                \Illuminate\Support\Facades\Log::warning("Le modèle {$aiChat->aiModel->name} n'est pas disponible sur Ollama: " . $e->getMessage());
            }

            // Message d'accueil personnalisé en fonction du modèle
            $welcomeMessage = 'Bonjour ! Je suis votre assistant IA';

            if ($aiChat->aiModel) {
                $welcomeMessage .= " basé sur le modèle {$aiChat->aiModel->name}";
            }

            $welcomeMessage .= ". Comment puis-je vous aider aujourd'hui ?";

            // Créer un premier message d'accueil du système
            $aiChat->messages()->create([
                'content' => $welcomeMessage,
                'role' => 'assistant',
                'metadata' => [
                    'type' => 'welcome',
                    'timestamp' => now()->timestamp,
                    'model_details' => $modelDetails
                ]
            ]);

            return redirect()->route('ai.chats.show', ['chat' => $id])->with('success', 'Chat démarré avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du démarrage du chat: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier l'état de connexion à Ollama
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOllamaStatus()
    {
        try {
            $ollamaService = app(\App\Services\OllamaService::class);
            $status = $ollamaService->healthCheck();

            return response()->json([
                'status' => $status['status'],
                'message' => $status['message'],
                'response_time' => $status['response_time']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la vérification du statut Ollama: ' . $e->getMessage()
            ], 500);
        }
    }
}
