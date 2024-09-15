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
        $conversationState = $request->session()->get('conversationState', 'main_menu');

        $response = $this->processMessage($message, $conversationState);

        // Atualiza o estado da conversa
        $request->session()->put('conversationState', $response['state']);

        return response()->json(['response' => $response['message']]);
    }

    private function processMessage($message, $conversationState)
    {
        switch ($conversationState) {
            case 'main_menu':
                return $this->handleMainMenu($message);

            case 'pre_defined_questions':
                return $this->handlePreDefinedQuestions($message);

            default:
                return [
                    'message' => 'Desculpe, não entendi. Por favor, selecione uma opção válida.',
                    'state' => 'main_menu'
                ];
        }
    }

    private function handleMainMenu($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Aqui estão as informações automáticas padrão...',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Selecione a opção:<br>1. Holerite<br>2. Benefícios<br>3. Descontos<br>4. Horas Extras<br>5. Férias<br>6. Atestados Médicos<br>7. Direitos e Deveres da Empresa<br>8. Direitos e Deveres do Funcionário<br>9. Código de Conduta e Ética<br>10. Finalizar atendimento',
                    'state' => 'pre_defined_questions'
                ];
            case '3':
                return [
                    'message' => 'Entrando em contato com o atendente...',
                    'state' => 'contacting_attendant'
                ];
            case '4':
                return [
                    'message' => 'Para agendar um horário presencial, por favor, informe a data desejada.',
                    'state' => 'scheduling'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'main_menu'
                ];
        }
    }

    private function handlePreDefinedQuestions($message)
    {
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
                    'message' => 'Informações sobre Horas Extras...',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Informações sobre Férias...',
                    'state' => 'main_menu'
                ];
            case '6':
                return [
                    'message' => 'Informações sobre Atestados Médicos...',
                    'state' => 'main_menu'
                ];
            case '7':
                return [
                    'message' => 'Informações sobre Direitos e Deveres da Empresa...',
                    'state' => 'main_menu'
                ];
            case '8':
                return [
                    'message' => 'Informações sobre Direitos e Deveres do Funcionário...',
                    'state' => 'main_menu'
                ];
            case '9':
                return [
                    'message' => 'Informações sobre Código de Conduta e Ética...',
                    'state' => 'main_menu'
                ];
            case '10':
                return [
                    'message' => 'Finalizando atendimento...',
                    'state' => 'start' // ou um estado que indique o final ou retorno ao início
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'pre_defined_questions'
                ];
        }
    }
}
