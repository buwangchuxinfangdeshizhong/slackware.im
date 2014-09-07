module.exports = function($){
    var iswatch = $('#watch-post');
    iswatch.on('click',function(){
        $.ajax({
            url: watchPostUrl,
        }).done(function() {

        });
    }
              );}
