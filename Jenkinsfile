pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        echo 'Building'
        sh 'composer install'
      }
    }

    stages{
        stage('test1'){
                  steps {
                    echo 'Testing1'
                  }
              }
        stage('test2'){
                  steps {
                    echo 'Testing2'
                  }
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
