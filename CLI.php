<?php

class CLI {

  protected $title = 'Untitled';
  protected $author = '';
  protected $date = '';
  protected $version = '0.0.0';
  protected $description = '';
  protected $examples = array();
  protected $commands = array();
  protected $defaultCommands = array(
    'help' => array(
      'callback' => 'processHelp',
      'alias' => array('-h', '-?'),
      'description' => 'How to use this tool'
    ),
    'examples' => array(
      'callback' => 'processExamples',
      'alias' => '-x',
      'description' => 'Examples of usage'
    )
  );

  public function __construct () {

    $this->commands = array_merge($defaultCommands, $this->commands);

  }

  protected function before ($command) {
  }

  protected function after ($command) {
  }

  public function execute ($commands) {

    if (empty($commands) || !is_array($commands)) {
      echo 'Error: Invalid commands provided';
      var_dump($commands);
      exit(1);
    }

    $originalCommand = count($commands) > 1 ? $commands[1] : 'help';
    $command = $this->commandForAlias($originalCommand);
    if (is_null($command)) {
      echo 'Invalid command: ' + $originalCommand;
      exit(1);
    }
    $settings = $this->commands[$command];
    $callback = $settings['callback'];
    $arguments = array_slice($commands, 2);

    $method = new ReflectionMethod($this, $callback);
    $params = $method->getParameters();

    foreach ($params as $i => $param) {
      if ($i < count($arguments)) {
        $argument = $arguments[$i];
      } elseif ($param->isOptional()) {

      } else {

      }
    }

    $this->before($command);
    call_user_func_array(array($this, $callback), $arguments);
    $this->after($command);

  }

  protected function commandForAlias ($alias) {

    foreach ($this->commands as $command => $settings) {
      if ($alias === $command ||
          (is_string($settings['alias']) && $settings['alias'] === $alias) ||
          (is_array($settings['alias']) && in_array($alias, $settings['alias'], true))) {
        return $command;
      }
    }
    return null;

  }

  protected function processHelp () {

  }

  protected function processExamples () {

  }

}
