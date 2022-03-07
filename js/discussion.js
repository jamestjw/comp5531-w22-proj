$(".replyMessage")
.click(function(e){
    e.preventDefault();
    $(e.target).parent().next().find("form").toggle();
});

document
    .querySelector("#addPollOption")
    .addEventListener("click", function(e) {
        let optionCountNode = document.querySelector("#option_count");
        var numOptions = parseInt(optionCountNode.value);
        numOptions++;

        optionCountNode.setAttribute("value", numOptions);

        const newOption = document.createElement("input");
        newOption.setAttribute("id", "option_"+numOptions);
        newOption.setAttribute("name", "option_"+numOptions);
        newOption.setAttribute("type", "text");
        newOption.setAttribute("placeholder", "Poll option "+numOptions);

        document.getElementById("pollForm").appendChild(newOption);x
});

document
    .querySelector("#displayAddPoll")
    .addEventListener("click", function(e) {
        document.getElementById("pollForm").style.display = "initial";
        e.target.style.display = "none";
});