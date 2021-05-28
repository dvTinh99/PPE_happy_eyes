pipeline {
   agent any

   stages {
      stage('Build') {
        steps {
          sh 'cd /var/www/dvtinh.com/html/PPE_happy_eyes/'
        }
   }
   stage('Test') {
     steps {
        sh 'composer install'
     }
   }
   stage('Deploy') {
     steps {
       echo 'Deploying...'
     }
   }
  }
}
