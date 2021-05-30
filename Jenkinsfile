pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        echo 'Building'
        sh 'ls'
      }
    }

    stage('Test') {
      steps {
        echo 'Testing'
        sh 'php artisan test'
        junit 'build/reports/**/*.xml'
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
