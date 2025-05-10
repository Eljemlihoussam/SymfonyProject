pipeline {
    agent any

    environment {
        SONAR_TOKEN = credentials('sonarqube-token')
        DOCKER_CREDENTIALS = credentials('dockerhub-creds')
    }

    stages {
        stage('Clone') {
            steps {
                git branch: 'Symfony', url: 'https://github.com/Eljemlihoussam/SymfonyProject.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'docker compose run --rm php composer install'
                sh 'docker compose run --rm php php bin/console cache:clear'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh '''
                    docker compose run --rm php ./vendor/bin/sonar-scanner \
                    -Dsonar.projectKey=crm-symfony \
                    -Dsonar.sources=src \
                    -Dsonar.host.url=http://localhost:9000 \
                    -Dsonar.login=$SONAR_TOKEN
                    '''
                }
            }
        }

        stage('Docker Build') {
            steps {
                sh 'docker build -t tonuserdocker/crm-symfony:latest .'
            }
        }

        stage('Push DockerHub') {
            steps {
                withDockerRegistry(credentialsId: 'dockerhub-creds') {
                    sh 'docker push tonuserdocker/crm-symfony:latest'
                }
            }
        }

        stage('Deploy via Ansible') {
            steps {
                sh 'ansible-playbook -i inventory.ini deploy.yml'
            }
        }
    }
}
