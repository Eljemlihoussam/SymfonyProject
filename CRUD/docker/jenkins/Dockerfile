FROM jenkins/jenkins:lts

USER root

# Installation des dépendances nécessaires
RUN apt-get update && \
    apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    software-properties-common \
    && rm -rf /var/lib/apt/lists/*

# Installation de Docker
RUN curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add - && \
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable" && \
    apt-get update && \
    apt-get install -y docker-ce docker-ce-cli containerd.io

# Installation des plugins Jenkins
RUN jenkins-plugin-cli --plugins \
    workflow-aggregator:latest \
    git:latest \
    configuration-as-code:latest \
    docker-workflow:latest \
    blueocean:latest \
    pipeline-model-definition:latest \
    pipeline-stage-view:latest \
    pipeline-github:latest \
    pipeline-utility-steps:latest \
    credentials-binding:latest \
    ssh-agent:latest \
    ws-cleanup:latest

# Configuration de Jenkins
COPY jenkins.yaml /var/jenkins_home/jenkins.yaml

USER jenkins 