// Old and probably working
const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const glob = require('glob');

const oldexports = {
  mode: process.env.NODE_ENV,
  entry: {
    app: './src/js/app.js',
  },
  output: {
    filename: 'js/[name].bundle.js',
    path: path.resolve(__dirname, 'dest')
  },
  externals: {
    axios: 'axios',
    localforage: 'localforage',
    moment: 'moment',
    vue: 'Vue',
    vuex: 'Vuex',
    'vue-router': 'VueRouter'
  }
};

// Updated and confirmed working
// Imports
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// Constants
const extractVueSCSS = new MiniCssExtractPlugin({
  filename: 'css/vue.css'
});

// Data
newexports = {
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: [
        'dest/css/vue*',
        'dest/images',
        'dest/js',
      ]
    }),
    new VueLoaderPlugin(),
    extractVueSCSS
  ],
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.(sass|scss)$/,
        use: [
          process.env.NODE_ENV !== 'production'
            ? 'vue-style-loader'
            : MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader',
          {
            loader: 'sass-resources-loader',
            options: {
              resources: path.resolve(__dirname, './src/sass/_inject.scss')
            }
          }
        ]
      },
      {
        test: /\.(png|svg)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]?[hash]',
              outputPath: '/images/'
            }
          }
        ]
      },
      {
        test: /\.(woff|woff2)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]?[hash]',
              outputPath: '/fonts/'
            }
          }
        ]
      }
    ]
  }
};

result = {};
Object.assign(result, oldexports);
Object.assign(result, newexports);

console.log(result);

module.exports = result;
