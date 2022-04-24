/*
James Juan Whei Tan - 40161156
*/
document
    .getElementById("displayUpdateForm")
    .addEventListener("click", function(e) {
        var formElem = document.getElementById("updateForm");
        if (formElem.style.display === "none") {
            formElem.style.display = "block";
        } else {
            formElem.style.display = "none";
        }
});