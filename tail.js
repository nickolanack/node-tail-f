var spawn = require('child_process').spawn;
var fs = require('fs');


process.chdir(__dirname);

var folders = require('./paths.json');
var args = ['-f'];
folders.forEach(function(d) {

  if (d[d.length - 1] == '/') {

    fs.readdirSync(d).forEach(function(f) {
      args.push(d + f);
    });
  } else {
    args.push(d);
  }


});



var tail = spawn('tail', args);
tail.unref();
tail.stdout.on('data', function(data) {


  var lines = data.toString().split("\n");
  var text = lines.filter(function(l) {
    return l.indexOf('tail/index.php') === -1;
  }).join("\n");

  if (text === '') {
    return;
  }

  var time = (new Date()).getTime();
  fs.writeFile("tail." + time + ".json", text, function(err) {
    if (err) {
      console.log(err);
      return;
    }

    fs.readdir('.', function(err, files) {
      //console.log(files);
      files.forEach(function(f) {
        if (f.indexOf('.json') > 0) {
          var t = parseInt(f.split('.')[1]);
          if (t < (time - 5000)) {
            fs.unlinkSync(f);
            //console.log('remove: ' + f);
          }
        }
      });
    });

  });


  //console.log(data.toString());
});