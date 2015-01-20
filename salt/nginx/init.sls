nginx:
  pkg.installed:  []
  service.running:
    - require:
      - pkg: nginx

/etc/nginx/nginx.conf:
  file.managed:
    - source: salt://nginx/nginx.conf 
    - mode: 644
    - user: root
  
