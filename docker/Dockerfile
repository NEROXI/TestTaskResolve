FROM ubuntu:22.04

ENV TZ=Europe/Kiev
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Обновление репозиториев
RUN apt-get update && apt-get upgrade -y


# Установка Apache, PHP и зависимостей
RUN apt-get install -y curl nano apache2 php libapache2-mod-php php-mysql php-mbstring php-curl php-json php-gd php-xml php-cli unzip php-redis

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="$PATH:/usr/local/bin"

# Переход в директорию приложения
WORKDIR /var/www/html

# Назначение прав на запись для директории storage
RUN chown -R www-data:www-data /var/www/html

# Порт для работы приложения
EXPOSE 80

# Запуск Apache при старте контейнера
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
