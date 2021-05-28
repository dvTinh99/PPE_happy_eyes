pipeline {
    agent any
    stages {
        stage('Pull') {
            steps {
                sh 'cd /var/www/dvtinh.com/html/PPE_happy_eyes'
            }
            
        }
        stage('install package'){
                sh 'composer install'
        }
        stage('deploy') {
            steps {
                echo 'deploy'
            }
        }
    }
}
