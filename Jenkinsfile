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
        junit 'target/**/.xml'
      }
    }

    stage('Deploy') {
      steps {
        echo 'deploing'
      }
    }

  }
}