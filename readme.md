# eQuotes plugin

## Development server configuration:

### Configuring webpack server for react modules (live reload):
1) Add to wp-config.php ``define( 'SCRIPT_DEBUG', true );``
2) On */modules/{module-name}/webpack.config.js*, certify that your host matches the host in the configuration;

### Running live reload from webpack server:
From /modules/{modules-name}/ directory ``npm run start:hot``

### Common errors:

##### Invalid Host/Origin header errors:

Your host probably doesn't match the webpack config file for that module.

##### WebSocket connection failed: Error in connection establishment:


This may occur in Chrome, is due the lack of a SSL certificate, you can configure a new one or **Allow the Unsafe content** on Chrome configuration for your website, example:

``chrome://settings/content/siteDetails?site=https://equotes-plugin.test``
