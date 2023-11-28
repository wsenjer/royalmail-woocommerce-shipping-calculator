pack:
	composer install
	composer bin php-scoper require --dev humbug/php-scoper
	./vendor/bin/php-scoper add-prefix --force
	rm -rf build/bamarni
	rm -rf build/composer
	rm -rf build/dvdoug/boxpacker/features
	mv build/dvdoug/boxpacker/src/* build/dvdoug/boxpacker/
	mv build/psr/log/Psr/Log/* build/psr/log/
	rm -rf build/dvdoug/boxpacker/src
	rm -rf build/psr/log/Psr
	rm -rf build/autoload.php
	-rm -f royalmail-woocommerce-shipping-calculator.zip && git archive --prefix=royalmail-woocommerce-shipping-calculator/ -o royalmail-woocommerce-shipping-calculator.zip HEAD;
