php:
  pkg.installed

php-fpm:
  pkg.installed: []
  service.running:
    - require:
      - pkg: php-fpm
