module.exports = function($,button,node,form){
    $(button).click(function(){
        node.hide();
        form.removeClass('hide');
    });
}
