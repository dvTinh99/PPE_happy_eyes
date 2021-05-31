pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        echo 'Building'
        sh 'composer install'
      }
    }

    stage('Test') {
      steps {
        echo 'Testing1'
        sh 'php artisan test'
      }
        steps {
        echo 'Testing2'
      }
        steps {
        echo 'Testing3'
      }
    }

    stage('Deploy') {
      steps {
        echo 'deploing'
      }
    }
  }
  post{
      always{
          mail bcc: '', body: '''Hello !
Come and check your project in jenkins''', cc: '', from: '', replyTo: '', subject: 'Jenkins', to: 'tinh5969@gmail.com'
      }
  }
}
