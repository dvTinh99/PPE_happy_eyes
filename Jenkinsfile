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
          emailext subject: 'jenkin pipeline', to: 'tinh5969@gmail.com'
      }
  }
}
