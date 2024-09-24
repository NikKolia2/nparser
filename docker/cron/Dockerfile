FROM ubuntu:latest


ARG DEBIAN_FRONTEND=noninteractive

RUN apt update && apt install -y mc php mc sshpass php-curl mariadb-server composer cron php-mysql php-pdo php-zip php-dom

RUN touch /var/log/cron.log

COPY ./cron-tasks /etc/cron.d/cron-tasks

CMD crontab /etc/cron.d/cron-tasks && cron && tail -f /var/log/cron.log