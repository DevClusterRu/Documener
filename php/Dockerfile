FROM php:7.4-fpm

RUN apt-get -y update

RUN apt-get install -y libicu-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y zlib1g-dev

RUN  apt-get install -y software-properties-common

RUN  apt-get install -y libfreetype6-dev
RUN  apt-get install -y libjpeg62-turbo-dev
RUN  apt-get install -y libpng-dev

RUN  docker-php-ext-configure intl
RUN  docker-php-ext-install intl



RUN docker-php-ext-configure gd --with-freetype --with-jpeg --enable-gd
RUN docker-php-ext-install gd

RUN docker-php-ext-install \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    mysqli


RUN apt-get install -y p7zip \
    p7zip-full \
    unace \
    zip



RUN apt-get -y update
RUN apt-get install -y -qq libtesseract-dev libleptonica-dev
ENV TESSDATA_PREFIX=/usr/share/tesseract-ocr/4.00/tessdata/
RUN apt-get install -y -qq tesseract-ocr
COPY rus.traineddata /usr/share/tesseract-ocr/4.00/tessdata/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/

CMD ["php-fpm"]