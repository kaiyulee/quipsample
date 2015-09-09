var path = require("path");

module.exports = {
    entry: './src/entry.js',
    output: {
        path: path.join(__dirname, 'www'),
        filename: 'app.js'
        //publicPath: "./www/scripts/"
    },
    module: {
        loaders: [
            {test: /\.js$/, loader: "babel", exclude: /node_modules/},
            {test: /\.css$/, loader: "style!css"},
            {test: /\.(jpg|png)$/, loader: "url?limit=8192"},
            {test: /\.scss$/, loader: "style!css!sass"}
        ]
    }
};
