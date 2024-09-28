<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{

    public function index()
    {
        session()->forget(['conversationState', 'selectedTopic']);
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

            case 'ask_if_solved': // Pergunta se a dúvida foi solucionada
                if ($message == '1') {
                    return $this->askForRating(); // Pergunta a avaliação de 1 a 10
                } elseif ($message == '2') {
                    return [
                        'message' => 'Encaminhando você para falar com um atendente...',
                        'state' => 'contacting_attendant'
                    ];
                } elseif ($message == '3') {
                    return $this->showMainMenu(); // Volta ao menu principal
                } else {
                    return [
                        'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                        'state' => 'ask_if_solved'
                    ];
                }

            case 'ask_for_rating': // Recebe a avaliação de 1 a 10
                return $this->endConversationWithRating($message); // Finaliza o atendimento com a avaliação



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
                    'message' => 'Selecione um tópico:
                    <br>1. Holerite
                    <br>2. Benefícios
                    <br>3. Descontos
                    <br>4. Horas Extras
                    <br>5. Férias
                    <br>6. Atestados Médicos
                    <br>7. Direitos e Deveres do Funcionário
                    <br>8. Código de Conduta e Ética
                    <br>9. Finalizar',
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
            case '2': // Benefícios
                return $this->getBenefitsQuestions();
            case '3': // Descontos
                return $this->getDiscountsQuestions();
            case '4': // Horas Extras
                return $this->getOvertimeQuestions();
            case '5': // Férias
                return $this->getVacationQuestions();
            case '6': // Atestados Médicos
                return $this->getMedicalCertificateQuestions();
            case '7': // Direitos e Deveres do Funcionário
                return $this->getEmployeeRightsAndDutiesQuestions();
            case '8': // Código de Conduta e Ética
                return $this->getEthicsCodeQuestions();
            case '9': // Finalizar atendimento
                return $this->endConversation();
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
            'message' => 'Escolha uma pergunta sobre Holerite:
        <br>1. Trabalhei a mais do meu horário. Quanto eu vou receber por isso?
        <br>2. Por que eu não recebo salário-família?
        <br>3. Desconheço um desconto na minha folha de pagamento. O que faço?
        <br>4. Quais os descontos para faltas não justificadas ou atrasos?
        <br>5. Qual a data de fechamento da folha?
        <br>6. Qual a duração mínima e máxima do meu intervalo intrajornada e interjornada?',
            'state' => 'holerite_questions',
            'selectedTopic' => 'holerite'
        ];
    }


    // Função para apresentar as perguntas de Benefícios
    private function getBenefitsQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Benefícios:<br>
        1. Como posso incluir dependente no plano de saúde?<br>
        2. Como funciona a licença paternidade/maternidade?<br>
        3. Tenho direito a quantos dias de ausência de acordo com a legislação?',
            'state' => 'benefits_questions',
            'selectedTopic' => 'benefits'
        ];
    }
    private function getDiscountsQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Descontos:<br>
        1. O vale transporte é obrigatório? Até quantos % pode ser descontado do salário?<br>
        2. Por que meu vale transporte tem valores diferentes a cada mês?<br>
        3. Esqueci de bater meu ponto, como faço para regularizar?',
            'state' => 'discounts_questions',
            'selectedTopic' => 'discounts'
        ];
    }

    private function getOvertimeQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Horas Extras:<br>
        1. Quanto recebo ao trabalhar no feriado e/ou final de semana?<br>
        2. Existe um limite de hora extra por dia?<br>
        3. Quantas horas extras posso fazer trabalhando no turno da noite?<br>
        4. Posso fazer horas extras no meu DSR?<br>
        5. Cheguei atrasado, posso compensar meu atraso em outros dias?',
            'state' => 'overtime_questions',
            'selectedTopic' => 'overtime'
        ];
    }

    private function getVacationQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Férias:<br>
        1. Como posso abonar minhas férias?<br>
        2. Qual o prazo para solicitar minhas férias?<br>
        3. Como devo solicitar minhas férias?<br>
        4. Posso dividir minhas férias?',
            'state' => 'vacation_questions',
            'selectedTopic' => 'vacation'
        ];
    }

    private function getMedicalCertificateQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Atestados Médicos:<br>1. O atestado médico é descontado do salário?<br>2. Qual o prazo de entrega do atestado médico?<br>3. Como posso enviar meu atestado médico?',
            'state' => 'medical_certificate_questions',
            'selectedTopic' => 'medical_certificate'
        ];
    }

    private function getEmployeeRightsAndDutiesQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Deveres do Funcionário:<br>
        1. Como faço para solicitar EPI e fardamento?<br>
        2. Como faço para solicitar documento de rendimentos para comprovar o imposto de renda?<br>
        3. Onde posso solicitar o CAT?<br>
        4. Como saber se meu ASO periódico está vencido?',
            'state' => 'employee_rights_duties_questions',
            'selectedTopic' => 'employee_rights_duties'
        ];
    }

    private function getEthicsCodeQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre o Código de Conduta e Ética da Empresa:<br>
        1. Qual é o código de conduta da empresa?<br>
        2. É obrigatório o uso de fardamento e crachá?<br>
        3. Onde posso fazer denúncia de uma situação de discriminação ou assédio?<br>
        4. Qual a política da empresa caso não usar meu EPI corretamente?',
            'state' => 'ethics_code_questions',
            'selectedTopic' => 'ethics_code'
        ];
    }

    private function endConversation()
    {
        return [
            'message' => 'Atendimento finalizado. Obrigado por utilizar nosso serviço! Se precisar de mais alguma coisa, estamos à disposição.',
            'state' => 'main_menu'
        ];
    }

    private function askIfSolved()
    {
        return [
            'message' => 'Sua dúvida foi solucionada?<br>1. Sim<br>2. Não<br>3. Voltar ao menu principal',
            'state' => 'ask_if_solved'
        ];
    }

    private function askForRating()
    {
        return [
            'message' => 'Por favor, avalie o atendimento de 1 a 10.',
            'state' => 'ask_for_rating'
        ];
    }

    private function endConversationWithRating($rating)
    {
        return [
            'message' => 'Obrigado pela sua avaliação de ' . $rating . '/10. Atendimento finalizado.',
            'state' => 'main_menu'
        ];
    }

    private function showMainMenu()
    {
        return [
            'message' => 'Selecione um tópico:<br>1. Início com informações padrões automáticas<br>2. Perguntas pré-definidas para funcionários de empresas cadastradas<br>3. Falar com atendente<br>4. Agendamento de horário presencial com o RH',
            'state' => 'main_menu'
        ];
    }

    // Função para lidar com a escolha das perguntas de Holerite e Benefícios
    private function handleQuestionSelection($message, $selectedTopic)
    {
        switch ($selectedTopic) {
            case 'holerite':
                return $this->getHoleriteAnswer($message);
            case 'benefits':
                return $this->getBenefitsAnswer($message);
            case 'discounts':
                return $this->getDiscountsAnswer($message);
            case 'overtime':
                return $this->getOvertimeAnswer($message);
            case 'vacation':
                return $this->getVacationAnswer($message);
            case 'medical_certificate':
                return $this->getMedicalCertificateAnswer($message);
            case 'employee_rights_duties':
                return $this->getEmployeeRightsAndDutiesAnswer($message);
            case 'ethics_code':
                return $this->getEthicsCodeAnswer($message);
            case 'absence_days':
                return $this->handleAbsenceDays($message);
            case 'aso_check': 
                return $this->transferToAnalyst($message);
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione um tópico válido.',
                    'state' => 'main_menu' // Pode ser um estado padrão, se desejar
                ];
        }
    }



    // Função para lidar com as respostas das perguntas de Holerite
    private function getHoleriteAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Você será compensado pelas horas que excederem sua carga horária regular. O cálculo será feito com base nas horas registradas no seu ponto, e o valor correspondente será adicionado à sua folha de pagamento ou compensado com folga, isso deverá ser acordado com seu líder direto.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'De acordo com a legislação você pode não estar recebendo o salário-família por alguns motivos:<br>- Renda: Sua renda pode estar acima do limite estabelecido (valor salarial de até R$ 1819,26).<br>- Documentação: Pode faltar documento no cadastro.<br>- Critérios: Você pode não se enquadrar nos critérios, de acordo com o Art. 65 a idade do filho pode ser até 14 anos ou sem limite de idade com deficiência.<br><br>Acesse o link que contém os documentos necessários: <a href="https://www.bing.com/ck/a?!&&p=e4ca8fd129a0c02aJmltdHM9MTcyNzA0OTYwMCZpZ3VpZD0xOGQwOTg5MS1iZjlkLTZlZTYtM2MxZi04YzQzYmVjYTZmMTUmaW5zaWQ9NTIyNw">Documentos necessários</a><br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Esse desconto pode ser referente a impostos, benefícios ou faltas. Recomendo que você consulte o seu holerite para ver os detalhes. Se ainda assim não souber a origem do desconto, posso transferi-lo para um analista que poderá ajudar melhor. Gostaria de fazer isso?<br><br>Digite 1 para sim<br>Digite 2 para não.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => 'Os descontos geralmente incluem:<br>- Faltas Não Justificadas: O valor referente às horas ou dias ausentes é descontado com base no seu salário.<br>- Atrasos: Descontos proporcionais ao tempo de atraso, conforme as políticas da empresa.<br>- O desconto ocorrerá com base no dia da falta + DSR (Descanso Semanal Remunerado).<br><br>Exemplo de desconto:<br>João faltou ao trabalho no dia 23/09/2024, sem justificativa ou atestado médico. Seu desconto será:<br>Salário: R$ 2.000,00 ÷ 30 dias = R$ 66,67 (valor do dia)<br>R$ 66,67 ÷ 26 dias úteis X 4 dias não úteis = R$ 10,25 (DSR)<br>R$ 66,67 + R$ 10,25 = Desconto de R$ 76,92.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '5':
                return [
                    'message' => 'A data de fechamento da folha varia de acordo com a empresa. Exemplo: de 21 a 20 do respectivo mês.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '6':
                return [
                    'message' => 'Intervalo intrajornada: o tempo de descanso durante a jornada de trabalho deve ser de no mínimo 1 hora e no máximo 2 horas, dependendo da sua carga horária. Se trabalhar menos de 6 horas, o intervalo é de 15 minutos, mas pode ser negociado com seu líder.<br>Intervalo interjornada: o período de descanso entre duas jornadas de trabalho deve ter no mínimo 11 horas consecutivas.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'holerite_questions',
                    'selectedTopic' => 'holerite'
                ];
        }
    }



    // Função para lidar com as respostas das perguntas de Benefícios
    private function getBenefitsAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Chatbot RH: Para incluir um dependente no seu plano de saúde, siga estes passos:<br>
                    1. Preencha o Formulário: Complete o formulário de inclusão de dependente, que pode estar disponível (link).<br>
                    2. Anexe Documento (Link): Envie a documentação necessária, como certidão de nascimento, casamento ou outros documentos que comprovem a relação de dependência.<br>
                    3. Aguarde Confirmação: Após o envio, aguarde a confirmação do RH ou da administradora do plano sobre a inclusão do seu dependente.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'Chatbot: De acordo com a CLT a licença paternidade é de 5 dias corridos a partir do nascimento do filho, e você receberá a remuneração integral durante esse período, sendo 20 dias para empresas com programa cidadã.<br>
                    Chatbot: E quanto à licença maternidade é de 120 dias. A colaboradora tem direito a esse período sem perder o salário e está protegida contra demissão.<br>
                    Chatbot: É importante que o colaborador informe seu gestor sobre o afastamento com antecedência. Para a licença maternidade, a colaboradora deve apresentar a certidão de nascimento.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Digite 1 para luto<br>Digite 2 para Casamento<br>Digite 3 para Doação de Sangue<br>Digite 4 para doenças',
                    'state' => 'absence_days_questions',
                    'selectedTopic' => 'absence_days'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'benefits_questions',
                    'selectedTopic' => 'benefits'
                ];
        }
    }

    private function handleAbsenceDays($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => '- Até 02 dias consecutivos, a contar do dia do falecimento: (Esposa, filhos, mãe, pai, sogros, irmãos ou pessoa que viva sobre sua dependência financeira.)<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => '- Até 03 dias consecutivos.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => '- 1x ao ano.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => '- Até 15 dias consecutivos. A partir do 16° dia seu encaminhamento será diretamente com o INSS.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'absence_days_questions',
                    'selectedTopic' => 'absence_days'
                ];
        }
    }


    private function getDiscountsAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Não, o vale transporte não é obrigatório, apenas em caso aceito pelo funcionário.<br>
                É creditado o valor total de passagens junto ao pagamento do salário do funcionário, com o desconto de 6%.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'O valor do vale-transporte pode variar de acordo com o número de dias úteis no mês ou mudanças na tarifa do transporte.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Você pode preencher um formulário de ajuste de ponto. É necessário que você forneça informações da data e o horário em que deveria ter batido o ponto.<br>
                Chatbot: Você pode acessá-lo em (Link).<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'discounts_questions',
                    'selectedTopic' => 'discounts'
                ];
        }
    }

    private function getOvertimeAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'O pagamento por trabalhar em feriados e finais de semana pode variar conforme a política da empresa e a legislação vigente.<br><br>Feriados: O trabalho em feriados é normalmente remunerado com um adicional de 100% sobre o valor da hora normal, ou pode ser compensado com um dia de folga, sendo necessário firmar acordo previamente com o seu líder direto.<br><br>Finais de Semana: Para trabalho em finais de semana, a empresa pode pagar um adicional de 50% ou 100% sobre o valor da hora normal.' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'Sim, o limite é de 2 horas por dia.' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Você pode fazer até 2 horas extras por dia, independentemente de trabalhar no turno da noite. No entanto, lembre-se que no turno noturno (a partir das 22h até 5h), além das horas extras, você também recebe o adicional noturno (geralmente 20% a mais sobre o valor da hora normal).' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => 'Sim, mas é preciso firmar acordo com seu líder direto. Pode haver pagamento adicional em dobro referente ao seu DSR.' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '5':
                return [
                    'message' => 'Sim, desde que não seja algo recorrente, é necessário negociar com seu líder direto, especialmente se você tiver um acordo de banco de horas.' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'overtime_questions',
                    'selectedTopic' => 'overtime'
                ];
        }
    }


    private function getVacationAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Você precisará entrar em um acordo com seu líder direto. Pela lei, você pode abonar no máximo 10 dias de suas férias. Para formalizar sua solicitação, siga estes passos:<br>
                1. Formalize o Pedido: Redija um pedido formal indicando a quantidade de dias de férias que deseja vender.<br>
                2. Anexe Documentos: Após preparar o pedido, envie para o e-mail do seu líder direto com o modelo padrão. Baixe no link: google.docs<br>
                3. Acompanhe o retorno deste processo no seu e-mail.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'O prazo para solicitar suas férias é durante o seu período aquisitivo: 12 meses a contar do seu 1° dia de trabalho. Isso permite que o departamento de RH e sua equipe planejem adequadamente.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Preencha o Formulário: Complete o formulário de solicitação de férias, que pode estar disponível em um link.<br>
                Formalize o Pedido: Envie o formulário preenchido e qualquer documentação necessária para o departamento de RH.<br>
                Aguarde Aprovação: Após o envio, seu pedido será revisado e aprovado pelo RH. Eles informarão você sobre a confirmação e as datas das suas férias.<br>
                Confirme a Aprovação: Certifique-se de receber uma confirmação oficial das datas de suas férias para evitar qualquer mal-entendido.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => 'A resposta é Sim! Pela lei, você poderá dividir em até 3 períodos:<br>
                1° período: 14 dias<br>
                2° período: 10 dias<br>
                3° período: 6 dias<br>
                (2 desses períodos ficam a sua preferência, mas 1 período terá obrigatoriamente 14 dias e os outros dois devem ter no mínimo 5 dias corridos).<br>
                Isso precisa ser acordado previamente com a empresa.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'vacation_questions',
                    'selectedTopic' => 'vacation'
                ];
        }
    }


    private function getMedicalCertificateAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'O atestado médico não é descontado do salário. Se você apresentar um atestado válido com a assinatura do médico, o período de ausência é considerado como falta justificada, e você não deve ter o salário descontado por esse motivo.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'De acordo com a legislação, não há prazo de entrega para atestados médicos, mas se o mesmo não for entregue, a ausência pode ser tratada como falta não justificada. Isso pode resultar em descontos no seu salário e, possivelmente, em outras implicações conforme a política da empresa.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Para enviar seu atestado médico, você pode digitalizar ou tirar uma foto legível e enviar para o e-mail do seu líder direto.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'medical_certificate_questions',
                    'selectedTopic' => 'medical_certificate'
                ];
        }
    }


    private function getEmployeeRightsAndDutiesAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Preencha o Formulário: Complete o formulário de solicitação de EPI’s e fardamento, que pode estar disponível (...)Link.<br>
                    Obs: no formulário estarão perguntas sobre tamanho de roupa, número de calçado, etc.<br>
                    Formalize o Pedido: Envie o formulário preenchido para o departamento de RH.<br>
                    Aguarde Aprovação: Após o envio, seu pedido será revisado e aprovado pelo RH. Eles informarão você sobre a confirmação e as datas e disponibilidade de entrega.<br>
                    Confirme a Aprovação: Certifique-se de receber uma confirmação oficial das datas de sua aprovação para evitar qualquer mal-entendido.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'Geralmente, é disponibilizado no início do ano, mas você poderá solicitar ao setor de Recursos Humanos.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => '1. Comunique de imediato ao seu líder direto e ao RH.<br>
                    2. Precisamos das seguintes informações: onde, como aconteceu, horário, tempo de jornada até o momento do incidente.<br>
                    3. Com base nesses dados, acionaremos a equipe de segurança do trabalho para auxiliar na investigação e preenchimento da ficha de análise do acidente.<br>
                    4. Caso já tenha atestado médico, certifique-se que tenha o CID (Código Internacional de Doenças).<br>
                    5. Após essas coletas, o SESMT será responsável pela abertura do CAT no eSocial.<br>
                    6. Prazo para conclusão da investigação: 24 horas.<br>
                    7. Uma cópia será fornecida a você no final do processo.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => 'Você pode verificar a validade do seu ASO periódico consultando o técnico de segurança do trabalho ou Medicina do Trabalho. Sua validade é de 6 a 12 meses.<br>
                    Se ainda assim estiver com dúvidas, posso transferi-lo para um analista que poderá ajudar melhor. Gostaria de fazer isso?<br>
                    Digite 1 para sim<br>
                    Digite 2 para não',
                    'state' => 'transfer_to_analyst',
                    'selectedTopic' => 'aso_check'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'employee_rights_duties_questions',
                    'selectedTopic' => 'employee_rights_duties'
                ];
        }
    }

    private function getEthicsCodeAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Seja pontual e mantenha a assiduidade em seu local de trabalho, mantenha uma postura de respeito e cordialidade com seus colegas de trabalho, utilize corretamente o fardamento e Equipamentos de Proteção Individual (EPI’s) e atue sempre em conformidade com todas as normas e regulamentos da legislação vigente, garantindo que suas ações estejam de acordo com as exigências legais.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'De acordo com as normas da empresa, o uso de fardamento e crachá são obrigatórios para garantir a identificação e a segurança no ambiente de trabalho. E caso acontecer de não utilizar, você poderá receber uma advertência, conforme a política da empresa.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '3':
                return [
                    'message' => 'Sinto muito que tenha acontecido isso com você!<br>Orientamos que recolha as informações necessárias para que possa fazer a denúncia, como: fotos, vídeos, mensagens, áudios, etc. Após, entre diretamente em contato com o seu líder direto e exponha a situação.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            case '4':
                return [
                    'message' => 'Pode resultar em advertências ou até em medidas mais graves, dependendo da situação. Por isso, é importante seguir todas as orientações de segurança.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'ethics_code_questions',
                    'selectedTopic' => 'ethics_code'
                ];
        }
    }

    private function transferToAnalyst($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Chatbot RH: Perfeito! Um momento, vou transferi-lo para um de nossos analistas.<br>[Transferência para o Analista]<br>Analista: Olá! Aqui é o [Nome do Analista]. Como posso ajudar você?',
                    'state' => 'ask_if_solved'
                ];
            case '2':
                return [
                    'message' => 'Sua dúvida foi solucionada?<br>
                1. Sim<br>
                2. Não<br>
                3. Voltar ao menu principal',
                    'state' => 'ask_if_solved'
                ];
            default:
                return [
                    'message' => 'Por favor, escolha uma opção válida.',
                    'state' => 'ask_if_solved'
                ];
        }
    }
}
