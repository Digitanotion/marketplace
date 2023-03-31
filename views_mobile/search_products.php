<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <link rel="stylesheet" href="../dependencies/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <!-- <input type="text" onkeyup="search_products_mobile(this.value)"> -->
    <?php /* $message="I like google oooo, but i dont like others";
    $size=0;
    $word="oth"; 
    
    echo preg_replace('/^.*?(.{0,'.$size.'})(\b\S*'.$word.'\S*\b)(.{0,'.$size.'}).*?$/i',
            '$1<strong>$2</strong>$3',$message); */?>
    <?php 
    
    //$message="I like google oooo, but i dont like others";
    //echo preg_replace('/\b\S*'.$GET['s'].'\S*\b/i', '<strong>$0</strong>', $message);
    ?>
    <section class="container-fluid">
        <div class="row" id="suggesstion-box">
            <div class="d-flex justify-content-center align-items-center" style="height: 100px;">Type what you are looking for, let's help you get it</div>
            
        </div>
    </section>
    <script src="../dependencies/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../dependencies/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function search_products_mobile(keyword_from_app,user_id) {
            $.ajax({
                type: "POST",
                url: "../mobile_controls/getSearchResult.php",
                data: 'keyword=' + keyword_from_app +'&user_mob_id__='+user_id,
                success: function (data) {
                    //$("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    //search(keyword_from_app)
                    //$("#search-box").css("background", "#FFF");

                }
            });
        }
        //Search and Mark searched
        function search(e) {
            let searched = e.trim(); //document.getElementById("search").value.trim();
            if (searched !== "") {
                let text = document.getElementById("suggesstion-box").innerHTML;
                let re = new RegExp(searched, "g"); // search for all instances
                let newText = text.replace(re, `<span class="mark_searched">${searched}</span>`);
                document.getElementById("suggesstion-box").innerHTML = newText;
            }
        }
        function searchAd(datIt, user_id){
        search_products_mobile(datIt,user_id);
}
        
    </script>
</body>

</html>