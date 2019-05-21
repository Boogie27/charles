<footer id='footer' class='text-center footer'><p>&copy;  copyright 2017 - 2018 lucy's boutique</p></footer>


 
 <script>
                    function updatesize(){
                        var size_string = '';
                        for(var i=1; i<=12; i++){
                            if(jQuery("#size"+i).val() != ''){
                                size_string += jQuery("#size"+i).val() +':' +jQuery("#Qty"+i).val()+',';
                            }
                        }
                        jQuery("#sizes").val(size_string);
                    }

              function get_child_option(selected){
                  if(typeof selected === "undefined"){
                        var  selected = "";
                  }
                    var parentID = jQuery("#category").val();
                        jQuery.ajax({
                        url: "/boutique/admin/parsers/child_category.php",
                        type: "POST",
                        data: {parentID : parentID, selected : selected},
                        success: function(data){
                         jQuery("#child").html(data);
                        },
                       error: function(){
                       alert("something went wrong int the child option");
                       }
                    });
                  }
              jQuery("select[name='category']").change(function(){
                get_child_option();
              });
         </script>
          


</body>
</html>