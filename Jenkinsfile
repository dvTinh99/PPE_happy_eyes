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
        sh 'composer install'
      }
    }

    stage('Deploy') {
      steps {
        echo 'deploing'
        sh '''cp .env.example env
php artisan key:generate'''
      }
    }

  }
}