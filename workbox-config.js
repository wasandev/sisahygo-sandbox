module.exports = {
    globDirectory: "public/",
    globPatterns: [
        "**/*.{css,ico,eot,svg,ttf,woff,woff2,js,json}",
        "images/*.{png,jpg,jpeg,gif,bmp}",
        "offline.html"
    ],
    swSrc: 'public/sw-base.js',
    swDest: "public/sw.js",
    globIgnores: [
		'../workbox-cli-config.js',
		'photos/**'
  ]
    // Define runtime caching rules.

};


