# we have a build folder called dist, so mark it as phony to force update
.PHONY: dist

dist:
	# minify javascript
	esbuild \
		--minify \
		--outfile=dist/littleBIGtable.min.js \
		src/littleBIGtable.js 
	# compress SVG sprite
	svgo \
		-i src/resources/icons.svg \
		--disable=removeUselessDefs \
		--disable=removeTitle
	# fetch latest tested version of AlpineJS
	curl "https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" \
		-o dist/alpine.min.js
