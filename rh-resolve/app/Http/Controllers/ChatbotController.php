<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function handle(Request $request)
    {
        $message = $request->input('message');
        $conversationState = $request->session()->get('conversationState', 'start');

        $response = $this->processMessage($message, $conversationState);

        // Atualiza o estado da conversa
        $request->session()->put('conversationState', $response['state']);

        return response()->json(['response' => $response['message']]);
    }

    private function processMessage($message, $conversationState)
    {
        switch ($conversationState) {
            case 'start':
                return [
                    'message' => '1. Início com informações padrões automáticas<br>2. Perguntas pré-definidas para funcionários de empresas cadastradas<br>3. Falar com atendente (consultor do RH Resolve +)<br>4. Agendamento de horário presencial com o RH da empresa',
                    'state' => 'main_menu'
                ];

            case 'main_menu':
                if ($message == '1') {
                    return [
                        'message' => 'Aqui estão as informações automáticas padrão...',
                        'state' => 'start'
                    ];
                } elseif ($message == '2') {
                    return [
                        'message' => 'Selecione a opção:<br>1. Holerite<br>2. Benefícios<br>3. Descontos<br>4. Voltar ao menu anterior',
                        'state' => 'pre_defined_questions'
                    ];
                } elseif ($message == '3') {
                    return [
                        'message' => 'Entrando em contato com o atendente...',
                        'state' => 'contacting_attendant'
                    ];
                } elseif ($message == '4') {
                    return [
                        'message' => 'Para agendar um horário presencial, por favor, informe a data desejada.',
                        'state' => 'scheduling'
                    ];
                } else {
                    return [
                        'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                        'state' => 'main_menu'
                    ];
                }

            case 'pre_defined_questions':
                switch ($message) {
                    case '1':
                        return [
                            'message' => 'Informações sobre Holerite...',
                            'state' => 'main_menu'
                        ];
                    case '2':
                        return [
                            'message' => 'Informações sobre Benefícios...',
                            'state' => 'main_menu'
                        ];
                    case '3':
                        return [
                            'message' => 'Informações sobre Descontos...',
                            'state' => 'main_menu'
                        ];
                    case '4':
                        return [
                            'message' => 'Voltando ao menu anterior...',
                            'state' => 'main_menu'
                        ];
                    default:
                        return [
                            'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                            'state' => 'pre_defined_questions'
                        ];
                }

            // Adicione outros casos para cada estado necessário

            default:
                return [
                    'message' => 'Desculpe, não entendi. Por favor, selecione uma opção válida.',
                    'state' => 'main_menu'
                ];
        }
    }
}
