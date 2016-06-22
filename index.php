<?php
class Tail{

        public static function GetString(){

                $files=scandir(__DIR__);
                $content=array();
                foreach($files as $file){

                 if(strpos($file,'.json')!==false){

                    $time=explode('.', $file);
                    $time=$time[1];

                    $content[$time.""]=file_get_contents(__DIR__.'/'.$file);
                 }

                }
                return json_encode($content, JSON_PRETTY_PRINT);
        }


}

if(isset($_GET)&&$_GET['tail']){

        echo Tail::GetString();

}else{

?>


<html>

<body><h2>Logs</h2><pre id="content"></pre></body>
<script>
var currentTimestamp=0;
setInterval(function(){
var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange=function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      var content=document.getElementById("content");
      var entries =JSON.parse(xhttp.responseText);

      Object.keys(entries).forEach(function(key){
        
        if(currentTimestamp<parseInt(key)){
           content.innerHTML=content.innerHTML+entries[key];
           currentTimestamp=parseInt(key); 
        }

      });

    }
  }
  xhttp.open("GET", "index.php?tail=" + 1, true);
  xhttp.send();


},1000);


</script>
</html>


<?php
}