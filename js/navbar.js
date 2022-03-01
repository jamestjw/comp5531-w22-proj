$(".role-option")
.click(function(e){
    e.preventDefault();
    var role = e.target.innerText.toLowerCase();

    $.post("change_role.php", { role: role } )
        .done(function() {
            // Make all roles inactive
            // $(".role-option").each((_, item) => {
            //     $(item).removeClass("active");
            // })
            // Make selected role active
            // $(e.target).addClass("active");

            // Refreshing the page seems to be more relevant than just changing
            // the active role
            location.reload()
        }).fail(function() {
            alert("Failed to change row.");
        });
});

