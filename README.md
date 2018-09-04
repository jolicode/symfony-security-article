# Article: Symfony and Security

This repository is the support of an Article published on
[https://jolicode.com/blog/](https://jolicode.com/blog/FIXME).


## Installation

    git clone git@github.com:jolicode/symfony-security-article.git
    cd symfony-security-article
    composer install
    # configure .env file
    bin/console doctrine:database:create
    bin/console doctrine:migration:migrate
    bin/console doctrine:fixture:load

## Usage

    bin/console server:start
