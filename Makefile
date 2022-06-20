behat:
	@./vendor/bin/sail restart selenium
	@./vendor/bin/sail php ./vendor/bin/behat

ssr:
	@npx mix --mix-config=webpack.ssr.mix.js

watch:
	@yarn hot
