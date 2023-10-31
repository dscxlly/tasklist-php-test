<?php

// Definição da estrutura de tarefa
class Task {
    public $description; // Descrição da tarefa
    public $is_completed; // Indica se a tarefa está concluída (false - Não concluída, true - Concluída)

    public function __construct($description) {
        $this->description = $description;
        $this->is_completed = false;
    }
}

// Classe Lista de Tarefas
class TaskList {
    public $tasks;      // Array de tarefas
    public $task_count; // Contagem de tarefas na lista

    public function __construct() {
        $this->tasks = [];
        $this->task_count = 0;
    }

    // Função para adicionar uma tarefa à lista
    public function addTask($description) {
        if ($this->task_count < 100) { // Verifica se a lista não está cheia (limite de 100 tarefas)
            $this->tasks[] = new Task($description); // Cria uma nova tarefa e adiciona à lista
            $this->task_count++; // Incrementa a contagem de tarefas
        }
    }

    // Função para marcar uma tarefa como concluída
    public function completeTask($task_index) {
        if ($task_index >= 0 && $task_index < $this->task_count) { // Verifica se o índice da tarefa é válido
            $this->tasks[$task_index]->is_completed = true; // Marca a tarefa como concluída
        }
    }

    // Função para listar tarefas pendentes
    public function getPendingTasks() {
        $result = [];
        foreach ($this->tasks as $task) {
            if (!$task->is_completed) { // Verifica se a tarefa não está concluída
                $result[] = $task->description; // Adiciona a descrição da tarefa pendente ao resultado
            }
        }
        return $result;
    }
}

// TESTES

function testAddTask() {
    $list = new TaskList();
    $list->addTask("Fazer compras");
    // Verifica se a tarefa foi adicionada corretamente
    assert($list->tasks[0]->description === "Fazer compras");
    assert($list->tasks[0]->is_completed === false);
}

function testCompleteTask() {
    $list = new TaskList();
    $list->addTask("Ler livro");
    $list->completeTask(0);
    // Verifica se a tarefa foi marcada como concluída
    assert($list->tasks[0]->is_completed === true);
}

function testGetPendingTasks() {
    $list = new TaskList();
    $list->addTask("Ligar para o cliente");
    $list->addTask("Enviar relatório");
    $list->completeTask(1);

    $result = $list->getPendingTasks();
    // Verifica se a lista de tarefas pendentes está correta
    assert($result[0] === "Ligar para o cliente");
    assert(count($result) === 1);
}

function testAddTaskLimit() {
    $list = new TaskList();
    for ($i = 0; $i < 100; $i++) {
        $list->addTask("Nova tarefa");
    }
    $list->addTask("Tentativa de adicionar uma tarefa a uma lista cheia");
    // Verifica se a tarefa não foi adicionada
    assert($list->task_count === 100);
}

function testCompleteInvalidTask() {
    $list = new TaskList();
    $list->addTask("Tarefa 1");
    $list->addTask("Tarefa 2");
    $list->completeTask(2); // Tenta marcar uma tarefa inválida
    // Verifica se a marcação não afetou a lista
    assert($list->tasks[0]->is_completed === false);
    assert($list->tasks[1]->is_completed === false);
}

// Executa os testes
testAddTask();
testCompleteTask();
testGetPendingTasks();
testAddTaskLimit();
testCompleteInvalidTask();

echo "Todos os testes passaram!";

// TESTES COM TRATAMENTO DE ERROS

// Teste que falha propositalmente ao tentar adicionar uma tarefa a uma lista cheia
function testAddTaskLimitWithException() {
    $list = new TaskList();
    for ($i = 0; $i < 100; $i++) {
        $list->addTask("Nova tarefa");
    }
    try {
        $list->addTask("Tentativa de adicionar uma tarefa a uma lista cheia");
        // Simule uma falha de teste
        throw new Exception("O teste deveria ter lançado uma exceção, mas não lançou.");
    } catch (Exception $e) {
        // Tratamento de erro esperado
        assert($list->task_count === 100);
    }
}

// Teste que falha propositalmente ao tentar marcar uma tarefa inválida como concluída
function testCompleteInvalidTaskWithException() {
    $list = new TaskList();
    $list->addTask("Tarefa 1");
    $list->addTask("Tarefa 2");
    try {
        $list->completeTask(2); // Tenta marcar uma tarefa inválida
        // Simule uma falha de teste
        throw new Exception("O teste deveria ter lançado uma exceção, mas não lançou.");
    } catch (Exception $e) {
        // Tratamento de erro esperado
        assert($list->tasks[0]->is_completed === false);
        assert($list->tasks[1]->is_completed === false);
    }
}

// Executa os testes com tratamento de erros
testAddTaskLimitWithException();
testCompleteInvalidTaskWithException();

echo "\n"; // Pula linha entre o resultados dos testes simples e os testes com tratamento de erros
echo "Todos os testes com tratamento de erros foram executados!";

?>