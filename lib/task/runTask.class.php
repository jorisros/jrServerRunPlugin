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
      $php = $this->checkPhp();
      $phpversion = $this->checkVersion();
    } catch(sfCommandException $e)
    {
         //var_dump($e);
    }
    $this->logBlock("\nServer started at http://".$options['host'].":".$options['port']."/frontend_dev.php\n\nPress Control + C to end...\n", 'INFO');

    exec($php." -S ".$options['host'].":".$options['port']." -t ".sfConfig::get('sf_web_dir'));
  }

  private function checkVersion()
  {
    $currentVersion = phpversion();
    $arr = explode('.',$currentVersion);
    $error = "PHP not support a build-in server, us at least 5.4.*, you are using:".$currentVersion;

    if($arr[0]<5 && $arr[1]<4)
    {
      throw new sfException($error);
    }

    return $currentVersion;
  }

  private function checkPhp()
  {
      $paths = explode(PATH_SEPARATOR, getenv('PATH'));
      foreach ($paths as $path) {
        // we need this for XAMPP (Windows)
        if (strstr($path, 'php') && isset($_SERVER["windir"]) && file_exists($path.'\php.exe') && is_file($path.'\php.exe')) {
          return $path.'\php.exe';
        }
        else {
          $php_executable = $path . DIRECTORY_SEPARATOR . "php" . (isset($_SERVER["windir"]) ? ".exe" : "");
          if (file_exists($php_executable) && is_file($php_executable)) {
            return $php_executable;
          }
        }
      }

      throw new sfException("PHP not found !!");
  }
}
