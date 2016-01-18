Instalación aplicación web MiTeSt
=================================

Instalación aplicación
----------------------

### Instalación compartiendo framework

Instalación framework en /usr/share/sowerphp:

	$ wget -c https://raw.githubusercontent.com/SowerPHP/sowerpkg/master/sowerpkg.sh
	$ ./sowerpkg.sh install -e "app general" -W

Instalación del proyecto MiTeSt:

	$ git clone https://github.com/mitestcl/mitest-webapp /var/www/html/mitest

### Instalación todo en uno (sin compartir)

	$ wget -c https://raw.githubusercontent.com/SowerPHP/sowerpkg/master/sowerpkg.sh
	$ ./sowerpkg.sh install -e "app general" -d /home/delaf/public_html/sowerphp
