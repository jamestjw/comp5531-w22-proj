/*
James Juan Whei Tan - 40161156
*/
document
    .querySelectorAll(".role-option")
    .forEach(n => {
        n.addEventListener("click", function(e) {
            e.preventDefault();
            var role = e.target.innerText.toLowerCase();

            var request = new XMLHttpRequest();
            request.onreadystatechange = function()
            {
                // When request is complete
                if (request.readyState == 4)
                {
                    if (request.status == 200) {
                        location.reload();
                    } else {
                        alert("Failed to change role.");
                    }
                }
            };
            request.open("POST", "change_role.php");
            request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            request.send("role="+role);
        })
    });