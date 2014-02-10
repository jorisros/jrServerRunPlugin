<?php

class runTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('host', null, sfCommandOption::PARAMETER_REQUIRED, 'The hostname', 'localhost'),
      new sfCommandOption('port', null, sfCommandOption::PARAMETER_REQUIRED, 'The port where the server runs on', 8000)
      // add your own options here
    ));

    $this->namespace        = 'server';
    $this->name             = 'run';
    $this->briefDescription = 'Runs the build-in server from php to host the project (not for production!!)';
    $this->detailedDescription = <<<EOF
The [run|INFO] task does things.
Call it with:

  [php symfony server:run|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    //  $databaseManager = new sfDatabaseManager($this->configuration);
    // $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    try{
      $this->checkPhpVersion();
    } catch(sfCommandException $e)
    {
      $this->logBlock('Server started at http://localhost:8000/frontend_dev.php\n\nPress Control + C to end...\n', 'INFO');
      //var_dump($e);
    }

    exec("php -S localhost:8000 -t web", $output);
  }

  private function checkPhpVersion()
  {
    //throw new sfCommandException('test');
  }
}
