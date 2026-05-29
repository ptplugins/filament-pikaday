const esbuild = require('esbuild');

const isDev = process.argv.includes('--dev');

esbuild.build({
    entryPoints: ['resources/js/pikaday-component.js'],
    outfile: 'dist/pikaday-component.js',
    bundle: true,
    minify: !isDev,
    target: ['es2020'],
    format: 'esm',
    external: ['moment'],
}).catch(() => process.exit(1));
