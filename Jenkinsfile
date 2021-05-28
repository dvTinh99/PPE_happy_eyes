pipeline {
   agent any

   stages {
      stage('Build') {
        steps {
          sh 'cd /var/www/dvtinh.com/html/PPE_happy_eyes; ls'
        }
   }
   stage('Test') {
     steps {
        sh 'git pull;composer install'
     }
   }
   stage('Deploy') {
     steps {
       echo 'Deploying...'
     }
   }
  }
}
