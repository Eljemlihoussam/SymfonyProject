pipeline {
    agent any

    environment {
        COMPOSER_ALLOW_SUPERUSER = 1
        DOCKER_IMAGE = 'eljemlihoussam/crm-symfony'
        DOCKER_TAG = "${BUILD_NUMBER}"
        GIT_CREDENTIALS_ID = 'github-token'
    }

    stages {
        stage('Install Dependencies') {
            steps {
                sh '''
                    apt-get update && apt-get install -y \
                    git zip unzip docker-ce-cli \
                    php php-cli php-curl php-mbstring php-xml php-zip \
                    php-mysql \
                    lsb-release ca-certificates apt-transport-https software-properties-common
                '''
                sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
                dir('CRUD') {
                    sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                }
            }
        }

        stage('Tests') {
            steps {
                dir('CRUD') {
                    sh 'php bin/phpunit'
                }
            }
        }

        stage('Security Check') {
            steps {
                sh 'composer require --dev symfony/security-checker'
                sh 'php bin/security-checker security:check'
            }
        }

        stage('Build') {
            steps {
                sh 'php bin/console cache:clear'
                sh 'php bin/console cache:warmup'
                sh 'php bin/console assets:install'
            }
        }

        stage('Deploy') {
            steps {
                sh 'docker-compose up -d --build'
            }
        }

        stage('Build Docker Image') {
            steps {
                dir('CRUD') {
                    script {
                        docker.build("${DOCKER_IMAGE}:${DOCKER_TAG}")
                    }
                }
            }
        }

        stage('Push to DockerHub') {
            steps {
                script {
                    docker.withRegistry('https://index.docker.io/v1/', 'dockerhub-credentials') {
                        docker.image("${DOCKER_IMAGE}:${DOCKER_TAG}").push()
                        docker.image("${DOCKER_IMAGE}:${DOCKER_TAG}").tag('latest')
                        docker.image("${DOCKER_IMAGE}:latest").push()
                    }
                }
            }
        }

        stage('Deploy with Ansible') {
            when {
                expression { fileExists('deploy.yml') }
            }
            steps {
                dir('CRUD') {
                    ansiblePlaybook(
                        playbook: '../deploy.yml',
                        inventory: '../inventory.ini',
                        extras: [
                            "docker_image=${DOCKER_IMAGE}",
                            "docker_tag=${DOCKER_TAG}"
                        ]
                    )
                }
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        success {
            echo 'Pipeline completed successfully!'
        }
        failure {
            echo 'Pipeline failed!'
        }
    }
}
