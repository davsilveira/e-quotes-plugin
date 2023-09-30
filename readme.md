# eQuotes plugin

## Development server configuration:

## Before start
1) Clone the repository
2) Navigate to the cloned repository and initialize/update submodules: `git submodule init && git submodule update`
3) Navigate to /modules/settings-page/ and run: `npm install`
4) Add to wp-config.php `define( 'SCRIPT_DEBUG', true );`
5) We use live reload, on */modules/{module-name}/webpack.config.js*, certify that your host matches the host in the configuration (line 9);

### Running live reload from webpack server:
From /modules/{modules-name}/ directory ``npm run start:hot``

### Common errors:

##### Invalid Host/Origin header errors:

Your host probably doesn't match the webpack config file for that module.

##### WebSocket connection failed: Error in connection establishment:


This may occur in Chrome, is due the lack of a SSL certificate, you can configure a new one or **Allow the Unsafe content** on Chrome configuration for your website, example:

``chrome://settings/content/siteDetails?site=https://equotes-plugin.test``
