# Configuration TLS/SSL pour le site web

# Générer une clé privée RSA
openssl genrsa -out private.key 2048

# Générer une demande de signature de certificat (CSR)
openssl req -new -key private.key -out certificate.csr

# Auto-signer le certificat (pour développement/test)
openssl x509 -req -days 365 -in certificate.csr -signkey private.key -out certificate.crt

# Configuration Apache (à mettre dans le fichier de configuration du virtual host)
<VirtualHost *:443>
    ServerName www.portfolio-francoisdupuy.com
    DocumentRoot /var/www/html
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Configurations de sécurité recommandées
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384
    SSLHonorCipherOrder off
    SSLSessionTickets off
    
    # HSTS (HTTP Strict Transport Security)
    Header always set Strict-Transport-Security "max-age=63072000"
</VirtualHost>

# Note: Pour un environnement de production, il est recommandé d'utiliser un certificat émis par une autorité de certification reconnue comme Let's Encrypt
