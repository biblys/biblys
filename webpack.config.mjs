import Encore from '@symfony/webpack-encore';

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const assetsFolder = process.env.NODE_ENV === 'production' ? 'assets/bundle' : 'build';

Encore
  .setOutputPath(`public/${assetsFolder}`)
  .setPublicPath(`/${assetsFolder}`)
  .addEntry('app', './assets/js/app.js')
  .addEntry('admin', './assets/js/admin.js')
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

export default await Encore.getWebpackConfig();
