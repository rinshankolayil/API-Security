# API Security

<ul>
  <li>Crypt RSA</li>
  <li>OAuth2</li>
  <li>Open SSL</li>
</ul>
Step 1
<br>
<ol>
  <li>Enable open ssl in web browser nginx or apache</li>
  <li>Restart web server</li>
</ol>
<br>
Step 2
<br>
<ol>
  <li>Download OpenSSL from https://code.google.com/archive/p/openssl-for-windows/downloads for windows</li>
  <li>The new latest stable version of OpenSSL has been installed from source on Linux Ubuntu 18.04 and CentOS 7.5</li>
  <li>Extract folder and save in your prefered location</li>
  <li>Open commdand prompt in the bin folder of extracted folder or add environment variable</li>
  <li>Example : D:\PATH\OPENSSL\bin</li>
</ol>
<br>
Step 3
<br>
Generate Private & Public key using OpenSSL
<br>
<code>openssl genrsa -out private-key.pem 1048</code>
<br>
<code>openssl rsa -in private-key.pem -pubout -out public-key.pem</code>




