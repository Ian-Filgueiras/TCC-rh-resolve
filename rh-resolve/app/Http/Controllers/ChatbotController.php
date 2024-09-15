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
        $selectedTopic = $request->session()->get('selectedTopic', null);

        // Se um tópico foi selecionado, processa as perguntas específicas do tópico
        if ($selectedTopic) {
            $response = $this->handleQuestionSelection($message, $selectedTopic);
        } else {
            // Caso contrário, continua o fluxo normal com base na escolha da mensagem inicial
            $response = $this->processMessage($message, $conversationState);
        }

        // Atualiza o estado da conversa e o tópico selecionado
        $request->session()->put('conversationState', $response['state']);
        if (isset($response['selectedTopic'])) {
            $request->session()->put('selectedTopic', $response['selectedTopic']);
        } else {
            $request->session()->forget('selectedTopic');
        }

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
            case '1': // Início com informações padrões automáticas
                return [
                    'message' => 'Aqui estão as informações automáticas padrão...',
                    'state' => 'main_menu'
                ];
            case '2': // Perguntas pré-definidas para funcionários
                return [
                    'message' => 'Selecione um tópico:<br>1. Holerite<br>2. Benefícios<br>3. Descontos<br>4. Horas Extras<br>5. Férias<br>6. Atestados Médicos<br>7. Direitos e Deveres da Empresa<br>8. Direitos e Deveres do Funcionário<br>9. Código de Conduta e Ética',
                    'state' => 'pre_defined_questions'
                ];
            case '3': // Falar com atendente
                return [
                    'message' => 'Entrando em contato com o atendente. Por favor, aguarde...',
                    'state' => 'contacting_attendant'
                ];
            case '4': // Agendamento de horário presencial
                return [
                    'message' => 'Para agendar um horário presencial, por favor, informe a data desejada ou acesse o calendário disponível no portal.',
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
            case '1': // Holerite
                return $this->getHoleriteQuestions();
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'pre_defined_questions'
                ];
        }
    }

    // Função para apresentar as perguntas de Holerite
    private function getHoleriteQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Holerite:<br>1. Quais são os principais componentes da folha de pagamento?<br>2. O que significa cada código/sigla na minha folha de pagamento?<br>3. Como posso solicitar uma correção ao identificar um erro na minha folha de pagamento?<br>4. Como são calculadas as contribuições na minha folha de pagamento?<br>5. Como posso verificar a conformidade da minha folha de pagamento com o meu contrato?',
            'state' => 'holerite_questions',
            'selectedTopic' => 'holerite'
        ];
    }

    // Função para lidar com a escolha das perguntas de Holerite
    private function handleQuestionSelection($message, $selectedTopic)
    {
        if ($selectedTopic == 'holerite') {
            return $this->getHoleriteAnswer($message);
        }

        // Adicione outros tópicos aqui quando necessário
    }

    private function getHoleriteAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Principais componentes da folha de pagamento:<br>- Salário Base: R$1.420,00<br>- FGTS: 8%<br>- Horas Extras: 50%<br>- Comissões: 5% do valor/ produto.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Códigos/Siglas:<br>- SAL: Salário base.<br>- FGTS: Fundo de Garantia do Tempo de Serviço.<br>- INSS: Instituto Nacional do Seguro Social.<br>- VT: Vale Transporte.<br>- VA: Vale Alimentação.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Para solicitar correção na folha de pagamento, entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Cálculo de contribuições:<br>- INSS: 9%<br>- FGTS: 8%<br>- Vale Transporte: 6%',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Você pode verificar a conformidade da sua folha de pagamento com o seu contrato entrando em contato com um de nossos analistas.',
                    'state' => 'main_menu'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'holerite_questions',
                    'selectedTopic' => 'holerite'
                ];
        }
    }
}
