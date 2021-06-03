pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        echo 'Building'
        sh 'composer install'
      }
    }

    stage('test') {
      parallel {
        stage('test') {
          steps {
            echo 'Testing1'
             sh 'php artisan test'
          }
        }

        stage('test performance') {
          steps {
            echo 'test performance'
          }
        }

      }
    }

    stage('Deploy') {
      parallel {
        stage('Deploy') {
          steps {
            echo 'deploing'
          }
        }

        stage('') {
          steps {
            sleep 5
          }
        }

      }
    }

  }
  post {
      always{
          mail(bcc: '', body: '''Hello !
Come and check your project in jenkins''', cc: '', from: '', replyTo: '', subject: 'Jenkins', to: 'tinh5969@gmail.com')
      }
    success {
        echo 'success'
      mail(bcc: '', body: '''Hello !
Come and check your project in jenkins''', cc: '', from: '', replyTo: '', subject: 'Jenkins', to: 'tinh5969@gmail.com')
    }
      failure {
          echo 'toang'
      }

  }
}
