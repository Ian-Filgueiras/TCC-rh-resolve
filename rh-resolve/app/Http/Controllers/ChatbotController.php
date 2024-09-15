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
                    'message' => 'Selecione um tópico:<br>1. Holerite<br>2. Benefícios<br>3. Descontos<br>4. Horas Extras<br>5. Férias<br>6. Atestados Médicos<br>7. Direitos e Deveres da Empresa<br>8. Direitos e Deveres do Funcionário<br>9. Código de Conduta e Ética<br>10. Finalizar',
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
            case '7': // Direitos e Deveres da Empresa
                return $this->getCompanyRightsAndDutiesQuestions();
            case '8': // Direitos e Deveres do Funcionário
                return $this->getEmployeeRightsAndDutiesQuestions();
            case '9': // Código de Conduta e Ética
                return $this->getEthicsCodeQuestions();
            case '10': // Finalizar atendimento
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
            'message' => 'Escolha uma pergunta sobre Holerite:<br>1. Quais são os principais componentes da folha de pagamento?<br>2. O que significa cada código/sigla na minha folha de pagamento?<br>3. Como posso solicitar uma correção ao identificar um erro na minha folha de pagamento?<br>4. Como são calculadas as contribuições na minha folha de pagamento?<br>5. Como posso verificar a conformidade da minha folha de pagamento com o meu contrato?',
            'state' => 'holerite_questions',
            'selectedTopic' => 'holerite'
        ];
    }

    // Função para apresentar as perguntas de Benefícios
    private function getBenefitsQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Benefícios:<br>1. Quais benefícios estão incluídos no meu contrato de trabalho?<br>2. Como posso acessar os benefícios oferecidos pela empresa?<br>3. Existem benefícios adicionais para funcionários com cargos específicos?<br>4. Como posso incluir um dependente no plano de saúde?<br>5. Existem benefícios sazonais que a empresa oferece?',
            'state' => 'benefits_questions',
            'selectedTopic' => 'benefits'
        ];
    }

    private function getDiscountsQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Descontos:<br>1. Quais são os tipos de descontos que podem ser aplicados ao meu salário?<br>2. Como é calculado o desconto referente ao plano de saúde?<br>3. Como posso contestar um desconto incorreto?<br>4. Quais os descontos para faltas não justificadas ou atrasos?<br>5. Como são tratados os descontos por danos ou perdas de equipamentos?',
            'state' => 'discounts_questions',
            'selectedTopic' => 'discounts'
        ];
    }

    private function getOvertimeQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Horas Extras:<br>1. Qual o adicional ofertado pela empresa?<br>2. Como são registrados minhas horas extras?<br>3. Existe um limite de horas extras por dia?<br>4. Como é calculado o valor da hora extra?<br>5. Como é feito o cálculo de horas extras em feriados e finais de semana?',
            'state' => 'overtime_questions',
            'selectedTopic' => 'overtime'
        ];
    }

    private function getVacationQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Férias:<br>1. Como é calculado o valor das minhas férias?<br>2. Qual é o prazo para solicitar minhas férias?<br>3. Posso dividir minhas férias em períodos menores?<br>4. Como posso vender minhas férias?<br>5. Quanto tenho a receber em caso de férias vencidas?',
            'state' => 'vacation_questions',
            'selectedTopic' => 'vacation'
        ];
    }

    private function getMedicalCertificateQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Atestados Médicos:<br>1. Qual o prazo para entregar o atestado médico?<br>2. O que acontece se eu não fornecer o atestado médico no prazo?<br>3. O que fazer se o atestado especifica restrição de atividades?<br>4. Como posso enviar meu atestado médico?<br>5. Como os atestados médicos afetam os meus benefícios?',
            'state' => 'medical_certificate_questions',
            'selectedTopic' => 'medical_certificate'
        ];
    }

    private function getCompanyRightsAndDutiesQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Direitos e Deveres da Empresa:<br>1. Quais são os direitos da empresa em relação à má conduta dos funcionários?<br>2. Quais são os deveres da empresa em relação às condições de trabalho?<br>3. Como a empresa comunica mudanças nas políticas?<br>4. Como registrar reclamações ou sugestões?<br>5. Quais são as responsabilidades da empresa em relação às leis trabalhistas?',
            'state' => 'company_rights_duties_questions',
            'selectedTopic' => 'company_rights_duties'
        ];
    }

    private function getEmployeeRightsAndDutiesQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Direitos e Deveres do Funcionário:<br>1. Quais são os direitos básicos garantidos para os funcionários?<br>2. Quais são as principais responsabilidades no ambiente de trabalho?<br>3. O que devo fazer se meus direitos estão sendo violados?<br>4. Quais são as consequências de não cumprir minhas responsabilidades?<br>5. Quais são as obrigações em relação à confidencialidade de informações?',
            'state' => 'employee_rights_duties_questions',
            'selectedTopic' => 'employee_rights_duties'
        ];
    }

    private function getEthicsCodeQuestions()
    {
        return [
            'message' => 'Escolha uma pergunta sobre Código de Conduta e Ética:<br>1. Como relatar uma violação do código de ética de forma confidencial?<br>2. O que a empresa considera como comportamento inadequado?<br>3. Quais são as consequências para quem violar o código?<br>4. O que fazer se eu testemunhar uma violação do código?<br>5. Como são tratadas as violações do código que afetam a reputação da empresa?',
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
            'message' => 'Sua dúvida foi solucionada?<br>1. Sim<br>1. Não',
            'state' => 'ask_if_solved'
        ];
    }





    // Função para lidar com a escolha das perguntas de Holerite e Benefícios
    private function handleQuestionSelection($message, $selectedTopic)
    {
        if ($selectedTopic == 'holerite') {
            return $this->getHoleriteAnswer($message);
        } elseif ($selectedTopic == 'benefits') {
            return $this->getBenefitsAnswer($message);
        } elseif ($selectedTopic == 'discounts') {
            return $this->getDiscountsAnswer($message);
        } elseif ($selectedTopic == 'overtime') {
            return $this->getOvertimeAnswer($message);
        } elseif ($selectedTopic == 'vacation') {
            return $this->getVacationAnswer($message);
        } elseif ($selectedTopic == 'medical_certificate') {
            return $this->getMedicalCertificateAnswer($message);
        } elseif ($selectedTopic == 'company_rights_duties') {
            return $this->getCompanyRightsAndDutiesAnswer($message);
        } elseif ($selectedTopic == 'employee_rights_duties') {
            return $this->getEmployeeRightsAndDutiesAnswer($message);
        } elseif ($selectedTopic == 'ethics_code') {
            return $this->getEthicsCodeAnswer($message);
        }
    }

    // Função para lidar com as respostas das perguntas de Holerite
    private function getHoleriteAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Principais componentes da folha de pagamento:<br>- Salário Base: R$1.420,00<br>- FGTS: 8%<br>- Horas Extras: 50%<br>- Comissões: 5% do valor/ produto.<br><br>' . $this->askIfSolved()['message'],
                    'state' => 'ask_if_solved'
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

    // Função para lidar com as respostas das perguntas de Benefícios
    private function getBenefitsAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Benefícios incluídos no contrato:<br>- FGTS<br>- Vale Alimentação<br>- Vale Refeição<br>- Horas Extras<br>- Comissões e Bonificações<br>- Plano de Saúde: Cobertura de 70%',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Para acessar os benefícios, baixe o aplicativo Alelo para VA/VR e o aplicativo Hapvida para o plano de saúde.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Benefícios adicionais para cargos específicos:<br>- Supervisores: Bônus sob os lucros da empresa.<br>- Gestores: Planos de aposentadoria.<br>- Administradores: Modalidade home office.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Para incluir um dependente no plano de saúde, preencha o formulário e anexe a documentação comprovando o relacionamento.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Benefícios sazonais incluem bônus de fim de ano e vouchers para compras em perfumarias e cosméticos.',
                    'state' => 'main_menu'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'benefits_questions',
                    'selectedTopic' => 'benefits'
                ];
        }
    }

    private function getDiscountsAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Tipos de descontos:<br>- INSS<br>- FGTS<br>- Vale Transporte: 6%<br>- Vale Alimentação: 8%<br>- Plano de Saúde: R$ 30,00/mensal + coparticipação.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Desconto do plano de saúde:<br>R$ 30,00/mensal + coparticipação de R$ 15,00 para consultas e exames.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Para contestar um desconto incorreto, entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Descontos para faltas não justificadas:<br>Jornada: 8 horas / 5 dias / 160h<br>Valor por hora: R$ 8,87<br>Valor por dia: R$ 71,00.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Descontos por danos ou perdas de equipamentos:<br>1° infração: Advertência<br>2° infração: Suspensão com desconto no salário<br>3° infração: Desligamento.',
                    'state' => 'main_menu'
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
                    'message' => 'O adicional ofertado pela empresa é de 50% sobre o valor da hora.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Suas horas extras são registradas na folha de ponto, que pode ser consultada no portal do funcionário.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Sim, o limite máximo de horas extras é de 2 horas por dia.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Cálculo do valor da hora extra:<br>Seu salário: R$ 1.420,00<br>Valor por hora: R$ 8,87<br>Valor da hora extra: R$ 13,30.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Cálculo de horas extras em feriados e finais de semana:<br>- Feriados: Adicional de 100% (R$ 142,00 por dia).<br>- Finais de semana: Adicional de 50% (R$ 106,50 por dia).',
                    'state' => 'main_menu'
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
                    'message' => 'O valor das suas férias é calculado com um adicional de um terço sobre o salário.<br>- Salário: R$ 1.420,00<br>- Adicional de 1/3: R$ 473,33<br>- Total das férias: R$ 1.893,33.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'O prazo para solicitar suas férias é após 12 meses de trabalho. Você tem 6 meses acumulados até agora.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Sim, você pode dividir suas férias em até 3 períodos:<br>- 1º período: 14 dias<br>- 2º período: 10 dias<br>- 3º período: 6 dias.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Você pode vender até 10 dias das suas férias. Para isso, formalize o pedido por escrito e anexe a documentação.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Se você tiver férias vencidas, o valor a receber é o correspondente ao salário + 1/3 adicional.<br>- Salário: R$ 1.420,00<br>- Adicional de 1/3: R$ 473,33<br>- Total: R$ 1.893,33.',
                    'state' => 'main_menu'
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
                    'message' => 'O prazo para entregar o atestado médico é de até 48 horas após o início da ausência.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Se você não fornecer o atestado médico no prazo, sua ausência será considerada falta injustificada, o que poderá levar a descontos no salário.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Se o atestado especifica restrição de atividades, envie o atestado ao RH especificando as restrições.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Você pode enviar seu atestado médico pelo portal de anexar documentos ou diretamente ao setor de RH.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Atestados médicos podem afetar seus benefícios, com coparticipação no plano de saúde e descontos proporcionais no vale refeição e alimentação.',
                    'state' => 'main_menu'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'medical_certificate_questions',
                    'selectedTopic' => 'medical_certificate'
                ];
        }
    }

    private function getCompanyRightsAndDutiesAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Direitos da empresa em relação à má conduta dos funcionários:<br>- Advertências<br>- Suspensões<br>- Demissões',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Deveres da empresa em relação às condições de trabalho:<br>- Segurança e saúde no ambiente de trabalho<br>- Remuneração justa<br>- Férias de 30 dias<br>- Benefícios obrigatórios.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'A empresa comunica mudanças nas políticas por meio de:<br>- Mural de informações no chatbot<br>- E-mails<br>- Reuniões de equipe<br>- Treinamentos.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Para registrar reclamações ou sugestões, entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Responsabilidades da empresa em relação às leis trabalhistas:<br>- Garantir conformidade com a CLT<br>- Registro correto das horas trabalhadas<br>- Ambiente seguro e saudável.',
                    'state' => 'main_menu'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'company_rights_duties_questions',
                    'selectedTopic' => 'company_rights_duties'
                ];
        }
    }

    private function getEmployeeRightsAndDutiesAnswer($message)
    {
        switch ($message) {
            case '1':
                return [
                    'message' => 'Direitos básicos garantidos para os funcionários:<br>- Remuneração justa<br>- Ambiente seguro e confortável<br>- Igualdade e respeito à privacidade.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'Principais responsabilidades no ambiente de trabalho:<br>- Desempenhar suas tarefas<br>- Cumprir as políticas da empresa<br>- Pontualidade e assiduidade<br>- Uso adequado dos recursos da empresa.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Se seus direitos estão sendo violados, registre os detalhes e entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Consequências de não cumprir suas responsabilidades:<br>- Advertências e repreensões<br>- Suspensão<br>- Rebaixamento de função<br>- Demissão.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Obrigações em relação à confidencialidade de informações:<br>- Não divulgar informações confidenciais<br>- Proteger informações sensíveis como senhas e documentos<br>- Reportar violações de segurança imediatamente.',
                    'state' => 'main_menu'
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
                    'message' => 'Para relatar uma violação do código de ética de forma confidencial, registre os detalhes e entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '2':
                return [
                    'message' => 'A empresa considera como comportamento inadequado:<br>- Fraude<br>- Desonestidade<br>- Assédio moral ou sexual<br>- Discriminação racial, de gênero, religiosa, etc.',
                    'state' => 'main_menu'
                ];
            case '3':
                return [
                    'message' => 'Consequências para quem violar o código:<br>- Advertência verbal e escrita<br>- Suspensão<br>- Reeducação<br>- Rebaixamento de cargo<br>- Demissão.',
                    'state' => 'main_menu'
                ];
            case '4':
                return [
                    'message' => 'Se você testemunhar uma violação do código, registre os detalhes e entre em contato com um analista em tempo real ou agende um horário no RH.',
                    'state' => 'main_menu'
                ];
            case '5':
                return [
                    'message' => 'Violações do código que afetam a reputação da empresa são tratadas com:<br>- Investigação interna e externa<br>- Recolhimento de evidências<br>- Aplicação de medidas disciplinares<br>- Treinamentos sobre ética e conduta.',
                    'state' => 'main_menu'
                ];
            default:
                return [
                    'message' => 'Opção inválida. Por favor, selecione uma opção válida.',
                    'state' => 'ethics_code_questions',
                    'selectedTopic' => 'ethics_code'
                ];
        }
    }
}
