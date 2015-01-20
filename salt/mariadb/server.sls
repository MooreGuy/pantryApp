mariadb-server:
  pkg.installed:  []

mariadb:
  service.running:
    - name: mariadb
    - require:
      - pkg: mariadb-server

MySQL-python:
  pkg.installed: []

dbconfig:
  mysql_user.present:
    - host: 'localhost'
    - name: 'testuser'
    - password: 'test'
    - require:
      - service: mariadb
      - pkg: MySQL-python

  mysql_database.present:
    - name: 'exampledb'
    - require:
      - mysql_user: testuser
  
  mysql_grants.present:
    - grant: ALL 
    - database: exampledb.*
    - user: 'testuser'
    - require:
      - mysql_database: exampledb 
