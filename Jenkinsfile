pipeline {
   agent any

   stages {
      stage('Build') {
        steps {
           sh 'git pull origin main;composer install'
        }
   }
   stage('Test') {
     steps {
       sh 'php artisan test'
       sh './vendor/bin/phpunit'
     }
   }
   stage('Deploy') {
     steps {
       echo 'Deploying...'
     }
   }
  }
}
