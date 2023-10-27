/* eslint-disable no-undef */
const path = require('path');

// include the css extraction and minification plugins
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const __home = path.resolve(__dirname, '');
const OptimizeCSSAssetsPlugin = require('css-minimizer-webpack-plugin');

module.exports = {
    mode: 'production',
    entry: ['./src/app.scss'],
    stats: {warnings: false},
    module: {
        rules: [
            // compile all .scss files to plain old css
            {
                test: /\.(sass|scss|css)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
            },
        ],
    },
    plugins: [
        // extract css into dedicated file
        new MiniCssExtractPlugin({
            filename: './app.min.css',
        }),
    ],
    optimization: {
        minimize: true,
        minimizer: [
            // enable the css minification plugin
            new OptimizeCSSAssetsPlugin(),
        ],
    },
};
