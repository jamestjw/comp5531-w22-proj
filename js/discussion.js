$(".replyMessage")
.click(function(e){
    e.preventDefault();
    $(e.target).parent().next().find("form").toggle();
});
