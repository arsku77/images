server {
    listen 80;
    
    root /var/www/images/frontend/web;
    index index.php;

    server_name www.images.arsku.com images.arsku.com;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php7.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    listen 443 ssl; # managed by Certbot
ssl_certificate /etc/letsencrypt/live/images.arsku.com/fullchain.pem; # managed by Certbot
ssl_certificate_key /etc/letsencrypt/live/images.arsku.com/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot



    if ($scheme != "https") {
        return 301 https://$host$request_uri;
    } # managed by Certbot


    # Redirect non-https traffic to https
    # if ($scheme != "https") {
    #     return 301 https://$host$request_uri;
    # } # managed by Certbot

}
